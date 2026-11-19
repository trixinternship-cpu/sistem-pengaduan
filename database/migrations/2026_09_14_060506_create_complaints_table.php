<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengaduan')->unique();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pc_id')->constrained('pcs')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('jenis_kendala');
            $table->text('what');
            $table->string('who');
            $table->dateTime('when');
            $table->string('where');
            $table->text('why')->nullable();
            $table->text('how');
            $table->text('deskripsi');
            $table->enum('status', [
                'baru',
                'diproses',
                'menunggu_konfirmasi',
                'selesai',
                'ditolak',
            ])->default('baru');
            $table->foreignId('teknisi_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->foreignId('related_complaint_id')->nullable()
                  ->constrained('complaints')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};