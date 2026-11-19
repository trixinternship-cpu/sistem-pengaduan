<?php

namespace Database\Seeders;

use App\Models\Pc;
use App\Models\User;
use Illuminate\Database\Seeder;

class PcSeeder extends Seeder
{
    public function run(): void
    {
        $pelapor1 = User::where('email', 'pelapor@example.com')->first();
        $pelapor2 = User::where('email', 'pelapor2@example.com')->first();

        $data = [
            [
                'kode_pc' => 'PC-001',
                'nama_pc' => 'PC Finance 01',
                'nomor_aset' => 'AST-001',
                'departemen' => 'Finance',
                'lokasi' => 'Office Lantai 1',
                'user_id' => $pelapor1?->id,
                'status' => 'aktif',
                'keterangan' => 'PC operasional finance',
            ],
            [
                'kode_pc' => 'PC-002',
                'nama_pc' => 'PC HRD 01',
                'nomor_aset' => 'AST-002',
                'departemen' => 'HRD',
                'lokasi' => 'Office Lantai 2',
                'user_id' => $pelapor2?->id,
                'status' => 'aktif',
                'keterangan' => 'PC operasional HRD',
            ],
            [
                'kode_pc' => 'PC-003',
                'nama_pc' => 'PC IT 01',
                'nomor_aset' => 'AST-003',
                'departemen' => 'IT',
                'lokasi' => 'Office Lantai 3',
                'user_id' => null,
                'status' => 'aktif',
                'keterangan' => 'PC tim IT',
            ],
        ];

        foreach ($data as $item) {
            Pc::create($item);
        }
    }
}