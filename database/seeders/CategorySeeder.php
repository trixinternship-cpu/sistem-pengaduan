<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_kategori' => 'Hardware', 'deskripsi' => 'Kendala perangkat keras seperti RAM, HDD, motherboard, dll'],
            ['nama_kategori' => 'Software', 'deskripsi' => 'Kendala perangkat lunak / aplikasi'],
            ['nama_kategori' => 'Jaringan', 'deskripsi' => 'Kendala internet, LAN, WiFi'],
            ['nama_kategori' => 'Printer', 'deskripsi' => 'Kendala printer dan scanner'],
            ['nama_kategori' => 'Sistem Operasi', 'deskripsi' => 'Kendala Windows, Linux, dll'],
            ['nama_kategori' => 'Aplikasi', 'deskripsi' => 'Kendala aplikasi kantor seperti Office, ERP, dll'],
            ['nama_kategori' => 'Lainnya', 'deskripsi' => 'Kendala lain yang tidak termasuk kategori di atas'],
        ];

        foreach ($data as $item) {
            Category::create($item);
        }
    }
}