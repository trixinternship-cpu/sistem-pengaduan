@extends('layouts.app')

@section('title', 'Dashboard Pelapor')

@section('content')

{{-- ================= STYLE ================= --}}
<style>
    /* ============================================================
       DASHBOARD PELAPOR — desain modern, prefix .pd- agar aman
       dari tabrakan dengan style global layout.
    ============================================================ */

    @keyframes pdFadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes pdPopIn {
        0%   { opacity: 0; transform: scale(.9) translateY(10px); }
        70%  { transform: scale(1.02) translateY(0); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
    }
    @keyframes pdFloat {
        0%,100% { transform: translateY(0); }
        50%     { transform: translateY(-7px); }
    }
    @keyframes pdShine {
        0%   { transform: translateX(-120%) skewX(-20deg); }
        100% { transform: translateX(220%) skewX(-20deg); }
    }
    @keyframes pdRing {
        0%   { box-shadow: 0 0 0 0 rgba(40,86,182,.35); }
        80%  { box-shadow: 0 0 0 14px rgba(40,86,182,0); }
        100% { box-shadow: 0 0 0 0 rgba(40,86,182,0); }
    }
    @keyframes pdGradShift {
        0%   { background-position: 0% 50%; }
        50%  { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    @keyframes pdCountUp {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ---- Hero Banner ---- */
    .pd-hero {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        padding: 30px 32px;
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
        width: 260px; height: 260px;
        top: -120px; right: -60px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,.22), transparent 65%);
        animation: pdFloat 7s ease-in-out infinite;
        pointer-events: none;
    }
    .pd-hero::after {
        content: '';
        position: absolute;
        width: 180px; height: 180px;
        bottom: -100px; left: 22%;
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
        font-size: 30px;
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
        gap: 12px;
        flex-wrap: wrap;
    }
    .pd-hero__btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 12px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
    }
    .pd-hero__btn--primary {
        background: #fff;
        color: #1e3a8a;
        box-shadow: 0 8px 20px -6px rgba(0,0,0,.3);
    }
    .pd-hero__btn--primary:hover {
        transform: translateY(-3px);
        color: #1e3a8a;
        box-shadow: 0 14px 26px -8px rgba(0,0,0,.4);
    }
    .pd-hero__btn--ghost {
        background: rgba(255,255,255,.14);
        color: #fff;
        border: 1px solid rgba(255,255,255,.3);
    }
    .pd-hero__btn--ghost:hover {
        background: rgba(255,255,255,.24);
        color: #fff;
        transform: translateY(-3px);
    }

    /* ---- Kartu Statistik ---- */
    .pd-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 26px;
    }
    .pd-stat {
        position: relative;
        padding: 22px 20px;
        border-radius: 18px;
        background: #fff;
        border: 1px solid #e8edf4;
        overflow: hidden;
        animation: pdPopIn .55s cubic-bezier(.22,.8,.32,1) both;
        transition: transform .28s cubic-bezier(.22,.8,.32,1), box-shadow .28s ease;
    }
    .pd-stat:nth-child(1) { animation-delay: .08s; }
    .pd-stat:nth-child(2) { animation-delay: .18s; }
    .pd-stat:nth-child(3) { animation-delay: .28s; }
    .pd-stat:nth-child(4) { animation-delay: .38s; }

    .pd-stat::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 4px;
        background: var(--pd-accent);
    }
    .pd-stat::after {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 40%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,.65), transparent);
        transform: translateX(-120%) skewX(-20deg);
        pointer-events: none;
    }
    .pd-stat:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 36px -18px rgba(24,43,74,.35);
    }
    .pd-stat:hover::after { animation: pdShine 1s ease; }

    .pd-stat__icon {
        width: 46px; height: 46px;
        border-radius: 13px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        color: #fff;
        background: var(--pd-accent);
        margin-bottom: 14px;
        transition: transform .28s ease;
    }
    .pd-stat:hover .pd-stat__icon { transform: rotate(-8deg) scale(1.1); }

    .pd-stat__value {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -1px;
        line-height: 1;
        color: #1d2b42;
        font-variant-numeric: tabular-nums;
        animation: pdCountUp .6s ease both;
        animation-delay: .45s;
    }
    .pd-stat__label {
        margin-top: 6px;
        font-size: 12.5px;
        font-weight: 600;
        color: #718096;
    }

    .pd-stat--total    { --pd-accent: #2856b6; }
    .pd-stat--baru     { --pd-accent: #f59e0b; }
    .pd-stat--diproses { --pd-accent: #8b5cf6; }
    .pd-stat--selesai  { --pd-accent: #10b981; }

    /* ---- Panel Pengaduan Terbaru ---- */
    .pd-panel {
        border: 1px solid #e8edf4;
        border-radius: 18px;
        background: #fff;
        overflow: hidden;
        animation: pdFadeUp .6s cubic-bezier(.22,.8,.32,1) both;
        animation-delay: .5s;
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
    .pd-panel__link {
        font-size: 12.5px;
        font-weight: 650;
        color: #2856b6;
        text-decoration: none;
        padding: 7px 13px;
        border-radius: 9px;
        background: #eff4fd;
        transition: background .18s ease, transform .18s ease;
    }
    .pd-panel__link:hover {
        background: #e0eafc;
        color: #1e438f;
        transform: translateX(3px);
    }

    /* ---- Tabel ---- */
    .pd-table { width: 100%; border-collapse: collapse; min-width: 640px; }
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
    .pd-table tbody tr:nth-child(1) { animation-delay: .58s; }
    .pd-table tbody tr:nth-child(2) { animation-delay: .66s; }
    .pd-table tbody tr:nth-child(3) { animation-delay: .74s; }
    .pd-table tbody tr:nth-child(4) { animation-delay: .82s; }
    .pd-table tbody tr:nth-child(5) { animation-delay: .90s; }
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
    .pd-status--info    { background: #e6efff; color: #1d4ed8; }
    .pd-status--warning { background: #fff4e0; color: #b45309; }
    .pd-status--success { background: #e3f8ef; color: #047857; }
    .pd-status--secondary { background: #eef1f6; color: #475569; }
    .pd-status--danger  { background: #ffe9e9; color: #b91c1c; }

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
        animation-delay: .5s;
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

    /* ---- Responsif ---- */
    @media (max-width: 991px) {
        .pd-stats { grid-template-columns: repeat(2, 1fr); }
        .pd-hero { padding: 24px; }
        .pd-hero h1 { font-size: 24px; }
    }
    @media (max-width: 575px) {
        .pd-stats { gap: 12px; }
        .pd-stat { padding: 16px 15px; }
        .pd-stat__value { font-size: 26px; }
        .pd-stat__icon { width: 40px; height: 40px; font-size: 18px; }
        .pd-hero { flex-direction: column; align-items: stretch; padding: 22px; }
        .pd-hero__actions { width: 100%; }
        .pd-hero__btn { flex: 1; justify-content: center; }
    }

    @media (prefers-reduced-motion: reduce) {
        .pd-hero, .pd-hero::before, .pd-hero::after, .pd-stat, .pd-stat::after,
        .pd-stat__value, .pd-panel, .pd-table tbody tr, .pd-empty,
        .pd-empty__icon, .pd-status::before, .pd-stat__icon, .pd-hero__btn,
        .pd-detail-btn, .pd-panel__link, .pd-empty__btn {
            animation: none !important;
            transition: none !important;
        }
    }
</style>

{{-- ================= HERO BANNER ================= --}}
<div class="pd-hero">
    <div class="pd-hero__text">
        <span class="pd-hero__eyebrow">
            <i class="bi bi-speedometer2"></i> Dashboard
        </span>
        <h1>Halo, {{ auth()->user()->name }} 👋</h1>
        <p>Pantau ringkasan dan status pengaduan Anda di sini.</p>
    </div>
    <div class="pd-hero__actions">
        <a href="{{ route('pelapor.complaints.create') }}" class="pd-hero__btn pd-hero__btn--primary">
            <i class="bi bi-plus-circle"></i> Buat Pengaduan
        </a>
        <a href="{{ route('pelapor.complaints.index') }}" class="pd-hero__btn pd-hero__btn--ghost">
            <i class="bi bi-list-ul"></i> Semua Laporan
        </a>
    </div>
</div>

{{-- ================= KARTU STATISTIK ================= --}}
<div class="pd-stats">
    <div class="pd-stat pd-stat--total">
        <div class="pd-stat__icon"><i class="bi bi-file-earmark-text"></i></div>
        <div class="pd-stat__value">{{ $stats['total'] }}</div>
        <div class="pd-stat__label">Total Laporan</div>
    </div>
    <div class="pd-stat pd-stat--baru">
        <div class="pd-stat__icon"><i class="bi bi-hourglass-split"></i></div>
        <div class="pd-stat__value">{{ $stats['baru'] }}</div>
        <div class="pd-stat__label">Baru</div>
    </div>
    <div class="pd-stat pd-stat--diproses">
        <div class="pd-stat__icon"><i class="bi bi-tools"></i></div>
        <div class="pd-stat__value">{{ $stats['diproses'] }}</div>
        <div class="pd-stat__label">Diproses</div>
    </div>
    <div class="pd-stat pd-stat--selesai">
        <div class="pd-stat__icon"><i class="bi bi-check-circle"></i></div>
        <div class="pd-stat__value">{{ $stats['selesai'] }}</div>
        <div class="pd-stat__label">Selesai</div>
    </div>
</div>

{{-- ================= PANEL PENGADUAN TERBARU ================= --}}
<div class="pd-panel">
    <div class="pd-panel__head">
        <div class="pd-panel__title">
            <i class="bi bi-clock-history"></i> Pengaduan Terbaru
        </div>
        <a href="{{ route('pelapor.complaints.index') }}" class="pd-panel__link">
            Lihat Semua <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    @if ($recent->isEmpty())
        <div class="pd-empty">
            <div class="pd-empty__icon"><i class="bi bi-inbox"></i></div>
            <h6>Belum Ada Pengaduan</h6>
            <p>Anda belum membuat pengaduan apapun.</p>
            <a href="{{ route('pelapor.complaints.create') }}" class="pd-empty__btn">
                <i class="bi bi-plus-circle"></i> Buat Pengaduan Pertama
            </a>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table class="pd-table">
                <thead>
                    <tr>
                        <th>Nomor</th>
                        <th>PC</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recent as $c)
                        <tr>
                            <td><span class="pd-nomor">{{ $c->nomor_pengaduan }}</span></td>
                            <td>{{ $c->pc->kode_pc ?? '-' }}</td>
                            <td>{{ $c->category->nama_kategori ?? '-' }}</td>
                            <td>
                                <span class="pd-status pd-status--{{ $c->status_badge }}">
                                    {{ $c->status_label }}
                                </span>
                            </td>
                            <td style="color:#718096;font-size:12px;">
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
    @endif
</div>

@endsection