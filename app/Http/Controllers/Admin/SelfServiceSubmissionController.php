<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SelfService;
use App\Models\SelfServiceSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SelfServiceSubmissionController extends Controller
{
    public function index(Request $request, SelfService $self_service)
    {
        $self_service->load('fields');
        $statuses = SelfServiceSubmission::statuses();

        $summary = collect(array_keys($statuses))
            ->mapWithKeys(fn ($status) => [
                $status => $self_service->submissions()->where('status', $status)->count(),
            ])
            ->all();

        $items = $self_service->submissions()
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->date_from, fn ($q) => $q->whereDate('submitted_at', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->whereDate('submitted_at', '<=', $request->date_to))
            ->when($request->search, function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($query) use ($search) {
                    $query->where('registration_number', 'like', '%' . $search . '%')
                        ->orWhere('applicant_name', 'like', '%' . $search . '%')
                        ->orWhere('applicant_nik', 'like', '%' . $search . '%')
                        ->orWhere('applicant_phone', 'like', '%' . $search . '%')
                        ->orWhere('applicant_email', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.layanan-mandiri.submissions.index', [
            'service' => $self_service,
            'items' => $items,
            'statuses' => $statuses,
            'summary' => $summary,
        ]);
    }

    public function show(SelfService $self_service, SelfServiceSubmission $submission)
    {
        $this->ensureSubmissionBelongsToService($self_service, $submission);
        $self_service->load('fields');

        return view('admin.layanan-mandiri.submissions.show', [
            'service' => $self_service,
            'item' => $submission,
        ]);
    }

    public function edit(SelfService $self_service, SelfServiceSubmission $submission)
    {
        $this->ensureSubmissionBelongsToService($self_service, $submission);
        $self_service->load('fields');

        return view('admin.layanan-mandiri.submissions.edit', [
            'service' => $self_service,
            'item' => $submission,
            'statuses' => SelfServiceSubmission::statuses(),
            'resultTypes' => SelfServiceSubmission::resultTypes(),
        ]);
    }

    public function update(Request $request, SelfService $self_service, SelfServiceSubmission $submission)
    {
        $this->ensureSubmissionBelongsToService($self_service, $submission);

        $data = $request->validate([
            'status' => ['required', Rule::in(array_keys(SelfServiceSubmission::statuses()))],
            'admin_note' => 'nullable|string',
            'result_type' => ['nullable', Rule::in(array_keys(SelfServiceSubmission::resultTypes()))],
            'result_title' => 'nullable|string|max:255',
            'result_note' => 'nullable|string',
            'result_file' => 'nullable|file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
            'remove_result_file' => 'nullable|boolean',
        ]);

        if ($data['status'] === 'masuk') {
            $data['processed_at'] = null;
            $data['completed_at'] = null;
        }

        if ($data['status'] === 'diproses') {
            $data['processed_at'] = $submission->processed_at ?: now();
            $data['completed_at'] = null;
        }

        if (in_array($data['status'], ['selesai', 'ditolak'], true)) {
            $data['processed_at'] = $submission->processed_at ?: now();
            $data['completed_at'] = $submission->completed_at ?: now();
        }

        if ($request->boolean('remove_result_file') && $submission->result_file) {
            Storage::disk('public')->delete($submission->result_file);
            $data['result_file'] = null;
        }

        if ($request->hasFile('result_file')) {
            if ($submission->result_file) {
                Storage::disk('public')->delete($submission->result_file);
            }

            $data['result_file'] = $request->file('result_file')->store('layanan-mandiri/hasil', 'public');
        }

        unset($data['remove_result_file']);

        $submission->update($data);

        return redirect()
            ->route('admin.layanan-mandiri.submissions.show', [$self_service->id, $submission->id])
            ->with('success', 'Status dan hasil layanan berhasil diperbarui.');
    }

    private function ensureSubmissionBelongsToService(SelfService $service, SelfServiceSubmission $submission): void
    {
        abort_unless($submission->self_service_id === $service->id, 404);
    }
}
