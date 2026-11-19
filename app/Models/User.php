<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const AI_CHAT_LIMIT = 50;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'phone',
        'ai_chat_count',
        'ai_chat_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ai_chat_date' => 'date',
        ];
    }

    public function isPelapor(): bool
    {
        return $this->role === 'pelapor';
    }

    public function isIt(): bool
    {
        return $this->role === 'it';
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'user_id');
    }

    public function handledComplaints()
    {
        return $this->hasMany(Complaint::class, 'teknisi_id');
    }

    public function handlings()
    {
        return $this->hasMany(ComplaintHandling::class, 'teknisi_id');
    }

    public function pcs()
    {
        return $this->hasMany(Pc::class, 'user_id');
    }

    public function ensureAiChatReset(): void
    {
        $today = now()->toDateString();
        $lastDate = $this->ai_chat_date ? $this->ai_chat_date->toDateString() : null;

        if ($lastDate !== $today) {
            $this->ai_chat_count = 0;
            $this->ai_chat_date = $today;
            $this->save();
        }
    }

    public function canUseAiChat(): bool
    {
        $this->ensureAiChatReset();
        return $this->ai_chat_count < self::AI_CHAT_LIMIT;
    }

    public function remainingAiChat(): int
    {
        $this->ensureAiChatReset();
        return max(0, self::AI_CHAT_LIMIT - $this->ai_chat_count);
    }

    public function incrementAiChat(): void
    {
        $this->ensureAiChatReset();
        $this->ai_chat_count = $this->ai_chat_count + 1;
        $this->save();
    }

    public function resetAiChat(): void
    {
        $this->ai_chat_count = 0;
        $this->ai_chat_date = now()->toDateString();
        $this->save();
    }
}