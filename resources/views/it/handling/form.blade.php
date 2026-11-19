@extends('layouts.app')

@section('title', 'Form Penanganan')

@section('content')

{{-- ================= STYLE ================= --}}
<style>
    /* ============================================================
       FORM PENANGANAN — desain .pd-* (Konsisten)
    ============================================================ */
    @keyframes pdFadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes pdFloat {
        0%,100% { transform: translateY(0); }
        50%     { transform: translateY(-7px); }
    }
    @keyframes pdGradShift {
        0%   { background-position: 0% 50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* ---- Hero Banner ---- */
    .pd-hero {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        padding: 28px 30px;
        margin-bottom: 26px;
        border-radius: 20px;
        overflow: hidden;
        color: #fff;
        background: linear-gradient(120deg, #1e3a8a, #2856b6, #3b82f6, #2856b6);
        background-size: 300% 300%;
        animation: pdGradShift 14s ease infinite, pdFadeUp .6s ease both;
        box-shadow: 0 18px 40px -18px rgba(40,86,182,.55);
    }
    .pd-hero::before {
        content: '';
        position: absolute;
        width: 240px; height: 240px;
        top: -110px; right: -50px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.22), transparent 65%);
        animation: pdFloat 7s ease-in-out infinite;
        pointer-events: none;
    }
    .pd-hero::after {
        content: '';
        position: absolute;
        width: 160px; height: 160px;
        bottom: -90px; left: 20%;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.14), transparent 65%);
        animation: pdFloat 9s ease-in-out infinite reverse;
        pointer-events: none;
    }
    .pd-hero__text { position: relative; z-index: 2; }
    .pd-hero__eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: .5px;
        text-transform: uppercase;
        padding: 5px 12px;
        border-radius: 999px;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.22);
        margin-bottom: 12px;
    }
    .pd-hero h1 {
        font-size: 28px;
        font-weight: 800;
        letter-spacing: -.8px;
        margin: 0 0 6px;
        line-height: 1.15;
    }
    .pd-hero p {
        margin: 0;
        font-size: 14px;
        opacity: .88;
    }

    /* ---- Panel Form ---- */
    .pd-panel {
        border: 1px solid #e8edf4;
        border-radius: 18px;
        background: #fff;
        overflow: hidden;
        animation: pdFadeUp .6s cubic-bezier(.22,.8,.32,1) both;
        animation-delay: .15s;
        box-shadow: 0 4px 18px rgba(24,43,74,.04);
        margin-bottom: 1.5rem;
    }
    .pd-panel__head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 22px;
        border-bottom: 1px solid #edf1f6;
        background: linear-gradient(90deg, #fbfdff, #ffffff);
    }
    .pd-panel__title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 750;
        color: #1d2b42;
    }
    .pd-panel__title i {
        color: #2856b6;
        font-size: 18px;
    }

    .pd-form-body {
        padding: 24px;
    }

    /* Kartu Info Kendala */
    .pd-info-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 24px;
    }
    .pd-info-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #64748b;
        margin-bottom: 4px;
    }
    .pd-info-value {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
    }

    /* Mempercantik input form Bootstrap */
    .form-label {
        font-weight: 600;
        color: #1d2b42;
        font-size: 13px;
        margin-bottom: 6px;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 10px 14px;
        font-size: 13.5px;
        color: #1e293b;
        transition: border-color .18s ease, box-shadow .18s ease;
    }
    .form-control:focus, .form-select:focus {
        border-color: #2856b6;
        box-shadow: 0 0 0 3px rgba(40, 86, 182, 0.15);
    }

    /* Radio Button Custom */
    .form-check-input:checked {
        background-color: #2856b6;
        border-color: #2856b6;
    }
    .form-check-label {
        font-size: 14px;
        cursor: pointer;
    }

    /* Tombol Bawah */
    .pd-form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px;
        background: #fbfdff;
        border-top: 1px solid #edf1f6;
    }
    .pd-btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        background: #fff;
        color: #64748b;
        border: 1px solid #cbd5e1;
        transition: all .18s ease;
    }
    .pd-btn-secondary:hover {
        background: #f8fafc;
        color: #1e293b;
    }
    .pd-btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 650;
        border: none;
        background: #2856b6;
        color: #fff;
        transition: all .18s ease;
        box-shadow: 0 4px 10px -3px rgba(40,86,182,.4);
    }
    .pd-btn-primary:hover {
        background: #1e438f;
        transform: translateY(-1px);
        box-shadow: 0 6px 14px -3px rgba(40,86,182,.5);
    }

    @media (max-width: 767.98px) {
        .pd-hero { padding: 22px; }
        .pd-hero h1 { font-size: 22px; }
        .pd-form-body { padding: 18px; }
        .pd-form-actions { padding: 16px 18px; flex-direction: column; gap: 12px; }
        .pd-btn-secondary, .pd-btn-primary { width: 100%; justify-content: center; }
    }
