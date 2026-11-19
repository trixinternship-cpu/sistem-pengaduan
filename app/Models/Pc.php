<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pc extends Model
{
    use HasFactory;

    protected $table = 'pcs';

    protected $fillable = [
        'kode_pc',
        'nama_pc',
        'nomor_aset',
        'departemen',
        'lokasi',
        'user_id',
        'status',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'pc_id');
    }
}