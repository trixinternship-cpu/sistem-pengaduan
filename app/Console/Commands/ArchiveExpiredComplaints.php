<?php

namespace App\Console\Commands;

use App\Models\Complaint;
use Illuminate\Console\Command;

class ArchiveExpiredComplaints extends Command
{
    protected $signature = 'complaints:archive-expired';
    protected $description = 'Arsipkan pengaduan selesai yang sudah ditangani lebih dari 7 hari.';

    public function handle(): int
    {
        $count = Complaint::withoutGlobalScope('notArchived')
            ->whereNull('archived_at')
            ->where('status', 'selesai')
            ->whereNotNull('tanggal_selesai')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereNotNull('archive_after')->where('archive_after', '<=', now());
                })->orWhere(function ($q) {
                    $q->whereNull('archive_after')->where('tanggal_selesai', '<=', now()->subDays(7));
                });
            })
            ->whereHas('handling', function ($query) {
                $query->where('hasil_penanganan', 'berhasil');
            })
            ->update(['archived_at' => now()]);

        $this->info("{$count} pengaduan berhasil diarsipkan.");

        return self::SUCCESS;
    }
}