</style>

{{-- ================= HERO BANNER ================= --}}
<div class="pd-hero">
    <div class="pd-hero__text">
        <span class="pd-hero__eyebrow">
            <i class="bi bi-tools"></i> Penanganan
        </span>
        <h1>{{ $complaint->handling ? 'Edit Penanganan' : 'Form Penanganan' }}</h1>
        <p>
            Pengaduan <strong>{{ $complaint->nomor_pengaduan }}</strong>
            — {{ $complaint->pc->kode_pc ?? '-' }}
        </p>
    </div>
</div>

{{-- PERBAIKAN: Tag form yang sebelumnya double/typo --}}
<form method="POST" action="{{ route('it.handling.store', $complaint->id) }}" class="form-with-loading">
    @csrf

    {{-- PANEL 1: INFO KENDALA & ANALISIS --}}
    <div class="pd-panel">
        <div class="pd-panel__head">
            <div class="pd-panel__title">
                <i class="bi bi-info-circle"></i> Info Kendala & Analisis
            </div>
        </div>

        <div class="pd-form-body">
            
            {{-- Kotak Info Kendala --}}
            <div class="pd-info-box">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="pd-info-label">Pelapor</div>
                        <div class="pd-info-value">{{ $complaint->user->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="pd-info-label">Kategori</div>
                        <div class="pd-info-value">{{ $complaint->category->nama_kategori ?? '-' }}</div>
                    </div>
                    <div class="col-12 mt-3">
                        <div class="pd-info-label">Kendala</div>
                        <div class="pd-info-value" style="font-weight: 500;">{{ $complaint->what }}</div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">
                        Analisis Penyebab <span class="text-danger">*</span>
                    </label>
                    <textarea name="analisis_penyebab" rows="3" class="form-control" required
                              placeholder="Contoh: Kabel LAN putus karena terjepit meja">{{ old('analisis_penyebab', $complaint->handling->analisis_penyebab ?? '') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">
                        Tindakan yang Dilakukan <span class="text-danger">*</span>
                    </label>
                    <textarea name="tindakan" rows="3" class="form-control" required
                              placeholder="Contoh: Memeriksa kabel LAN, mengganti dengan kabel baru">{{ old('tindakan', $complaint->handling->tindakan ?? '') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label">
                        Solusi <span class="text-danger">*</span>
                    </label>
                    <textarea name="solusi" rows="4" class="form-control" required
                              placeholder="Tulis solusi yang bisa dipakai ulang untuk kendala serupa">{{ old('solusi', $complaint->handling->solusi ?? '') }}</textarea>
                    <small class="text-muted mt-1 d-block">
                        Solusi ini akan disimpan sebagai referensi untuk kendala serupa di masa depan.
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- PANEL 2: HASIL PENANGANAN --}}
    <div class="pd-panel">
        <div class="pd-panel__head">
            <div class="pd-panel__title">
                <i class="bi bi-check2-square"></i> Hasil Penanganan
            </div>
        </div>

        <div class="pd-form-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">
                        Hasil <span class="text-danger">*</span>
                    </label>
                    <div class="d-flex gap-4 mt-1">
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

                <div class="col-12 mt-4">
                    <label class="form-label">Catatan Teknisi (opsional)</label>
                    <textarea name="catatan_teknisi" rows="2" class="form-control"
                              placeholder="Catatan tambahan...">{{ old('catatan_teknisi', $complaint->handling->catatan_teknisi ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="pd-form-actions">
            <a href="{{ route('it.complaints.show', $complaint->id) }}" class="pd-btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="pd-btn-primary">
                <i class="bi bi-save"></i> Simpan Penanganan
            </button>
        </div>
    </div>

</form>

@endsection