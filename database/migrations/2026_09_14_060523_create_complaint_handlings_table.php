<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaint_handlings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complaint_id')->unique()
                  ->constrained('complaints')->cascadeOnDelete();
            $table->foreignId('teknisi_id')->constrained('users')->cascadeOnDelete();
            $table->text('analisis_penyebab');
            $table->text('tindakan');
            $table->text('solusi');
            $table->enum('hasil_penanganan', ['berhasil', 'tidak_berhasil'])->default('berhasil');
            $table->text('catatan_teknisi')->nullable();
            $table->dateTime('tanggal_penanganan');
            $table->integer('digunakan_kembali')->default(0);
            $table->integer('berhasil_digunakan_kembali')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaint_handlings');
    }
};