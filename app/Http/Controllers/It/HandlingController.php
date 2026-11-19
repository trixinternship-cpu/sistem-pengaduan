<?php

namespace App\Http\Controllers\It;

use App\Http\Controllers\Controller;
use App\Http\Requests\HandlingRequest;
use App\Models\Complaint;
use App\Models\ComplaintHandling;
use App\Notifications\ComplaintStatusNotification;

class HandlingController extends Controller
{
    public function create(Complaint $complaint)
    {
        abort_if($complaint->status === 'selesai', 403, 'Pengaduan sudah selesai.');

        $complaint->load(['pc', 'category', 'user', 'handling']);

        return view('it.handling.form', compact('complaint'));
    }

    public function store(HandlingRequest $request, Complaint $complaint)
    {
        abort_if($complaint->status === 'selesai', 403);

        $data = $request->validated();

        ComplaintHandling::updateOrCreate(
            ['complaint_id' => $complaint->id],
            [
                'teknisi_id' => auth()->id(),
                'analisis_penyebab' => $data['analisis_penyebab'],
                'tindakan' => $data['tindakan'],
                'solusi' => $data['solusi'],
                'hasil_penanganan' => $data['hasil_penanganan'],
                'catatan_teknisi' => $data['catatan_teknisi'] ?? null,
                'tanggal_penanganan' => now(),
            ]
        );

        $status = $data['hasil_penanganan'] === 'berhasil'
            ? 'menunggu_konfirmasi'
            : 'diproses';

        $complaint->update([
            'status' => $status,
            'teknisi_id' => auth()->id(),
        ]);

        // Notif ke pelapor
        if ($complaint->user) {
            $complaint->user->notify(new ComplaintStatusNotification(
                $complaint,
                'Pengaduan Ditangani',
                'Pengaduan ' . $complaint->nomor_pengaduan . ' sedang ditangani oleh tim IT',
                route('pelapor.complaints.show', $complaint->id)
            ));
        }

        return redirect()
            ->route('it.complaints.show', $complaint->id)
            ->with('success', 'Penanganan berhasil disimpan.');
    }
}