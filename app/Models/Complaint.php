<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_pengaduan',
        'user_id',
        'pc_id',
        'category_id',
        'jenis_kendala',
        'what',
        'who',
        'when',
        'where',
        'why',
        'how',
        'deskripsi',
        'status',
        'teknisi_id',
        'tanggal_selesai',
        'is_recurring',
        'related_complaint_id',
    ];

    protected $casts = [
        'when' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'is_recurring' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    public function pc()
    {
        return $this->belongsTo(Pc::class, 'pc_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function handling()
    {
        return $this->hasOne(ComplaintHandling::class);
    }

    public function attachments()
    {
        return $this->hasMany(ComplaintAttachment::class);
    }

    public function relatedComplaint()
    {
        return $this->belongsTo(Complaint::class, 'related_complaint_id');
    }

    // Relationship baru untuk chat
    public function chat()
    {
        return $this->hasOne(Chat::class);
    }

    public function scopeSolved($query)
    {
        return $query->where('status', 'selesai')
                     ->whereHas('handling', function ($q) {
                         $q->where('hasil_penanganan', 'berhasil');
                     });
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'baru' => 'secondary',
            'diproses' => 'warning',
            'menunggu_konfirmasi' => 'info',
            'selesai' => 'success',
            'ditolak' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'baru' => 'Baru',
            'diproses' => 'Diproses',
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            default => '-',
        };
    }
}