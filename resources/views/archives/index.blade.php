@extends('layouts.app')

@section('title', 'Arsip Pengaduan')

@section('content')

{{-- ================= LIBRARY SWEETALERT2 ================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ================= STYLE ================= --}}
<style>
    /* ============================================================
       ARSIP PENGADUAN — desain .pd-* (Konsisten)
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
    
    /* Tombol Hero (Kembali) */
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
    .pd-panel__count {
        font-size: 12px;
        font-weight: 650;
        color: #2856b6;
        background: #eff4fd;
        padding: 5px 12px;
        border-radius: 999px;
    }

    /* ---- List Arsip ---- */
    .pd-archive-item {
        padding: 24px;
        border-bottom: 1px solid #edf1f6;
        transition: background .18s ease;
    }
    .pd-archive-item:last-child { border-bottom: none; }
    .pd-archive-item:hover { background: #f8fafc; }

    .pd-archive-number {
        font-weight: 800;
        color: #244b9b;
        font-size: 15px;
        text-decoration: none;
    }
    .pd-archive-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        background: #e3f8ef;
        color: #047857;
    }
    .pd-archive-date {
        font-size: 12px;
        color: #64748b;
        font-weight: 500;
    }
    .pd-archive-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 4px;
    }
    .pd-archive-desc {
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
        margin-bottom: 12px;
    }

    /* Meta Info (Ikon & Teks) */
    .pd-archive-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        font-size: 12px;
        color: #64748b;
        margin-bottom: 16px;
    }
    .pd-archive-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Kotak Solusi */
    .pd-archive-solution {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
    }
    .pd-archive-solution-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #64748b;
        margin-bottom: 6px;
    }
    .pd-archive-solution-text {
        font-size: 13px;
        color: #334155;
        line-height: 1.6;
    }

    /* Tombol Pulihkan */
    .pd-btn-restore {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        border: none;
        background: #2856b6;
        color: #fff;
        transition: all .18s ease;
        box-shadow: 0 4px 10px -3px rgba(40,86,182,.4);
        text-decoration: none;
    }
    .pd-btn-restore:hover {
        background: #1e438f;
        transform: translateY(-1px);
        box-shadow: 0 6px 14px -3px rgba(40,86,182,.5);
        color: #fff;
    }

    /* ---- Empty State ---- */
    .pd-empty {
        padding: 60px 20px;
        text-align: center;
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
        animation: pdFloat 3.4s ease-in-out infinite;
    }
    .pd-empty h6 { font-weight: 750; color: #1d2b42; margin-bottom: 6px; font-size: 16px; }
    .pd-empty p { color: #718096; font-size: 13px; margin: 0; }

    /* ---- Alert Info ---- */
    .pd-alert-info {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1e3a8a;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 13.5px;
        line-height: 1.5;
    }
    .pd-alert-info i { font-size: 20px; color: #3b82f6; margin-top: 2px; }

    /* ============================================================
       PAGINATION KUSTOM
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
    .pd-pagination p.text-sm.text-gray-700,
    .pd-pagination .text-sm.text-gray-700,
    .pd-pagination .small.text-muted { display: none !important; }
    
    .pd-pagination .pagination {
        margin: 0; display: flex; gap: 6px; align-items: center;
    }
    .pd-pagination .page-item { display: inline-flex; }
    .pd-pagination .page-link {
        display: flex; align-items: center; justify-content: center;
        min-width: 34px; height: 34px; padding: 0;
        border: 1px solid #dce4ef; border-radius: 8px !important;
        color: #2856b6; font-size: 13px; font-weight: 600; background: #fff;
        transition: all .18s ease; line-height: 1;
    }
    .pd-pagination .page-link svg { display: none !important; }

    /* Panah Kiri */
    .pd-pagination .page-item:first-child .page-link {
        font-size: 0; 
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232856b6'%3E%3Cpath d='M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat; background-position: center; background-size: 18px;
    }
    /* Panah Kanan */
    .pd-pagination .page-item:last-child .page-link {
        font-size: 0;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%232856b6'%3E%3Cpath d='M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat; background-position: center; background-size: 18px;
    }

    /* Hover */
    .pd-pagination .page-item:first-child .page-link:hover {
        background-color: #eff4fd;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231e438f'%3E%3Cpath d='M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z'/%3E%3C/svg%3E") !important;
    }
    .pd-pagination .page-item:last-child .page-link:hover {
        background-color: #eff4fd;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%231e438f'%3E%3Cpath d='M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z'/%3E%3C/svg%3E") !important;
    }

    /* Disabled */
    .pd-pagination .page-item.disabled:first-child .page-link {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23a0aec0'%3E%3Cpath d='M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z'/%3E%3C/svg%3E") !important;
        background-color: #f8fafc;
    }
    .pd-pagination .page-item.disabled:last-child .page-link {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23a0aec0'%3E%3Cpath d='M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z'/%3E%3C/svg%3E") !important;
        background-color: #f8fafc;
    }

    .pd-pagination .page-item.active .page-link {
        background: #2856b6; border-color: #2856b6; color: #fff;
        box-shadow: 0 4px 10px -3px rgba(40,86,182,.5);
    }

    /* ---- Responsif ---- */
    @media (max-width: 767.98px) {
        .pd-hero { padding: 22px; }
        .pd-hero h1 { font-size: 22px; }
        .pd-hero__btn { width: 100%; justify-content: center; margin-top: 10px; }
        .pd-archive-item { padding: 18px; }
        .pd-pagination { align-items: center; }
        .pd-pagination .pagination { flex-wrap: wrap; justify-content: center; }
    }

    @media (prefers-reduced-motion: reduce) {
        .pd-hero, .pd-hero::before, .pd-hero::after, .pd-panel,
        .pd-archive-item, .pd-empty, .pd-empty__icon {
            animation: none !important;
            transition: none !important;
        }
    }
</style>

{{-- ================= HERO BANNER ================= --}}
<div class="pd-hero">
    <div class="pd-hero__text">
        <span class="pd-hero__eyebrow">
            <i class="bi bi-archive"></i> Penyimpanan
        </span>
        <h1>Arsip Pengaduan</h1>
        <p>Pengaduan selesai dan berhasil ditangani yang telah melewati 7 hari.</p>
    </div>
    <a href="{{ auth()->user()->role === 'it' ? route('it.complaints.index') : route('pelapor.complaints.index') }}" class="pd-hero__btn">
        <i class="bi bi-arrow-left"></i> Kembali ke Pengaduan
    </a>
</div>

{{-- ================= ALERT INFO ================= --}}
<div class="pd-alert-info">
    <i class="bi bi-info-circle-fill"></i>
    <div>
        Arsip tidak menghapus data. Riwayat penanganan, lampiran, dan chat tetap disimpan. Pengaduan dapat dipulihkan oleh IT atau pelapor pemilik pengaduan.
    </div>
</div>

{{-- ================= PANEL DAFTAR ARSIP ================= --}}
<div class="pd-panel">
    
    <div class="pd-panel__head">
        <div class="pd-panel__title">
            <i class="bi bi-archive-fill"></i> Daftar Arsip
        </div>
        @if (!$complaints->isEmpty())
            <span class="pd-panel__count">
                Total: {{ $complaints->total() }} pengaduan
            </span>
        @endif
    </div>

    @if ($complaints->isEmpty())
        <div class="pd-empty">
            <div class="pd-empty__icon"><i class="bi bi-archive"></i></div>
            <h6>Belum Ada Arsip</h6>
            <p>Pengaduan yang memenuhi aturan arsip akan muncul di sini secara otomatis.</p>
        </div>
    @else
        <div class="pd-archive-list">
            @foreach ($complaints as $complaint)
                <div class="pd-archive-item">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                        
                        <div class="flex-grow-1">
                            {{-- Header (Nomor, Badge, Tanggal) --}}
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <a href="{{ auth()->user()->role === 'it' ? route('it.complaints.show', $complaint->id) : route('pelapor.complaints.show', $complaint->id) }}" class="pd-archive-number">
                                    {{ $complaint->nomor_pengaduan }}
                                </a>
                                <span class="pd-archive-badge">
                                    <i class="bi bi-check-circle-fill"></i> Selesai
                                </span>
                                <span class="pd-archive-date">
                                    Diarsipkan {{ $complaint->archived_at?->timezone(config('app.timezone'))->format('d M Y, H:i') }}
                                </span>
                            </div>

                            {{-- Judul & Deskripsi --}}
                            <div class="pd-archive-title">
                                {{ $complaint->what ?: $complaint->jenis_kendala ?: 'Pengaduan' }}
                            </div>
                            <div class="pd-archive-desc">
                                {{ \Illuminate\Support\Str::limit($complaint->deskripsi, 220) }}
                            </div>

                            {{-- Meta Info (Ikon & Teks) --}}
                            <div class="pd-archive-meta">
                                @if (auth()->user()->role === 'it')
                                    <span><i class="bi bi-person"></i>{{ $complaint->user->name ?? '-' }}</span>
                                @endif
                                <span><i class="bi bi-pc-display"></i>{{ $complaint->pc->kode_pc ?? '-' }}</span>
                                <span><i class="bi bi-tag"></i>{{ $complaint->category->nama_kategori ?? '-' }}</span>
                                <span><i class="bi bi-calendar-check"></i>Selesai {{ $complaint->tanggal_selesai?->format('d M Y') ?? '-' }}</span>
                                <span><i class="bi bi-paperclip"></i>{{ $complaint->attachments->count() }} lampiran</span>
                                <span><i class="bi bi-chat-dots"></i>{{ $complaint->chat?->messages()->count() ?? 0 }} pesan chat</span>
                            </div>

                            {{-- Kotak Solusi --}}
                            @if ($complaint->handling)
                                <div class="pd-archive-solution">
                                    <div class="pd-archive-solution-title">Solusi Penanganan</div>
                                    <div class="pd-archive-solution-text">
                                        {{ \Illuminate\Support\Str::limit($complaint->handling->solusi, 300) }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Tombol Pulihkan --}}
                        <div class="flex-shrink-0">
                            <form method="POST" action="{{ route('archives.restore', $complaint->id) }}" class="d-inline restore-form">
                                @csrf
                                <button type="button" class="pd-btn-restore restore-btn" data-nomor="{{ $complaint->nomor_pengaduan }}">
                                    <i class="bi bi-arrow-counterclockwise"></i> Pulihkan
                                </button>
                            </form>
                        </div>
                        
                    </div>
                </div>
            @endforeach
        </div>

        {{-- PAGINASI --}}
        @if ($complaints->hasPages())
            <div class="pd-pagination">
                {{ $complaints->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        @endif
    @endif
</div>

{{-- ================= SCRIPT SWEETALERT ================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const restoreButtons = document.querySelectorAll('.restore-btn');

        restoreButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                const form = this.closest('form');
                const nomorPengaduan = this.getAttribute('data-nomor');

                Swal.fire({
                    title: 'Pulihkan Pengaduan?',
                    html: `Apakah Anda yakin ingin memulihkan pengaduan <strong>${nomorPengaduan}</strong> ke daftar aktif?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2856b6', // Biru
                    cancelButtonColor: '#64748b',  // Abu-abu
                    confirmButtonText: '<i class="bi bi-arrow-counterclockwise"></i> Ya, Pulihkan',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal-modern-popup',
                        title: 'swal-modern-title',
                        htmlContainer: 'swal-modern-html',
                        confirmButton: 'swal-modern-confirm',
                        cancelButton: 'swal-modern-cancel'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>

{{-- Style tambahan untuk mempercantik SweetAlert2 --}}
<style>
    .swal-modern-popup {
        border-radius: 20px !important;
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.2) !important;
        font-family: inherit !important;
    }
    .swal-modern-title {
        font-size: 20px !important;
        font-weight: 800 !important;
        color: #1e293b !important;
    }
    .swal-modern-html {
        font-size: 14px !important;
        color: #475569 !important;
    }
    .swal-modern-confirm {
        border-radius: 8px !important;
        font-weight: 700 !important;
        padding: 10px 20px !important;
    }
    .swal-modern-cancel {
        border-radius: 8px !important;
        font-weight: 700 !important;
        padding: 10px 20px !important;
    }
</style>

@endsection