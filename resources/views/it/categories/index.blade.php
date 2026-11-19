@extends('layouts.app')

@section('title', 'Kategori Kendala')

@section('content')

{{-- ================= LIBRARY SWEETALERT2 ================= --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- ================= STYLE ================= --}}
<style>
    /* ============================================================
       KATEGORI KENDALA IT — desain .pd-* (Konsisten)
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
    
    /* Tombol Hero */
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

    /* ---- Tabel ---- */
    .pd-table { width: 100%; border-collapse: collapse; min-width: 600px; }
    .pd-table thead th {
        text-align: left;
        padding: 14px 20px;
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
        padding: 16px 20px;
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
    .pd-table tbody tr:nth-child(2) { animation-delay: .25s; }
    .pd-table tbody tr:nth-child(3) { animation-delay: .3s; }
    .pd-table tbody tr:nth-child(4) { animation-delay: .35s; }
    .pd-table tbody tr:nth-child(5) { animation-delay: .4s; }
    .pd-table tbody tr:last-child td { border-bottom: 0; }
    .pd-table tbody tr:hover {
        background: #f6faff;
        transform: translateX(4px);
    }
    .pd-kategori-name {
        font-weight: 700;
        color: #244b9b;
    }
    
    /* Tombol Aksi */
    .pd-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        transition: all .18s ease;
        text-decoration: none;
    }
    .pd-action-btn:hover {
        background: #f8fafc;
        color: #2856b6;
        border-color: #c8d6ef;
    }
    .pd-action-btn.delete:hover {
        background: #fff1f2;
        color: #e11d48;
        border-color: #fecdd3;
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
        .pd-pagination { align-items: center; }
        .pd-pagination .pagination { flex-wrap: wrap; justify-content: center; }
        .pd-panel__head { flex-direction: column; align-items: flex-start; gap: 10px; }
    }

    @media (prefers-reduced-motion: reduce) {
        .pd-hero, .pd-hero::before, .pd-hero::after, .pd-panel,
        .pd-table tbody tr, .pd-empty, .pd-empty__icon {
            animation: none !important;
            transition: none !important;
        }
    }
</style>

{{-- ================= HERO BANNER ================= --}}
<div class="pd-hero">
    <div class="pd-hero__text">
        <span class="pd-hero__eyebrow">
            <i class="bi bi-tags"></i> Master Data
        </span>
        <h1>Kategori Kendala</h1>
        <p>Master kategori untuk klasifikasi pengaduan.</p>
    </div>
    <a href="{{ route('it.categories.create') }}" class="pd-hero__btn">
        <i class="bi bi-plus-circle"></i> Tambah Kategori
    </a>
</div>

{{-- ================= PANEL TABEL ================= --}}
<div class="pd-panel">
    
    <div class="pd-panel__head">
        <div class="pd-panel__title">
            <i class="bi bi-list-ul"></i> Daftar Kategori
        </div>
        @if (!$categories->isEmpty())
            <span class="pd-panel__count">
                Total: {{ $categories->total() }} kategori
            </span>
        @endif
    </div>

    @if ($categories->isEmpty())
        <div class="pd-empty">
            <div class="pd-empty__icon"><i class="bi bi-tags"></i></div>
            <h6>Belum Ada Kategori</h6>
            <p>Buat kategori untuk klasifikasi pengaduan.</p>
            <a href="{{ route('it.categories.create') }}" class="pd-empty__btn">
                <i class="bi bi-plus-circle"></i> Tambah Kategori
            </a>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table class="pd-table">
                <thead>
                    <tr>
                        <th>Nama Kategori</th>
                        <th class="d-none d-md-table-cell">Deskripsi</th>
                        <th>Jumlah Pengaduan</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $cat)
                        <tr>
                            <td class="pd-kategori-name">{{ $cat->nama_kategori }}</td>
                            <td class="text-muted small d-none d-md-table-cell">
                                {{ $cat->deskripsi ?: '-' }}
                            </td>
                            <td>
                                <span class="badge bg-primary px-3 py-2" style="border-radius:8px;">
                                    {{ $cat->complaints_count }}
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <div class="d-flex gap-1 justify-content-end">
                                    <a href="{{ route('it.categories.edit', $cat->id) }}"
                                       class="pd-action-btn" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    {{-- UBAH BAGIAN INI: Hapus onsubmit bawaan --}}
                                    <form method="POST" action="{{ route('it.categories.destroy', $cat->id) }}"
                                          class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="pd-action-btn delete" 
                                                title="Hapus" 
                                                data-nama="{{ $cat->nama_kategori }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- BAGIAN PAGINASI --}}
        <div class="pd-pagination">
            @if ($categories->hasPages())
                {{ $categories->onEachSide(1)->links('pagination::bootstrap-5') }}
            @else
                <nav>
                    <ul class="pagination">
                        <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
                            <span class="page-link" aria-hidden="true">‹</span>
                        </li>
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">1</span>
                        </li>
                        <li class="page-item disabled" aria-disabled="true" aria-label="Next »">
                            <span class="page-link" aria-hidden="true">›</span>
                        </li>
                    </ul>
                </nav>
            @endif
        </div>
    @endif
</div>

{{-- ================= SCRIPT SWEETALERT ================= --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Ambil semua tombol hapus
        const deleteButtons = document.querySelectorAll('.pd-action-btn.delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                // Ambil form terdekat
                const form = this.closest('form');
                // Ambil nama kategori dari atribut data-nama
                const namaKategori = this.getAttribute('data-nama');

                // Tampilkan pop-up SweetAlert2
                Swal.fire({
                    title: 'Hapus Kategori?',
                    html: `Apakah Anda yakin ingin menghapus kategori <strong>${namaKategori}</strong>?<br><span style="font-size: 13px; color: #64748b;">Tindakan ini tidak dapat dibatalkan.</span>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48', // Merah
                    cancelButtonColor: '#64748b',  // Abu-abu
                    confirmButtonText: '<i class="bi bi-trash"></i> Ya, Hapus',
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
                        // Jika dikonfirmasi, submit form
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