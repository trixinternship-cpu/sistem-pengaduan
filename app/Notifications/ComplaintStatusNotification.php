<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ComplaintStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Complaint $complaint,
        public string $title,
        public string $message,
        public string $url = ''
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'complaint_id' => $this->complaint->id,
            'nomor_pengaduan' => $this->complaint->nomor_pengaduan,
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
        ];
    }
}