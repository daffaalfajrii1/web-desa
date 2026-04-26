<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpidRequest;
use Illuminate\Http\Request;

class PpidRequestController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $items = PpidRequest::with('handler')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('institution', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%')
                      ->orWhere('request_content', 'like', '%' . $search . '%');
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.ppid-request.index', compact('items'));
    }

    public function show(PpidRequest $ppid_request)
    {
        $ppid_request->load('handler');

        return view('admin.ppid-request.show', ['item' => $ppid_request]);
    }

    public function edit(PpidRequest $ppid_request)
    {
        return view('admin.ppid-request.edit', ['item' => $ppid_request]);
    }

    public function update(Request $request, PpidRequest $ppid_request)
    {
        $data = $request->validate([
            'status' => 'required|in:new,processed,completed,rejected',
            'admin_note' => 'nullable|string',
        ]);

        $data['handled_by'] = auth()->id();

        if (in_array($data['status'], ['processed', 'completed', 'rejected'])) {
            $data['responded_at'] = now();
        }

        $ppid_request->update($data);

        return redirect()->route('admin.ppid-request.index')
            ->with('success', 'Permohonan informasi berhasil diperbarui.');
    }

    public function destroy(PpidRequest $ppid_request)
    {
        $ppid_request->delete();

        return redirect()->route('admin.ppid-request.index')
            ->with('success', 'Permohonan informasi berhasil dihapus.');
    }
}