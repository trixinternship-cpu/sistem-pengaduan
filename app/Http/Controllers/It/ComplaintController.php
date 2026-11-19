<?php

namespace App\Http\Controllers\It;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Notifications\ComplaintStatusNotification;
use App\Services\RecurringComplaintService;

class ComplaintController extends Controller
{
    public function __construct(private RecurringComplaintService $recurring)
    {
    }

    public function index()
    {
        $status = request('status');

        $complaints = Complaint::with(['pc', 'category', 'user', 'teknisi'])
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15);

        return view('it.complaints.index', compact('complaints', 'status'));
    }

    public function show(Complaint $complaint)
    {
        $complaint->load(['pc', 'category', 'user', 'teknisi', 'handling.teknisi', 'attachments']);

        $similar = $this->recurring->findSimilar(
            $complaint->pc_id,
            $complaint->category_id,
            $complaint->what . ' ' . $complaint->how . ' ' . $complaint->deskripsi
        )->reject(fn ($item) => $item['complaint']->id === $complaint->id);

        $riwayatPc = Complaint::with(['category', 'handling'])
            ->where('pc_id', $complaint->pc_id)
            ->where('id', '!=', $complaint->id)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('it.complaints.show', compact('complaint', 'similar', 'riwayatPc'));
    }

    public function updateStatus(Complaint $complaint)
    {
        $data = request()->validate([
            'status' => ['required', 'in:baru,diproses,menunggu_konfirmasi,selesai,ditolak'],
        ]);

        $complaint->update([
            'status' => $data['status'],
            'teknisi_id' => auth()->id(),
            'tanggal_selesai' => $data['status'] === 'selesai' ? now() : $complaint->tanggal_selesai,
        ]);

        // Notif ke pelapor
        if ($complaint->user) {
            $statusLabel = match ($data['status']) {
                'baru' => 'Baru',
                'diproses' => 'Sedang Diproses',
                'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                'selesai' => 'Selesai',
                'ditolak' => 'Ditolak',
                default => $data['status'],
            };

            $complaint->user->notify(new ComplaintStatusNotification(
                $complaint,
                'Status Pengaduan Diubah',
                'Pengaduan ' . $complaint->nomor_pengaduan . ' sekarang berstatus: ' . $statusLabel,
                route('pelapor.complaints.show', $complaint->id)
            ));
        }

        return back()->with('success', 'Status pengaduan diperbarui.');
    }

    public function markRecurring(Complaint $complaint)
    {
        $data = request()->validate([
            'related_complaint_id' => ['required', 'exists:complaints,id'],
        ]);

        $complaint->update([
            'is_recurring' => true,
            'related_complaint_id' => $data['related_complaint_id'],
        ]);

        return back()->with('success', 'Pengaduan ditandai sebagai kendala berulang.');
    }
}