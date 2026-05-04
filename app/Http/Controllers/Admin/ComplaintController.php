<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $statuses = Complaint::statuses();

        $summary = [
            'masuk' => Complaint::where('status', 'masuk')->count(),
            'diproses' => Complaint::where('status', 'diproses')->count(),
            'selesai' => Complaint::where('status', 'selesai')->count(),
            'ditolak' => Complaint::where('status', 'ditolak')->count(),
        ];

        $items = Complaint::query()
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->date_from, fn ($q) => $q->whereDate('submitted_at', '>=', $request->date_from))
            ->when($request->date_to, fn ($q) => $q->whereDate('submitted_at', '<=', $request->date_to))
            ->when($request->search, function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('complaint_code', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%');
                });
            })
            ->orderByDesc('submitted_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.pengaduan.index', compact('items', 'statuses', 'summary'));
    }

    public function show(Complaint $pengaduan)
    {
        return view('admin.pengaduan.show', ['item' => $pengaduan]);
    }

    public function edit(Complaint $pengaduan)
    {
        $statuses = Complaint::statuses();

        return view('admin.pengaduan.edit', [
            'item' => $pengaduan,
            'statuses' => $statuses,
        ]);
    }

    public function update(Request $request, Complaint $pengaduan)
    {
        $data = $request->validate([
            'status' => 'required|in:masuk,diproses,selesai,ditolak',
            'admin_note' => 'nullable|string',
        ]);

        if (in_array($data['status'], ['selesai', 'ditolak'], true)) {
            $data['resolved_at'] = $pengaduan->resolved_at ?: now();
        } else {
            $data['resolved_at'] = null;
        }

        $pengaduan->update($data);

        return redirect()
            ->route('admin.pengaduan.show', $pengaduan->id)
            ->with('success', 'Status pengaduan berhasil diperbarui.');
    }
}
