@extends('layouts.app')

@section('title', $pc->exists ? 'Edit PC' : 'Tambah PC')

@section('content')

{{-- ================= STYLE ================= --}}
<style>
    /* ============================================================
       FORM PC — desain .pd-* (Konsisten)
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
        margin-bottom: 2rem;
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

    /* Mempercantik input form Bootstrap bawaan */
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
        .pd-form-actions { padding: 16px 18px; }
    }
</style>

{{-- ================= HERO BANNER ================= --}}
<div class="pd-hero">
    <div class="pd-hero__text">
        <span class="pd-hero__eyebrow">
            <i class="bi bi-pc-display"></i> Master Data
        </span>
        <h1>{{ $pc->exists ? 'Edit PC' : 'Tambah PC Baru' }}</h1>
        <p>Isi data PC dengan lengkap dan benar.</p>
    </div>
</div>

{{-- ================= FORM ================= --}}
<form method="POST"
      action="{{ $pc->exists ? route('it.pcs.update', $pc->id) : route('it.pcs.store') }}">
    @csrf
    @if ($pc->exists)
        @method('PUT')
    @endif

    <div class="pd-panel">
        <div class="pd-panel__head">
            <div class="pd-panel__title">
                <i class="bi bi-pencil-square"></i> Formulir Perangkat
            </div>
        </div>

        <div class="pd-form-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kode PC <span class="text-danger">*</span></label>
                    <input type="text" name="kode_pc" class="form-control"
                           value="{{ old('kode_pc', $pc->kode_pc) }}"
                           placeholder="PC-001" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nama PC <span class="text-danger">*</span></label>
                    <input type="text" name="nama_pc" class="form-control"
                           value="{{ old('nama_pc', $pc->nama_pc) }}"
                           placeholder="PC Finance 01" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nomor Aset</label>
                    <input type="text" name="nomor_aset" class="form-control"
                           value="{{ old('nomor_aset', $pc->nomor_aset) }}"
                           placeholder="AST-001">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Departemen <span class="text-danger">*</span></label>
                    <input type="text" name="departemen" class="form-control"
                           value="{{ old('departemen', $pc->departemen) }}"
                           placeholder="Finance" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                    <input type="text" name="lokasi" class="form-control"
                           value="{{ old('lokasi', $pc->lokasi) }}"
                           placeholder="Office Lantai 1" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Pengguna</label>
                    <select name="user_id" class="form-select">
                        <option value="">-- Tidak ada --</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}"
                                {{ old('user_id', $pc->user_id) == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Status <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach (['aktif' => 'Aktif', 'rusak' => 'Rusak', 'maintenance' => 'Maintenance'] as $val => $label)
                            <option value="{{ $val }}"
                                {{ old('status', $pc->status) === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 mt-4">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="form-control"
                              placeholder="Catatan tambahan...">{{ old('keterangan', $pc->keterangan) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Tombol Aksi --}}
        <div class="pd-form-actions">
            <a href="{{ route('it.pcs.index') }}" class="pd-btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="pd-btn-primary">
                <i class="bi bi-save"></i> Simpan Data
            </button>
        </div>
    </div>
</form>

@endsection