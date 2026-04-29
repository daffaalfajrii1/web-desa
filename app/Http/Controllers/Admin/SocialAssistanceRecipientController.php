<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hamlet;
use App\Models\SocialAssistanceProgram;
use App\Models\SocialAssistanceRecipient;
use Illuminate\Http\Request;

class SocialAssistanceRecipientController extends Controller
{
    public function index(Request $request)
    {
        $programId = $request->get('program_id');
        $status = $request->get('distribution_status');
        $search = $request->get('search');

        $programs = SocialAssistanceProgram::orderByDesc('year')->orderBy('name')->get();

        $items = SocialAssistanceRecipient::with(['program', 'hamlet'])
            ->when($programId, fn ($q) => $q->where('social_assistance_program_id', $programId))
            ->when($status, fn ($q) => $q->where('distribution_status', $status))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', '%' . $search . '%')
                        ->orWhere('nik', 'like', '%' . $search . '%')
                        ->orWhere('kk_number', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.infografis.bansos-recipient.index', compact('items', 'programs'));
    }

    public function create()
    {
        $programs = SocialAssistanceProgram::where('is_active', true)->orderByDesc('year')->orderBy('name')->get();
        $hamlets = Hamlet::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.infografis.bansos-recipient.create', compact('programs', 'hamlets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
    'social_assistance_program_id' => 'required|exists:social_assistance_programs,id',
    'hamlet_id' => 'nullable|exists:hamlets,id',
    'name' => 'required|string|max:255',
    'nik' => 'required|string|max:30',
    'kk_number' => 'nullable|string|max:30',
    'address' => 'nullable|string',
    'amount' => 'nullable|numeric|min:0',
    'benefit_type' => 'required|in:cash,goods,service,mixed',
    'item_description' => 'nullable|string',
    'unit' => 'nullable|string|max:50',
    'quantity' => 'nullable|numeric|min:0',
    'phone' => 'nullable|string|max:50',
    'verification_status' => 'required|in:pending,verified,rejected',
    'distribution_status' => 'required|in:pending,ready,distributed,rejected',
    'distributed_at' => 'nullable|date',
    'receiver_name' => 'nullable|string|max:255',
    'notes' => 'nullable|string',
]);
        SocialAssistanceRecipient::create($data);

        return redirect()->route('admin.bansos-recipient.index')->with('success', 'Penerima bansos berhasil ditambahkan.');
    }

    public function edit(SocialAssistanceRecipient $bansos_recipient)
    {
        $programs = SocialAssistanceProgram::orderByDesc('year')->orderBy('name')->get();
        $hamlets = Hamlet::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.infografis.bansos-recipient.edit', [
            'item' => $bansos_recipient,
            'programs' => $programs,
            'hamlets' => $hamlets,
        ]);
    }

    public function update(Request $request, SocialAssistanceRecipient $bansos_recipient)
    {
        $data = $request->validate([
            'social_assistance_program_id' => 'required|exists:social_assistance_programs,id',
            'hamlet_id' => 'nullable|exists:hamlets,id',
            'name' => 'required|string|max:255',
            'nik' => 'required|string|max:30',
            'kk_number' => 'nullable|string|max:30',
            'address' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'phone' => 'nullable|string|max:50',
            'verification_status' => 'required|in:pending,verified,rejected',
            'distribution_status' => 'required|in:pending,ready,distributed,rejected',
            'distributed_at' => 'nullable|date',
            'receiver_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $bansos_recipient->update($data);

        return redirect()->route('admin.bansos-recipient.index')->with('success', 'Penerima bansos berhasil diperbarui.');
    }

    public function destroy(SocialAssistanceRecipient $bansos_recipient)
    {
        $bansos_recipient->delete();

        return redirect()->route('admin.bansos-recipient.index')->with('success', 'Penerima bansos berhasil dihapus.');
    }
}