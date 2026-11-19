@extends('layouts.app')

@section('title', 'Riwayat Pengaduan')

@section('content')

{{-- ================= STYLE ================= --}}
<style>
    /* ============================================================
       RIWAYAT PENGADUAN — desain .pd-* (Sesuai Permintaan)
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
    @keyframes pdPulseBadge {
        0%,100% { transform: scale(1); }
        50%     { transform: scale(1.06); }
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
    .pd-hero__btn {
        position: relative;
        z-index: 2;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 18px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        background: #fff;
        color: #1e3a8a;
        box-shadow: 0 8px 20px -6px rgba(0,0,0,.3);
        transition: transform .2s ease, box-shadow .2s ease;
    }
    .pd-hero__btn:hover {
        color: #1e3a8a;
        transform: translateY(-3px);
        box-shadow: 0 14px 26px -8px rgba(0,0,0,.4);
    }

    /* ---- Panel ---- */
    .pd-panel {
        border: 1px solid #e8edf4;
        border-radius: 18px;
        background: #fff;
        overflow: hidden;
        animation: pdFadeUp .6s cubic-bezier(.22,.8,.32,1) both;
        animation-delay: .15s;
        box-shadow: 0 4px 18px rgba(24,43,74,.04);
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
    .pd-panel__count {
        font-size: 12px;
        font-weight: 650;
        color: #2856b6;
        background: #eff4fd;
        padding: 5px 12px;
        border-radius: 999px;
    }

    /* ---- Tabel ---- */
    .pd-table { width: 100%; border-collapse: collapse; min-width: 700px; }
    .pd-table thead th {
        text-align: left;
        padding: 12px 18px;
        font-size: 10.5px;
        font-weight: 750;
        letter-spacing: .6px;
        text-transform: uppercase;
        color: #8a97a8;
        background: #f7f9fc;
        border-bottom: 1px solid #e8edf4;
        white-space: nowrap;
    }
    .pd-table tbody td {
        padding: 15px 18px;
        font-size: 13px;
        color: #35445b;
        border-bottom: 1px solid #eef2f7;
        vertical-align: middle;
    }
    .pd-table tbody tr {
        animation: pdFadeUp .45s ease both;
        transition: background .18s ease, transform .18s ease;
    }
    .pd-table tbody tr:nth-child(1) { animation-delay: .2s; }
    .pd-table tbody tr:nth-child(2) { animation-delay: .27s; }
    .pd-table tbody tr:nth-child(3) { animation-delay: .34s; }
    .pd-table tbody tr:nth-child(4) { animation-delay: .41s; }
    .pd-table tbody tr:nth-child(5) { animation-delay: .48s; }
    .pd-table tbody tr:nth-child(6) { animation-delay: .55s; }
    .pd-table tbody tr:nth-child(7) { animation-delay: .62s; }
    .pd-table tbody tr:nth-child(8) { animation-delay: .69s; }
    .pd-table tbody tr:last-child td { border-bottom: 0; }
    .pd-table tbody tr:hover {
        background: #f6faff;
        transform: translateX(4px);
    }
    .pd-nomor {
        font-weight: 700;
        color: #244b9b;
        white-space: nowrap;
    }
    .pd-badge-recurring {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-top: 5px;
        padding: 4px 9px;
        border-radius: 7px;
        font-size: 10px;
        font-weight: 700;
        background: #ffe9e9;
        color: #b91c1c;
        animation: pdPulseBadge 2s ease-in-out infinite;
    }
    .pd-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 11px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        transition: transform .18s ease;
    }
    .pd-table tr:hover .pd-status { transform: scale(1.07); }
    .pd-status::before {
        content: '';
        width: 6px; height: 6px;
        border-radius: 50%;
        background: currentColor;
        animation: pdRing 2s ease-out infinite;
    }
    .pd-status--info      { background: #e6efff; color: #1d4ed8; }
    .pd-status--warning   { background: #fff4e0; color: #b45309; }
    .pd-status--success   { background: #e3f8ef; color: #047857; }
    .pd-status--secondary { background: #eef1f6; color: #475569; }
    .pd-status--danger    { background: #ffe9e9; color: #b91c1c; }

    .pd-detail-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        font-size: 11.5px;
        font-weight: 650;
        border-radius: 8px;
        border: 1px solid #c8d6ef;
        color: #2856b6;
        text-decoration: none;
        transition: all .18s ease;
    }
    .pd-detail-btn:hover {
        background: #2856b6;
        color: #fff;
        border-color: #2856b6;
        transform: translateY(-1px);
        box-shadow: 0 6px 14px -4px rgba(40,86,182,.4);
    }

    /* ---- Empty State ---- */
    .pd-empty {
        padding: 60px 20px;
        text-align: center;
        animation: pdFadeUp .5s ease both;
        animation-delay: .15s;
    }
    .pd-empty__icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 88px; height: 88px;
        border-radius: 50%;
        background: linear-gradient(135deg, #e8f0ff, #f6faff);
        color: #9db4d9;
        font-size: 38px;
        margin-bottom: 18px;
        animation: pdFloat 3.4s ease-in-out infinite, pdRing 2.6s ease-out infinite;
    }
    .pd-empty h6 { font-weight: 750; color: #1d2b42; margin-bottom: 6px; }
    .pd-empty p { color: #718096; font-size: 13px; margin-bottom: 16px; }
    .pd-empty__btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 20px;
        border-radius: 10px;
        background: #2856b6;
        color: #fff;
        font-size: 13px;
        font-weight: 650;
        text-decoration: none;
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .pd-empty__btn:hover {
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 12px 22px -8px rgba(40,86,182,.5);
    }

    /* ============================================================
       FIX PAGINATION (Menebalkan panah dengan trik CSS)
    ============================================================ */
    .pd-pagination {
        padding: 16px 20px;
        border-top: 1px solid #edf1f6;
        background: #fbfdff;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
    }
    
    /* Sembunyikan teks bawaan Laravel */
    .pd-pagination p.text-sm.text-gray-700,
    .pd-pagination .text-sm.text-gray-700,
    .pd-pagination .small.text-muted {
        display: none !important; 
    }
    
    .pd-pagination .pagination {
        margin: 0;
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .pd-pagination .page-item {
        display: inline-flex;
    }
    .pd-pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
        padding: 0;
        border: 1px solid #dce4ef;
        border-radius: 8px !important;
        color: #2856b6;
        font-size: 13px;
        font-weight: 600;
        background: #fff;
        transition: all .18s ease;
        line-height: 1;
    }

    /* 
      KUNCI PANAH TEBAL:
      Kita sembunyikan SVG tipis bawaan Laravel, 
      lalu kita ganti background-nya dengan ikon SVG solid (tebal).
      Ikon di bawah ini menggunakan encoded SVG chevron solid.
    */
    
    /* Sembunyikan SVG asli */
    .pd-pagination .page-link svg {
        display: none !important;
    }

    /* Ganti panah Previous (Kiri) */
    .pd-pagination .page-item:first-child .page-link {
        font-size: 0; /* Hilangkan teks "Previous" */
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232856b6'%3E%3Cpath d='M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat;
        background-position: center;
        background-size: 18px; /* Ukuran panah */
    }
    /* Ganti panah Next (Kanan) */
    .pd-pagination .page-item:last-child .page-link {
        font-size: 0; /* Hilangkan teks "Next" */
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232856b6'%3E%3Cpath d='M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat;
        background-position: center;
        background-size: 18px; /* Ukuran panah */
    }

    /* Warna panah saat di-hover */
    .pd-pagination .page-item:first-child .page-link:hover {
        background-color: #eff4fd;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231e438f'%3E%3Cpath d='M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z'/%3E%3C/svg%3E") !important;
    }
    .pd-pagination .page-item:last-child .page-link:hover {
        background-color: #eff4fd;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231e438f'%3E%3Cpath d='M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z'/%3E%3C/svg%3E") !important;
    }

    /* Warna panah saat disabled (tidak bisa diklik) */
    .pd-pagination .page-item.disabled:first-child .page-link {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23a0aec0'%3E%3Cpath d='M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z'/%3E%3C/svg%3E") !important;
        background-color: #f8fafc;
    }
    .pd-pagination .page-item.disabled:last-child .page-link {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23a0aec0'%3E%3Cpath d='M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z'/%3E%3C/svg%3E") !important;
        background-color: #f8fafc;
    }

    .pd-pagination .page-item.active .page-link {
        background: #2856b6;
        border-color: #2856b6;
        color: #fff;
        box-shadow: 0 4px 10px -3px rgba(40,86,182,.5);
    }

    /* ---- Responsif ---- */
    @media (max-width: 767.98px) {
        .pd-hero { padding: 22px; }
        .pd-hero h1 { font-size: 22px; }
        .pd-hero__btn { width: 100%; justify-content: center; }
        .pd-panel__head { flex-direction: column; align-items: flex-start; gap: 10px; }
        .pd-pagination { align-items: center; }
        .pd-pagination .pagination { flex-wrap: wrap; justify-content: center; }
    }

    @media (prefers-reduced-motion: reduce) {
        .pd-hero, .pd-hero::before, .pd-hero::after, .pd-panel,
        .pd-table tbody tr, .pd-empty, .pd-empty__icon,
        .pd-status::before, .pd-badge-recurring {
            animation: none !important;
            transition: none !important;
        }
    }
</style>

{{-- ================= HERO BANNER ================= --}}
<div class="pd-hero">
    <div class="pd-hero__text">
        <span class="pd-hero__eyebrow">
            <i class="bi bi-clock-history"></i> Riwayat
        </span>
        <h1>Riwayat Pengaduan</h1>
        <p>Semua pengaduan yang pernah Anda buat.</p>
    </div>
    <a href="{{ route('pelapor.complaints.create') }}" class="pd-hero__btn">
        <i class="bi bi-plus-circle"></i> Buat Pengaduan
    </a>
</div>

{{-- ================= PANEL ================= --}}
<div class="pd-panel">
    <div class="pd-panel__head">
        <div class="pd-panel__title">
            <i class="bi bi-list-ul"></i> Daftar Pengaduan
        </div>
        @if (!$complaints->isEmpty())
            <span class="pd-panel__count">
                Total: {{ $complaints->total() }} pengaduan
            </span>
        @endif
    </div>

    @if ($complaints->isEmpty())
        <div class="pd-empty">
            <div class="pd-empty__icon"><i class="bi bi-inbox"></i></div>
            <h6>Belum Ada Pengaduan</h6>
            <p>Mulai laporkan kendala Anda.</p>
            <a href="{{ route('pelapor.complaints.create') }}" class="pd-empty__btn">
                <i class="bi bi-plus-circle"></i> Buat Pengaduan
            </a>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table class="pd-table">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th class="d-none d-md-table-cell">PC</th>
                        <th class="d-none d-lg-table-cell">Kategori</th>
                        <th class="d-none d-lg-table-cell">Jenis</th>
                        <th>Status</th>
                        <th class="d-none d-lg-table-cell">Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($complaints as $c)
                        <tr>
                            <td>
                                <div class="pd-nomor">{{ $c->nomor_pengaduan }}</div>
                                @if ($c->is_recurring)
                                    <span class="pd-badge-recurring" title="Kendala berulang">
                                        <i class="bi bi-arrow-repeat"></i> Berulang
                                    </span>
                                @endif
                            </td>
                            <td class="d-none d-md-table-cell">
                                <div class="fw-semibold">{{ $c->pc->kode_pc ?? '-' }}</div>
                                <div class="text-muted small">{{ $c->pc->nama_pc ?? '' }}</div>
                            </td>
                            <td class="d-none d-lg-table-cell">{{ $c->category->nama_kategori ?? '-' }}</td>
                            <td class="d-none d-lg-table-cell">{{ $c->jenis_kendala }}</td>
                            <td>
                                <span class="pd-status pd-status--{{ $c->status_badge }}">
                                    {{ $c->status_label }}
                                </span>
                            </td>
                            <td class="text-muted small d-none d-lg-table-cell">
                                {{ $c->created_at->format('d M Y H:i') }}
                            </td>
                            <td style="text-align:right;">
                                <a href="{{ route('pelapor.complaints.show', $c->id) }}" class="pd-detail-btn">
                                    Detail <i class="bi bi-arrow-right-short"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pd-pagination">
            {{-- Paksa gunakan paginator bawaan tapi dengan gaya kita --}}
            {{ $complaints->onEachSide(1)->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection