@extends('layouts.app')

@section('title', 'Detail Pengaduan IT')

@section('content')

{{-- ================= STYLE ================= --}}
<style>
    /* ============================================================
       DETAIL PENGADUAN IT — desain .pd-* (Konsisten)
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
    @keyframes pdRing {
        0%   { box-shadow: 0 0 0 0 rgba(40,86,182,.35); }
        80%  { box-shadow: 0 0 0 14px rgba(40,86,182,0); }
        100% { box-shadow: 0 0 0 0 rgba(40,86,182,0); }
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
    .pd-hero__actions {
        position: relative;
        z-index: 2;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .pd-hero__btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .pd-hero__btn--primary {
        background: #fff;
        color: #1e3a8a;
        box-shadow: 0 4px 10px rgba(0,0,0,.15);
    }
    .pd-hero__btn--primary:hover {
        transform: translateY(-2px);
        color: #1e3a8a;
        box-shadow: 0 8px 16px rgba(0,0,0,.25);
    }
    .pd-hero__btn--ghost {
        background: rgba(255,255,255,.14);
        color: #fff;
        border: 1px solid rgba(255,255,255,.3);
    }
    .pd-hero__btn--ghost:hover {
        background: rgba(255,255,255,.24);
        color: #fff;
        transform: translateY(-2px);
    }

    /* ---- Panel / Card ---- */
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
        padding: 16px 22px;
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

    /* ---- Tabel 5W+1H ---- */
    .pd-table-detail { width: 100%; border-collapse: collapse; }
    .pd-table-detail th {
        text-align: left;
        padding: 12px 20px;
        font-size: 12px;
        font-weight: 700;
        color: #64748b;
        width: 30%;
        background: #f8fafc;
        border-bottom: 1px solid #edf1f6;
        vertical-align: top;
    }
    .pd-table-detail td {
        padding: 12px 20px;
        font-size: 13.5px;
        color: #1e293b;
        border-bottom: 1px solid #edf1f6;
        vertical-align: top;
    }
    .pd-table-detail tr:last-child th,
    .pd-table-detail tr:last-child td {
        border-bottom: none;
    }

    /* ---- Status Badge ---- */
    .pd-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }
    .pd-status::before {
        content: '';
        width: 6px; height: 6px;
        border-radius: 50%;
        background: currentColor;
        animation: pdRing 2s ease-out infinite;
    }
    .pd-status--info    { background: #e6efff; color: #1d4ed8; }
    .pd-status--warning { background: #fff4e0; color: #b45309; }
    .pd-status--success { background: #e3f8ef; color: #047857; }
    .pd-status--secondary { background: #eef1f6; color: #475569; }
    .pd-status--danger  { background: #ffe9e9; color: #b91c1c; }

    /* ---- List Group (Riwayat PC) ---- */
    .pd-list-group { padding: 0; margin: 0; list-style: none; }
    .pd-list-group li {
        padding: 16px 22px;
        border-bottom: 1px solid #edf1f6;
        transition: background .18s ease;
    }
    .pd-list-group li:last-child { border-bottom: none; }
    .pd-list-group li:hover { background: #f6faff; }

    /* ---- Alert Recurring ---- */
    .pd-alert {
        border: none;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        animation: pdFadeUp .6s ease both;
        animation-delay: .1s;
    }
    .pd-alert--danger {
        background: #fff1f2;
        color: #be123c;
        border: 1px solid #fecdd3;
    }
    .pd-alert--danger i { font-size: 20px; margin-top: 2px; }

    /* ---- Form Ubah Status ---- */
    .form-select-sm {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
    }
    .form-select-sm:focus {
        border-color: #2856b6;
        box-shadow: 0 0 0 3px rgba(40,86,182,.15);
    }

    /* ---- Lampiran & Similar ---- */
    .pd-attachment-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px;
        border-radius: 10px;
        background: #fff;
        border: 1px solid #cbd5e1;
        color: #1e293b;
        font-size: 12.5px;
        font-weight: 600;
        text-decoration: none;
        transition: all .18s ease;
    }
    .pd-attachment-btn:hover {
        background: #f6faff;
        border-color: #c8d6ef;
        color: #2856b6;
    }

    .pd-similar-box {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        background: #f8fafc;
    }

    @media (max-width: 767.98px) {
        .pd-hero { padding: 22px; }
        .pd-hero h1 { font-size: 22px; }
        .pd-hero__actions { width: 100%; margin-top: 12px; }
        .pd-hero__btn { flex: 1; justify-content: center; }
        .pd-table-detail th, .pd-table-detail td { padding: 12px 16px; }
    }
</style>

{{-- ================= HERO BANNER ================= --}}
<div class="pd-hero">
    <div class="pd-hero__text">
        <span class="pd-hero__eyebrow">
            <i class="bi bi-ticket-detailed"></i> Detail Pengaduan
        </span>
        <h1>{{ $complaint->nomor_pengaduan }}</h1>
        <p>Dibuat {{ $complaint->created_at->format('d M Y H:i') }} oleh <strong>{{ $complaint->user->name ?? '-' }}</strong></p>
    </div>
    <div class="pd-hero__actions">
        <a href="{{ route('chat.show', $complaint->id) }}" class="pd-hero__btn pd-hero__btn--ghost">
            <i class="bi bi-chat-dots"></i> Chat
        </a>
        @if ($complaint->status !== 'selesai')
            <a href="{{ route('it.handling.create', $complaint->id) }}" class="pd-hero__btn pd-hero__btn--primary">
                <i class="bi bi-tools"></i> {{ $complaint->handling ? 'Edit Penanganan' : 'Tangani' }}
            </a>
        @endif
        <a href="{{ route('it.complaints.index') }}" class="pd-hero__btn pd-hero__btn--ghost">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

{{-- ================= ALERT BERULANG ================= --}}
@if ($complaint->is_recurring)
    <div class="pd-alert pd-alert--danger">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>Kendala berulang.</strong><br>
            Pelapor melaporkan kendala yang mirip dengan riwayat sebelumnya.
        </div>
    </div>
@endif

<div class="row g-3">
    {{-- KOLOM KIRI (Lebar 8) --}}
    <div class="col-12 col-md-8">
        
        {{-- Status Card --}}
        <div class="pd-panel">
            <div class="pd-panel__head">
                <div class="pd-panel__title">
                    <i class="bi bi-info-circle"></i> Status & Tindakan
                </div>
            </div>
            <div class="p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <div class="text-muted small fw-bold mb-1">STATUS SAAT INI</div>
                    <span class="pd-status pd-status--{{ $complaint->status_badge }} fs-6">
                        {{ $complaint->status_label }}
                    </span>
                </div>
                <div class="text-end">
                    <div class="text-muted small fw-bold mb-1">TEKNISI</div>
                    <div class="fw-semibold" style="color:#1d2b42;">{{ $complaint->teknisi->name ?? '-' }}</div>
                </div>
            </div>
            
            <div class="px-4 pb-4">
                <form method="POST" action="{{ route('it.complaints.update-status', $complaint->id) }}" class="d-flex gap-2 flex-wrap">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select form-select-sm" style="max-width: 220px;">
                        @foreach (['baru','diproses','menunggu_konfirmasi','selesai','ditolak'] as $s)
                            <option value="{{ $s }}" {{ $complaint->status === $s ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $s)) }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-primary fw-semibold" style="border-radius:8px;">
                        <i class="bi bi-arrow-repeat"></i> Ubah Status
                    </button>
                </form>
            </div>
        </div>

        {{-- Info 5W+1H --}}
        <div class="pd-panel">
            <div class="pd-panel__head">
                <div class="pd-panel__title">
                    <i class="bi bi-card-text"></i> Informasi Kendala (5W+1H)
                </div>
            </div>
            <table class="pd-table-detail">
                <tr><th>What</th><td>{{ $complaint->what }}</td></tr>
                <tr><th>Who</th><td>{{ $complaint->who }}</td></tr>
                <tr><th>When</th><td>{{ $complaint->when?->format('d M Y H:i') }}</td></tr>
                <tr><th>Where</th><td>{{ $complaint->where }}</td></tr>
                <tr><th>Why</th><td>{{ $complaint->why ?: '-' }}</td></tr>
                <tr><th>How</th><td>{{ $complaint->how }}</td></tr>
                <tr><th>Deskripsi</th><td>{{ $complaint->deskripsi }}</td></tr>
            </table>
        </div>

        {{-- Penanganan --}}
        @if ($complaint->handling)
            <div class="pd-panel">
                <div class="pd-panel__head">
                    <div class="pd-panel__title">
                        <i class="bi bi-tools"></i> Penanganan
                    </div>
                    @if ($complaint->status !== 'selesai')
                        <a href="{{ route('it.handling.create', $complaint->id) }}"
                           class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                    @endif
                </div>
                <table class="pd-table-detail">
                    <tr><th>Analisis Penyebab</th><td>{{ $complaint->handling->analisis_penyebab }}</td></tr>
                    <tr><th>Tindakan</th><td>{{ $complaint->handling->tindakan }}</td></tr>
                    <tr><th>Solusi</th><td>{{ $complaint->handling->solusi }}</td></tr>
                    <tr>
                        <th>Hasil</th>
                        <td>
                            @if ($complaint->handling->hasil_penanganan === 'berhasil')
                                <span class="badge bg-success px-3 py-2">Berhasil</span>
                            @else
                                <span class="badge bg-danger px-3 py-2">Tidak Berhasil</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th>Ditangani oleh</th><td>{{ $complaint->handling->teknisi->name ?? '-' }}</td></tr>
                    <tr><th>Tanggal</th><td>{{ $complaint->handling->tanggal_penanganan?->format('d M Y H:i') }}</td></tr>
                    @if ($complaint->handling->catatan_teknisi)
                        <tr><th>Catatan</th><td>{{ $complaint->handling->catatan_teknisi }}</td></tr>
                    @endif
                    <tr>
                        <th>Dipakai ulang</th>
                        <td>
                            <span class="badge bg-secondary px-3 py-2">{{ $complaint->handling->digunakan_kembali }} kali</span>
                            <span class="badge bg-success px-3 py-2">{{ $complaint->handling->berhasil_digunakan_kembali }} berhasil</span>
                        </td>
                    </tr>
                </table>
            </div>
        @endif

        {{-- Lampiran --}}
        @if ($complaint->attachments->isNotEmpty())
            <div class="pd-panel">
                <div class="pd-panel__head">
                    <div class="pd-panel__title">
                        <i class="bi bi-paperclip"></i> Lampiran
                    </div>
                </div>
                <div class="p-4">
                    <div class="row g-2">
                        @foreach ($complaint->attachments as $att)
                            <div class="col-12 col-md-4">
                                <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" class="pd-attachment-btn">
                                    <i class="bi bi-paperclip"></i> {{ Str::limit($att->file_name, 20) }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Kendala Serupa --}}
        @if ($similar->isNotEmpty())
            <div class="pd-panel" style="border-color: #fcd34d;">
                <div class="pd-panel__head" style="background: #fffbeb; border-bottom-color: #fef08a;">
                    <div class="pd-panel__title" style="color: #b45309;">
                        <i class="bi bi-exclamation-triangle" style="color: #d97706;"></i> Kendala Serupa Pernah Ditangani
                    </div>
                </div>
                <div class="p-4">
                    @foreach ($similar as $item)
                        @php $c = $item['complaint']; @endphp
                        <div class="pd-similar-box">
                            <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
                                <div>
                                    <span class="badge bg-secondary">{{ $c->nomor_pengaduan }}</span>
                                    <span class="text-muted small ms-2">
                                        {{ $c->tanggal_selesai?->format('d M Y') ?? '-' }}
                                    </span>
                                </div>
                                <span class="badge bg-primary">Skor: {{ $item['score'] }}</span>
                            </div>
                            <div class="small">
                                <div class="mb-1"><strong>Kendala:</strong> {{ $c->what }}</div>
                                <div class="mb-1"><strong>Penyebab:</strong> {{ $c->handling->analisis_penyebab ?? '-' }}</div>
                                <div class="mt-2 p-3 bg-white rounded border border-light">
                                    <strong>Solusi:</strong> {{ $c->handling->solusi ?? '-' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- KOLOM KANAN (Lebar 4) --}}
    <div class="col-12 col-md-4">
        
        {{-- Info Perangkat --}}
        <div class="pd-panel">
            <div class="pd-panel__head">
                <div class="pd-panel__title">
                    <i class="bi bi-pc-display"></i> Info Perangkat
                </div>
                <a href="{{ route('it.pcs.history', $complaint->pc_id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;">
                    Riwayat
                </a>
            </div>
            <table class="pd-table-detail">
                <tr><th>Kode</th><td class="fw-bold" style="color:#244b9b;">{{ $complaint->pc->kode_pc ?? '-' }}</td></tr>
                <tr><th>Nama</th><td>{{ $complaint->pc->nama_pc ?? '-' }}</td></tr>
                <tr><th>Departemen</th><td>{{ $complaint->pc->departemen ?? '-' }}</td></tr>
                <tr><th>Lokasi</th><td>{{ $complaint->pc->lokasi ?? '-' }}</td></tr>
                <tr><th>Kategori</th><td>{{ $complaint->category->nama_kategori ?? '-' }}</td></tr>
            </table>
        </div>

        {{-- Riwayat Perangkat --}}
        <div class="pd-panel">
            <div class="pd-panel__head">
                <div class="pd-panel__title">
                    <i class="bi bi-clock-history"></i> Riwayat Perangkat
                </div>
            </div>
            @if ($riwayatPc->isEmpty())
                <div class="text-muted text-center py-4 small">
                    Belum ada riwayat lain.
                </div>
            @else
                <ul class="pd-list-group">
                    @foreach ($riwayatPc as $r)
                        <li>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold" style="color:#1d2b42;">{{ $r->jenis_kendala }}</div>
                                    <div class="text-muted small mt-1">{{ $r->created_at->format('d M Y') }}</div>
                                </div>
                                <span class="pd-status pd-status--{{ $r->status_badge }}" style="font-size:10px; padding:4px 8px;">
                                    {{ $r->status_label }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>

@endsection