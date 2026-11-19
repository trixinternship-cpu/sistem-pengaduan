@extends('layouts.app')

@section('title', 'Form Penanganan')

@section('content')

<div class="mb-4">
    <h4 class="mb-1 fw-bold">
        {{ $complaint->handling ? 'Edit Penanganan' : 'Form Penanganan' }}
    </h4>
    <p class="text-muted mb-0">
        Pengaduan <strong>{{ $complaint->nomor_pengaduan }}</strong>
        — {{ $complaint->pc->kode_pc ?? '-' }}
    </p>
</div>

{{-- Info singkat kendala --}}
<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="text-muted small">Pelapor</div>
                <div class="fw-semibold">{{ $complaint->user->name ?? '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Kategori</div>
                <div class="fw-semibold">{{ $complaint->category->nama_kategori ?? '-' }}</div>
            </div>
            <div class="col-md-12 mt-2">
                <div class="text-muted small">Kendala</div>
                <div>{{ $complaint->what }}</div>
            </div>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('it.handling.store', $complaint->id) }}"><form method="POST" action="{{ route('it.handling.store', $complaint->id) }}" class="form-with-loading">    @csrf

    <div class="card mb-3">
        <div class="card-header bg-white"><strong>Analisis & Tindakan</strong></div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">
                    Analisis Penyebab <span class="text-danger">*</span>
                </label>
                <textarea name="analisis_penyebab" rows="3" class="form-control" required
                          placeholder="Contoh: Kabel LAN putus karena terjepit meja">{{ old('analisis_penyebab', $complaint->handling->analisis_penyebab ?? '') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Tindakan yang Dilakukan <span class="text-danger">*</span>
                </label>
                <textarea name="tindakan" rows="3" class="form-control" required
                          placeholder="Contoh: Memeriksa kabel LAN, mengganti dengan kabel baru">{{ old('tindakan', $complaint->handling->tindakan ?? '') }}</textarea>
            </div>

            <div class="mb-0">
                <label class="form-label">
                    Solusi <span class="text-danger">*</span>
                </label>
                <textarea name="solusi" rows="4" class="form-control" required
                          placeholder="Tulis solusi yang bisa dipakai ulang untuk kendala serupa">{{ old('solusi', $complaint->handling->solusi ?? '') }}</textarea>
                <small class="text-muted">
                    Solusi ini akan disimpan sebagai referensi untuk kendala serupa di masa depan.
                </small>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header bg-white"><strong>Hasil Penanganan</strong></div>
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">
                    Hasil <span class="text-danger">*</span>
                </label>
                <div class="d-flex gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="hasil_penanganan"
                               id="hasil_berhasil" value="berhasil"
                               {{ old('hasil_penanganan', $complaint->handling->hasil_penanganan ?? 'berhasil') === 'berhasil' ? 'checked' : '' }}>
                        <label class="form-check-label text-success fw-semibold" for="hasil_berhasil">
                            <i class="bi bi-check-circle"></i> Berhasil
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="hasil_penanganan"
                               id="hasil_gagal" value="tidak_berhasil"
                               {{ old('hasil_penanganan', $complaint->handling->hasil_penanganan ?? '') === 'tidak_berhasil' ? 'checked' : '' }}>
                        <label class="form-check-label text-danger fw-semibold" for="hasil_gagal">
                            <i class="bi bi-x-circle"></i> Tidak Berhasil
                        </label>
                    </div>
                </div>
            </div>

            <div class="mb-0">
                <label class="form-label">Catatan Teknisi (opsional)</label>
                <textarea name="catatan_teknisi" rows="2" class="form-control"
                          placeholder="Catatan tambahan...">{{ old('catatan_teknisi', $complaint->handling->catatan_teknisi ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('it.complaints.show', $complaint->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan Penanganan
        </button>
    </div>
</form>

@endsection