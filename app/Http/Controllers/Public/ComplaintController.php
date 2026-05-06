<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Services\Public\SimpleCaptchaService;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function create(SimpleCaptchaService $captcha)
    {
        return view('public.complaints.create', [
            'captcha' => $captcha->challenge('complaint'),
            'statusComplaint' => null,
            'ticketQuery' => '',
        ]);
    }

    public function checkStatus(Request $request)
    {
        $ticket = trim((string) $request->input('ticket', ''));
        $statusComplaint = null;

        if ($ticket !== '') {
            $statusComplaint = Complaint::query()
                ->where('complaint_code', $ticket)
                ->first();
        }

        return view('public.complaints.status', [
            'statusComplaint' => $statusComplaint,
            'ticketQuery' => $ticket,
        ]);
    }

    public function store(Request $request, SimpleCaptchaService $captcha)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|max:32',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
            'subject' => 'required|string|max:255',
            'complaint_text' => 'required|string|max:5000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpeg,jpg,png,gif,webp,pdf|max:10240',
            'captcha_answer' => 'required|string|max:10',
        ], [
            'attachments.max' => 'Maksimal 5 berkas lampiran.',
            'attachments.*.mimes' => 'Lampiran harus foto (JPEG, PNG, GIF, WEBP) atau PDF.',
            'attachments.*.max' => 'Setiap lampiran maksimal 10 MB.',
        ]);

        if (! $captcha->verify('complaint', $data['captcha_answer'])) {
            return back()
                ->withErrors(['captcha_answer' => 'Jawaban captcha tidak sesuai.'])
                ->withInput($request->except('captcha_answer'));
        }

        unset($data['captcha_answer']);

        $uploads = $data['attachments'] ?? null;
        unset($data['attachments']);

        $complaint = Complaint::create($data + [
            'status' => 'masuk',
            'submitted_at' => now(),
        ]);

        $storedPaths = [];
        if ($uploads !== null && $uploads !== []) {
            foreach ($uploads as $file) {
                if ($file && $file->isValid()) {
                    $storedPaths[] = $file->store('complaints/'.$complaint->id, 'public');
                }
            }
            if ($storedPaths !== []) {
                $complaint->update(['attachments' => $storedPaths]);
            }
        }

        return redirect()
            ->route('public.complaints.create')
            ->with('success', 'Pengaduan berhasil dikirim. Nomor tiket: '.$complaint->complaint_code);
    }
}
