<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplaintHandling extends Model
{
    use HasFactory;

    protected $fillable = [
        'complaint_id',
        'teknisi_id',
        'analisis_penyebab',
        'tindakan',
        'solusi',
        'hasil_penanganan',
        'catatan_teknisi',
        'tanggal_penanganan',
        'digunakan_kembali',
        'berhasil_digunakan_kembali',
    ];

    protected $casts = [
        'tanggal_penanganan' => 'datetime',
    ];

    public function complaint()
    {
        return $this->belongsTo(Complaint::class);
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }
}