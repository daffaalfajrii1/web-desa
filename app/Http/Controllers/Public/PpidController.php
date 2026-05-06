<?php

namespace App\Http\Controllers\Public;

use App\Models\PpidRequest;
use App\Models\PpidSection;
use App\Services\Public\SimpleCaptchaService;
use Illuminate\Http\Request;

class PpidController extends BasePublicController
{
    public function index(SimpleCaptchaService $captcha)
    {
        $sections = $this->fromTable('ppid_sections', function () {
            $order = PpidSection::typeOrder();

            return PpidSection::query()
                ->where('is_active', true)
                ->with(['documents' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
                ->get()
                ->sortBy(function (PpidSection $s) use ($order) {
                    $ti = array_search($s->type, $order, true);

                    return (($ti !== false ? $ti : 99) * 10000) + (int) $s->sort_order;
                })
                ->values();
        }, collect());

        $grouped = $sections->groupBy('type');

        return view('public.pages.ppid', [
            'sections' => $sections,
            'grouped' => $grouped,
            'typeLabels' => PpidSection::typeLabels(),
            'typeOrder' => PpidSection::typeOrder(),
            'captcha' => $captcha->challenge('ppid_request'),
        ]);
    }

    public function storeRequest(Request $request, SimpleCaptchaService $captcha)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'institution' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'request_content' => 'required|string|max:5000',
            'captcha_answer' => 'required|string|max:10',
        ]);

        if (! $captcha->verify('ppid_request', $data['captcha_answer'])) {
            return back()
                ->withErrors(['captcha_answer' => 'Jawaban captcha tidak sesuai.'])
                ->withInput($request->except('captcha_answer'));
        }

        unset($data['captcha_answer']);

        try {
            PpidRequest::create($data + ['status' => 'new']);
        } catch (\Throwable) {
            return back()
                ->withInput()
                ->with('error', 'Permohonan tidak dapat disimpan. Silakan coba lagi.');
        }

        return redirect()
            ->route('public.ppid.index')
            ->with('success', 'Permohonan informasi berhasil dikirim. Tim PPID akan menindaklanjuti sesuai ketentuan.');
    }
}
