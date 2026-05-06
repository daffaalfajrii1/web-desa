<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\SelfService;
use App\Models\SelfServiceSubmission;
use App\Services\Public\RecaptchaVerificationService;
use App\Services\Public\SimpleCaptchaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SelfServiceController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        return view('public.self-services.index', [
            'services' => SelfService::query()
                ->where('is_active', true)
                ->when($search !== '', fn ($query) => $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('service_name', 'like', '%'.$search.'%')
                        ->orWhere('service_code', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('requirements', 'like', '%'.$search.'%');
                    if (Schema::hasColumn('self_services', 'slug')) {
                        $subQuery->orWhere('slug', 'like', '%'.$search.'%');
                    }
                }))
                ->orderBy('sort_order')
                ->orderBy('service_name')
                ->get(),
            'search' => $search,
        ]);
    }

    public function status(Request $request)
    {
        $registrationNumber = Str::upper(trim((string) $request->query('registration_number', $request->query('q', ''))));
        $contact = trim((string) $request->query('contact', ''));
        $submission = null;
        $notice = null;

        if ($registrationNumber !== '') {
            if ($contact === '') {
                $notice = 'Masukkan No. HP atau NIK yang dipakai saat mengajukan layanan.';
            } else {
                $submission = SelfServiceSubmission::query()
                    ->with('service')
                    ->where('registration_number', $registrationNumber)
                    ->first();

                if (! $submission || ! $this->contactMatches($submission, $contact)) {
                    $submission = null;
                    $notice = 'Data pengajuan tidak ditemukan. Periksa nomor registrasi dan No. HP/NIK.';
                }
            }
        }

        return view('public.self-services.status', [
            'registrationNumber' => $registrationNumber,
            'contact' => $contact,
            'submission' => $submission,
            'notice' => $notice,
            'statuses' => SelfServiceSubmission::statuses(),
        ]);
    }

    public function show(SelfService $selfService, SimpleCaptchaService $captcha, RecaptchaVerificationService $recaptcha)
    {
        abort_unless($selfService->is_active, 404);

        return view('public.self-services.show', [
            'service' => $selfService->load(['fields' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')]),
            'captcha' => $recaptcha->isConfigured()
                ? null
                : $captcha->challenge('self_service_'.$selfService->slug),
            'useRecaptcha' => $recaptcha->isConfigured(),
        ]);
    }

    public function store(
        Request $request,
        SelfService $selfService,
        SimpleCaptchaService $captcha,
        RecaptchaVerificationService $recaptcha,
    ) {
        abort_unless($selfService->is_active, 404);

        $service = $selfService->load(['fields' => fn ($q) => $q->orderBy('sort_order')->orderBy('id')]);

        if ($service->fields->isEmpty()) {
            return back()->withErrors(['fields' => 'Pengajuan online untuk layanan ini belum tersedia. Silakan hubungi kantor desa.'])->withInput();
        }

        $rules = $this->rulesForService($service, $recaptcha);
        $data = $request->validate($rules);

        if ($recaptcha->isConfigured()) {
            if (! $recaptcha->verify($data['g-recaptcha-response'] ?? null, $request->ip())) {
                return back()
                    ->withErrors(['g-recaptcha-response' => 'Verifikasi reCAPTCHA gagal. Coba lagi.'])
                    ->withInput($request->except('g-recaptcha-response'));
            }
            unset($data['g-recaptcha-response']);
        } else {
            if (! $captcha->verify('self_service_'.$service->slug, $data['captcha_answer'])) {
                return back()
                    ->withErrors(['captcha_answer' => 'Jawaban captcha tidak sesuai.'])
                    ->withInput($request->except('captcha_answer'));
            }

            unset($data['captcha_answer']);
        }

        $inputFields = $request->input('fields', []);
        $formData = [];
        $attachments = [];

        foreach ($service->fields as $field) {
            $name = $field->field_name;

            if ($field->field_type === 'file') {
                if ($request->hasFile('fields.'.$name)) {
                    $stored = $request->file('fields.'.$name)->store(
                        'self-service/'.$service->id,
                        'public'
                    );
                    $formData[$name] = $stored;
                    $attachments[$name] = $stored;
                }

                continue;
            }

            if ($field->field_type === 'checkbox') {
                $formData[$name] = $inputFields[$name] ?? [];

                continue;
            }

            $formData[$name] = $inputFields[$name] ?? null;
        }

        $applicant = $this->resolveApplicantColumnsFromFormData($formData);
        $nameFilled = trim($applicant['applicant_name'] ?? '') !== '';
        $hasContact = trim($applicant['applicant_phone'] ?? '') !== '' || trim($applicant['applicant_nik'] ?? '') !== '';
        if (! $nameFilled) {
            return back()
                ->withErrors([
                    'fields' => 'Pastikan kolom nama pemohon telah diisi lengkap sesuai identitas resmi Anda.',
                ])
                ->withInput();
        }
        if (! $hasContact) {
            return back()
                ->withErrors([
                    'fields' => 'Isilah nomor HP/WhatsApp atau NIK Anda agar permohonan dapat diverifikasi dan statusnya dapat dicek kemudian.',
                ])
                ->withInput();
        }

        $submission = SelfServiceSubmission::create([
            'self_service_id' => $service->id,
            'applicant_name' => Str::limit((string) $applicant['applicant_name'], 255),
            'applicant_nik' => $applicant['applicant_nik'] !== null ? Str::limit((string) $applicant['applicant_nik'], 32) : null,
            'applicant_phone' => $applicant['applicant_phone'] !== null ? Str::limit((string) $applicant['applicant_phone'], 50) : null,
            'applicant_email' => $applicant['applicant_email'] !== null ? Str::limit((string) $applicant['applicant_email'], 255) : null,
            'applicant_address' => $applicant['applicant_address'] !== null ? Str::limit((string) $applicant['applicant_address'], 10000) : null,
            'form_data' => $formData,
            'attachments' => $attachments !== [] ? $attachments : null,
            'status' => 'masuk',
            'submitted_at' => now(),
        ]);

        return redirect()
            ->route('public.self-services.status', [
                'registration_number' => $submission->registration_number,
                'contact' => $submission->applicant_phone ?: $submission->applicant_nik,
            ])
            ->with('success', 'Permohonan berhasil dikirim. Nomor registrasi: '.$submission->registration_number);
    }

    public function downloadResult(Request $request, string $registrationNumber)
    {
        $contact = trim((string) $request->query('contact', ''));

        $submission = SelfServiceSubmission::query()
            ->where('registration_number', Str::upper($registrationNumber))
            ->firstOrFail();

        abort_unless($contact !== '' && $this->contactMatches($submission, $contact), 403);

        abort_unless($submission->result_file && Storage::disk('public')->exists($submission->result_file), 404);

        $extension = pathinfo($submission->result_file, PATHINFO_EXTENSION);
        $fileName = Str::slug($submission->result_title ?: $submission->registration_number).($extension ? '.'.$extension : '');

        return Storage::disk('public')->download($submission->result_file, $fileName);
    }

    private function contactMatches(SelfServiceSubmission $submission, string $contact): bool
    {
        $contactDigits = preg_replace('/\D+/', '', $contact);
        $phoneDigits = preg_replace('/\D+/', '', (string) $submission->applicant_phone);
        $nikDigits = preg_replace('/\D+/', '', (string) $submission->applicant_nik);

        return ($contactDigits !== '' && (
            hash_equals($phoneDigits, $contactDigits)
            || hash_equals($nikDigits, $contactDigits)
        )) || hash_equals((string) $submission->applicant_phone, $contact)
            || hash_equals((string) $submission->applicant_nik, $contact);
    }

    /** @return array{applicant_name: ?string, applicant_nik: ?string, applicant_phone: ?string, applicant_email: ?string, applicant_address: ?string} */
    private function resolveApplicantColumnsFromFormData(array $formData): array
    {
        $lookup = [];
        foreach ($formData as $key => $value) {
            $lookup[Str::lower((string) $key)] = $value;
        }

        $pickString = static function (array $aliasKeys) use ($lookup): ?string {
            foreach ($aliasKeys as $alias) {
                if (! array_key_exists($alias, $lookup)) {
                    continue;
                }
                $val = $lookup[$alias];
                if (is_array($val)) {
                    continue;
                }
                $s = trim((string) $val);
                if ($s !== '') {
                    return $s;
                }
            }

            return null;
        };

        return [
            'applicant_name' => $pickString(['nama_pemohon', 'nama', 'nama_lengkap', 'nama_warga']),
            'applicant_nik' => $pickString(['nik', 'no_nik', 'nomor_nik', 'nomor_ktp']),
            'applicant_phone' => $pickString(['no_hp', 'nomor_hp', 'telepon', 'nomor_telepon', 'hp', 'whatsapp', 'wa']),
            'applicant_email' => $pickString(['email', 'e_mail', 'surel']),
            'applicant_address' => $pickString(['alamat', 'alamat_lengkap', 'domisili', 'alamat_pemohon']),
        ];
    }

    private function rulesForService(SelfService $service, RecaptchaVerificationService $recaptcha): array
    {
        $rules = [];

        if ($recaptcha->isConfigured()) {
            $rules['g-recaptcha-response'] = 'required|string';
        } else {
            $rules['captcha_answer'] = 'required|string|max:10';
        }

        foreach ($service->fields as $field) {
            $key = 'fields.'.$field->field_name;
            $opts = (array) ($field->options ?? []);

            switch ($field->field_type) {
                case 'textarea':
                    $rules[$key] = ($field->is_required ? 'required' : 'nullable').'|string|max:20000';
                    break;

                case 'number':
                    $rules[$key] = ($field->is_required ? 'required' : 'nullable').'|numeric';
                    break;

                case 'date':
                    $rules[$key] = ($field->is_required ? 'required' : 'nullable').'|date';
                    break;

                case 'select':
                case 'radio':
                    if ($opts !== []) {
                        $rules[$key] = [
                            $field->is_required ? 'required' : 'nullable',
                            'string',
                            Rule::in($opts),
                        ];
                    } else {
                        $rules[$key] = ($field->is_required ? 'required' : 'nullable').'|string|max:500';
                    }
                    break;

                case 'checkbox':
                    $rules[$key] = ($field->is_required ? 'required|array|min:1' : 'nullable').'|array';
                    if ($opts !== []) {
                        $rules[$key.'.*'] = ['string', Rule::in($opts)];
                    }
                    break;

                case 'file':
                    $rules[$key] = ($field->is_required ? 'required' : 'nullable').'|file|max:10240';
                    break;

                default:
                    $rules[$key] = ($field->is_required ? 'required' : 'nullable').'|string|max:5000';
            }
        }

        return $rules;
    }
}
