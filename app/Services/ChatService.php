<?php

namespace App\Services;

use App\Models\Chat;
use App\Models\Complaint;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ChatService
{
    public function __construct(private AiClassifierService $ai)
    {
    }

    public function ensureChatForComplaint(Complaint $complaint): Chat
    {
        return Chat::firstOrCreate(
            ['complaint_id' => $complaint->id],
            [
                'pelapor_id' => $complaint->user_id,
                'teknisi_id' => $complaint->teknisi_id,
            ]
        );
    }

    public function sendMessage(Chat $chat, $user, string $message): Message
    {
        $senderType = $user->role === 'it' ? 'it' : 'pelapor';

        $msg = Message::create([
            'chat_id' => $chat->id,
            'user_id' => $user->id,
            'sender_type' => $senderType,
            'message' => $message,
        ]);

        $chat->update([
            'last_message_at' => now(),
        ]);

        return $msg;
    }

    public function aiReply(Chat $chat, string $userMessage): ?Message
    {
        try {
            $pelapor = User::find($chat->pelapor_id);

            if (!$pelapor) {
                return null;
            }

            /*
            |--------------------------------------------------------------------------
            | CEK KUOTA AI
            |--------------------------------------------------------------------------
            */

            if (!$pelapor->canUseAiChat()) {
                $info = Message::create([
                    'chat_id' => $chat->id,
                    'user_id' => null,
                    'sender_type' => 'ai',
                    'message' =>
                        "Maaf, kuota balasan AI Anda hari ini sudah habis (50/50).\n\n" .
                        "Pesan Anda akan langsung ditangani oleh tim IT. Mohon tunggu ya.\n\n" .
                        "_Kuota akan direset besok._",
                ]);

                $chat->update([
                    'last_message_at' => now(),
                ]);

                return $info;
            }

            /*
            |--------------------------------------------------------------------------
            | AMBIL PENGADUAN
            |--------------------------------------------------------------------------
            */

            $complaint = $chat->complaint;

            if (!$complaint) {
                Log::warning('AI chat: complaint tidak ditemukan', [
                    'chat_id' => $chat->id,
                ]);

                return null;
            }

            $complaint->loadMissing([
                'category',
                'pc',
                'user',
                'teknisi',
                'handling',
                'relatedComplaint',
            ]);

            $category = $complaint->category->nama_kategori ?? 'Lainnya';

            /*
            |--------------------------------------------------------------------------
            | INFORMASI PC
            |--------------------------------------------------------------------------
            |
            | Dibuat aman supaya tidak error apabila nama kolom PC berbeda.
            |
            */

            $pcInfo = 'Tidak diketahui';

            if ($complaint->pc) {
                $pc = $complaint->pc;

                $possiblePcFields = [
                    'kode_pc',
                    'nama_pc',
                    'nomor_pc',
                    'kode',
                    'nama',
                    'hostname',
                    'serial_number',
                ];

                foreach ($possiblePcFields as $field) {
                    if (
                        isset($pc->{$field}) &&
                        !empty($pc->{$field})
                    ) {
                        $pcInfo = $pc->{$field};
                        break;
                    }
                }

                // Jika tidak menemukan field khusus, gunakan ID PC
                if ($pcInfo === 'Tidak diketahui') {
                    $pcInfo = 'PC ID: ' . $pc->id;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | INFORMASI PENGADUAN
            |--------------------------------------------------------------------------
            */

            $complaintContext = [
                'nomor_pengaduan' => $complaint->nomor_pengaduan ?? '-',

                'pc' => $pcInfo,

                'jenis_kendala' => $complaint->jenis_kendala ?? '-',

                'kategori' => $category,

                'what' => $complaint->what ?? '-',

                'who' => $complaint->who ?? '-',

                'when' => $complaint->when
                    ? $complaint->when
                        ->timezone(config('app.timezone'))
                        ->format('d-m-Y H:i')
                    : '-',

                'where' => $complaint->where ?? '-',

                'why' => $complaint->why ?? '-',

                'how' => $complaint->how ?? '-',

                'deskripsi' => $complaint->deskripsi ?? '-',

                'status' => $complaint->status_label
                    ?? $complaint->status
                    ?? '-',

                'is_recurring' => $complaint->is_recurring
                    ? 'Ya'
                    : 'Tidak',

                'related_complaint_id' =>
                    $complaint->related_complaint_id ?? null,
            ];

            /*
            |--------------------------------------------------------------------------
            | RIWAYAT CHAT
            |--------------------------------------------------------------------------
            |
            | Ambil 15 pesan terakhir agar AI memahami percakapan.
            |
            */

            $history = $chat->messages()
                ->orderByDesc('created_at')
                ->take(15)
                ->get()
                ->reverse()
                ->map(function ($m) {
                    return [
                        'role' => $m->sender_type === 'ai'
                            ? 'assistant'
                            : 'user',

                        'content' => $m->message,
                    ];
                })
                ->values()
                ->toArray();

            /*
            |--------------------------------------------------------------------------
            | JIKA PENGADUAN TERKAIT DENGAN PENGADUAN SEBELUMNYA
            |--------------------------------------------------------------------------
            */

            if ($complaint->relatedComplaint) {
                $related = $complaint->relatedComplaint;

                $complaintContext['pengaduan_sebelumnya'] = [
                    'nomor' => $related->nomor_pengaduan ?? '-',
                    'jenis_kendala' => $related->jenis_kendala ?? '-',
                    'deskripsi' => $related->deskripsi ?? '-',
                    'status' => $related->status ?? '-',
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | PANGGIL AI
            |--------------------------------------------------------------------------
            */

            $reply = $this->ai->chatWithContext(
                $userMessage,
                $category,
                $history,
                $complaintContext
            );

            if (!$reply) {
                return null;
            }

            /*
            |--------------------------------------------------------------------------
            | KUOTA
            |--------------------------------------------------------------------------
            */

            $remaining = $pelapor->remainingAiChat() - 1;

            if ($remaining > 0) {
                $reply .=
                    "\n\n_Sisa kuota AI Anda hari ini: {$remaining}x balasan._";
            } else {
                $reply .=
                    "\n\n_Ini balasan AI terakhir Anda hari ini. Besok kuota Anda direset kembali._";
            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN BALASAN AI
            |--------------------------------------------------------------------------
            */

            $msg = Message::create([
                'chat_id' => $chat->id,
                'user_id' => null,
                'sender_type' => 'ai',
                'message' => $reply,
            ]);

            $chat->update([
                'last_message_at' => now(),
            ]);

            $pelapor->incrementAiChat();

            return $msg;

        } catch (\Throwable $e) {

            Log::warning(
                'AI chat reply gagal: ' . $e->getMessage(),
                [
                    'chat_id' => $chat->id,
                    'user_message' => $userMessage,
                ]
            );

            return null;
        }
    }

    public function markAsRead(Chat $chat, $userId): void
    {
        $chat->messages()
            ->whereNull('read_at')
            ->where('user_id', '!=', $userId)
            ->update([
                'read_at' => now(),
            ]);
    }
}