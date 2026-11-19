<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $complaints = Complaint::archived()
            ->with(['user', 'pc', 'category', 'teknisi', 'handling', 'attachments', 'chat'])
            ->when($user->role === 'pelapor', fn ($query) => $query->where('user_id', $user->id))
            ->when($user->role !== 'it' && $user->role !== 'pelapor', fn () => abort(403))
            ->latest('archived_at')
            ->paginate(15);

        return view('archives.index', compact('complaints'));
    }

    public function restore(Request $request, int $id)
    {
        $user = $request->user();
        abort_unless(in_array($user->role, ['it', 'pelapor'], true), 403);

        $complaint = Complaint::withoutGlobalScope('notArchived')->findOrFail($id);

        if ($user->role === 'pelapor') {
            abort_unless((int) $complaint->user_id === (int) $user->id, 403);
        }

        abort_if(is_null($complaint->archived_at), 404);

        $complaint->forceFill([
            'archived_at' => null,
            // Beri jeda 7 hari lagi setelah dipulihkan tanpa mengubah tanggal selesai asli.
            'archive_after' => now()->addDays(7),
        ])->save();

        return redirect()->route('archives.index')
            ->with('success', 'Pengaduan berhasil dipulihkan dan kembali ke daftar aktif.');
    }
}
