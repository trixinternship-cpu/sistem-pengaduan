@extends('layouts.app')

@section('title', 'Buat Pengaduan')

@section('content')

{{-- ================= STYLE ================= --}}
<style>
    /* ============================================================
       FORM BUAT PENGADUAN — konsisten dengan dashboard (.pd-*)
    ============================================================ */
    @keyframes pdFadeUp {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes pdPopIn {
        0%   { opacity: 0; transform: scale(.95) translateY(10px); }
        70%  { transform: scale(1.01) translateY(0); }
        100% { opacity: 1; transform: scale(1) translateY(0); }
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

    /* ---- Hero Banner (sama gaya dengan dashboard) ---- */
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
        background: rgba(255,255,255,.14);
        color: #fff;
        border: 1px solid rgba(255,255,255,.3);
        transition: transform .2s ease, background .2s ease;
    }
    .pd-hero__btn:hover {
        background: rgba(255,255,255,.24);
        color: #fff;
        transform: translateY(-3px);
    }

    /* ---- Kartu form ---- */
    .pd-card {
        position: relative;
        border: 1px solid #e8edf4;
        border-radius: 18px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(24,43,74,.04);
        animation: pdPopIn .5s cubic-bezier(.22,.8,.32,1) both;
        height: 100%;
    }
    .pd-card__head {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px 20px;
        border-bottom: 1px solid #edf1f6;
        background: linear-gradient(90deg, #fbfdff, #ffffff);
        font-size: 14px;
        font-weight: 750;
        color: #1d2b42;
    }
    .pd-card__head i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px; height: 30px;
        border-radius: 9px;
        background: #eaf1fd;
        color: #2856b6;
        font-size: 15px;
        flex-shrink: 0;
    }
    .pd-card__body { padding: 20px; }

    /* Delay animasi kartu */
    .row.g-3 > .col-12.col-md-6:nth-child(1) .pd-card { animation-delay: .05s; }
    .row.g-3 > .col-12.col-md-6:nth-child(2) .pd-card { animation-delay: .13s; }
    .row.g-3 > .col-12:nth-child(3) .pd-card { animation-delay: .21s; }
    .row.g-3 > .col-12:nth-child(5) .pd-card { animation-delay: .29s; }

    /* ---- Form controls ---- */
    .pd-card .form-label {
        font-size: .86rem;
        font-weight: 650;
        color: #35445b;
        margin-bottom: 7px;
    }
    .pd-card .form-control,
    .pd-card .form-select {
        min-height: 42px;
        padding: 9px 13px;
        border: 1px solid #dce4ef;
        border-radius: 9px;
        color: #35445b;
        font-size: .9rem;
        box-shadow: none;
        transition: border-color .18s ease, box-shadow .18s ease;
    }
    .pd-card .form-control:focus,
    .pd-card .form-select:focus {
        border-color: #8aa9e5;
        box-shadow: 0 0 0 3px rgba(40,86,182,.10);
    }

    /* ---- Alert deteksi kendala ---- */
    .pd-alert {
        border: 0;
        border-radius: 14px;
        padding: 18px 20px;
        margin-bottom: 16px;
        animation: pdFadeUp .4s ease both;
        font-size: .88rem;
    }
    .pd-alert--check {
        background: #f1f5fb;
        color: #3b4a63;
        border: 1px solid #e1e8f3;
    }
    .pd-alert--found {
        background: #fff8eb;
        border: 1px solid #f5d9a8;
        color: #7a4b00;
    }
    .pd-alert--found .alert-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 1rem;
        font-weight: 750;
        color: #b45309;
        margin-bottom: 8px;
    }
    .pd-alert--ok {
        background: #e9f9f0;
        border: 1px solid #b8e9cf;
        color: #047857;
        font-weight: 600;
    }

    /* Kartu riwayat kendala serupa */
    .pd-history {
        border: 1px solid #eef2f7;
        border-radius: 12px;
        background: #fff;
        padding: 14px 16px;
        margin-bottom: 10px;
        transition: transform .18s ease, box-shadow .18s ease;
    }
    .pd-history:hover {
        transform: translateX(3px);
        box-shadow: 0 8px 18px -10px rgba(24,43,74,.3);
    }
    .pd-history .badge {
        border-radius: 7px;
        font-weight: 650;
        font-size: 10.5px;
        padding: 5px 9px;
    }
    .pd-history .pd-solusi {
        background: #eef6ff;
        border-left: 3px solid #2856b6;
        border-radius: 8px;
        padding: 10px 12px;
        margin-top: 8px;
        font-size: .84rem;
        color: #244b9b;
    }

    /* ---- Tombol aksi ---- */
    .pd-actions {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 28px;
        animation: pdFadeUp .5s ease both;
        animation-delay: .35s;
    }
    .pd-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 11px 22px;
        border-radius: 11px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        border: 0;
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
    }
    .pd-btn--ghost {
        background: #fff;
        color: #475569;
        border: 1px solid #dce4ef;
    }
    .pd-btn--ghost:hover {
        background: #f6f8fc;
        color: #1d2b42;
        transform: translateY(-2px);
        box-shadow: 0 8px 18px -8px rgba(24,43,74,.3);
    }
    .pd-btn--primary {
        background: linear-gradient(120deg, #2856b6, #3b82f6);
        color: #fff;
        box-shadow: 0 10px 24px -10px rgba(40,86,182,.7);
    }
    .pd-btn--primary:hover:not(:disabled) {
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 16px 30px -12px rgba(40,86,182,.8);
    }
    .pd-btn--primary:disabled {
        background: #cbd6e8;
        box-shadow: none;
        cursor: not-allowed;
        opacity: .9;
    }

    /* Tombol di dalam alert */
    .pd-alert--found .btn-success {
        background: #10b981;
        border: 0;
        border-radius: 9px;
        font-weight: 650;
        font-size: 12.5px;
        padding: 8px 15px;
    }
    .pd-alert--found .btn-outline-secondary {
        border-radius: 9px;
        font-weight: 650;
        font-size: 12.5px;
        padding: 8px 15px;
    }

    /* Modal konsisten */
    #confirmModal .modal-content {
        border: 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 24px 60px -20px rgba(24,43,74,.5);
    }
    #confirmModal .modal-header {
        border-bottom: 1px solid #edf1f6;
        padding: 18px 22px;
    }
    #confirmModal .modal-title { font-weight: 750; color: #1d2b42; }
    #confirmModal .modal-body { padding: 20px 22px; color: #475569; }
    #confirmModal .modal-footer { border-top: 1px solid #edf1f6; padding: 14px 22px; }

    /* ---- Responsif ---- */
    @media (max-width: 767.98px) {
        .pd-hero { padding: 22px; }
        .pd-hero h1 { font-size: 22px; }
        .pd-card__body { padding: 16px; }
        .pd-actions { flex-direction: column-reverse; }
        .pd-actions .pd-btn { width: 100%; justify-content: center; }
    }

    @media (prefers-reduced-motion: reduce) {
        .pd-hero, .pd-hero::before, .pd-hero::after, .pd-card,
        .pd-alert, .pd-actions, .pd-btn, .pd-history {
            animation: none !important;
            transition: none !important;
        }
    }
</style>

{{-- ================= HERO BANNER ================= --}}
<div class="pd-hero">
    <div class="pd-hero__text">
        <span class="pd-hero__eyebrow">
            <i class="bi bi-pencil-square"></i> Form Pengaduan
        </span>
        <h1>Buat Pengaduan Baru</h1>
        <p>Isi form di bawah sesuai kondisi kendala Anda.</p>
    </div>
    <a href="{{ route('pelapor.complaints.index') }}" class="pd-hero__btn">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form method="POST" action="{{ route('pelapor.complaints.store') }}"
      enctype="multipart/form-data" id="complaintForm" class="form-with-loading">
    @csrf
    <input type="hidden" name="related_complaint_id" id="relatedComplaintId" value="">
    <input type="hidden" name="recurring_result" id="recurringResult" value="">

    <div class="row g-3">

        {{-- Data Perangkat & Kategori --}}
        <div class="col-12 col-md-6">
            <div class="pd-card">
                <div class="pd-card__head">
                    <i class="bi bi-pc-display"></i> Data Perangkat & Kategori
                </div>
                <div class="pd-card__body">
                    <div class="mb-3">
                        <label class="form-label">Perangkat <span class="text-danger">*</span></label>
                        <select name="pc_id" id="pc_id" class="form-select" required>
                            <option value="">-- Pilih Perangkat --</option>
                            @foreach ($pcs as $pc)
                                <option value="{{ $pc->id }}" {{ old('pc_id') == $pc->id ? 'selected' : '' }}>
                                    {{ $pc->kode_pc }} — {{ $pc->nama_pc }} ({{ $pc->departemen }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori Kendala <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Jenis Kendala <span class="text-danger">*</span></label>
                        <input type="text" name="jenis_kendala" class="form-control"
                               value="{{ old('jenis_kendala') }}"
                               placeholder="Contoh: Tidak bisa internet" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- Who & When --}}
        <div class="col-12 col-md-6">
            <div class="pd-card">
                <div class="pd-card__head">
                    <i class="bi bi-person-lines-fill"></i> Siapa & Kapan
                </div>
                <div class="pd-card__body">
                    <div class="mb-3">
                        <label class="form-label">Siapa yang mengalami? <span class="text-danger">*</span></label>
                        <input type="text" name="who" class="form-control"
                               value="{{ old('who', auth()->user()->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kapan terjadi? <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="when" id="whenInput" class="form-control"
                               value="{{ old('when') }}" required>
                        <small class="text-muted d-block mt-1">
                            <i class="bi bi-clock"></i>
                            Sekarang: <span id="liveTimeHint" class="fw-semibold text-primary">--:--:--</span>
                        </small>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Di mana? (lokasi) <span class="text-danger">*</span></label>
                        <input type="text" name="where" class="form-control"
                               value="{{ old('where') }}"
                               placeholder="Contoh: Lantai 2, Ruang Finance" required>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detail Kendala --}}
        <div class="col-12">
            <div class="pd-card">
                <div class="pd-card__head">
                    <i class="bi bi-chat-left-text"></i> Detail Kendala
                </div>
                <div class="pd-card__body">
                    <div class="mb-3">
                        <label class="form-label">Apa kendalanya? (What) <span class="text-danger">*</span></label>
                        <textarea name="what" id="what" rows="2" class="form-control" required
                                  placeholder="Contoh: PC tidak bisa terhubung ke internet">{{ old('what') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kronologi (How) <span class="text-danger">*</span></label>
                        <textarea name="how" id="how" rows="3" class="form-control" required
                                  placeholder="Jelaskan urutan kejadiannya...">{{ old('how') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Lengkap <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="form-control" required
                                  placeholder="Deskripsikan kondisi kendala secara detail...">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Penyebab yang Anda ketahui (Why, opsional)</label>
                        <textarea name="why" rows="2" class="form-control"
                                  placeholder="Kosongkan jika tidak tahu">{{ old('why') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- ============== AREA DETEKSI KENDALA SERUPA ============== --}}
        <div class="col-12">
            <div id="checkingIndicator" class="pd-alert pd-alert--check" style="display:none;">
                <span class="spinner-border spinner-border-sm me-2"></span>
                Memeriksa apakah kendala ini pernah ditangani sebelumnya...
            </div>

            <div id="recurringFound" class="pd-alert pd-alert--found" style="display:none;">
                <h5 class="alert-heading">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Kendala Serupa Ditemukan
                </h5>
                <p class="mb-2">
                    Kendala ini pernah ditangani pada perangkat yang sama.
                    <strong>Silakan coba solusi berikut terlebih dahulu sebelum dikirim ke tim IT.</strong>
                </p>
                <div id="recurringList"></div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <button type="button" class="btn btn-success" id="btnCobaSolusi">
                        <i class="bi bi-lightbulb"></i> Masalah Sama, Coba Solusi Ini
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btnBedaMasalah">
                        <i class="bi bi-x-circle"></i> Masalah Berbeda, Kirim ke IT
                    </button>
                </div>
            </div>

            <div id="noRecurringFound" class="pd-alert pd-alert--ok" style="display:none;">
                <i class="bi bi-check-circle-fill"></i>
                Tidak ada kendala serupa. Anda bisa langsung kirim pengaduan ini ke tim IT.
            </div>
        </div>

        {{-- Lampiran --}}
        <div class="col-12">
            <div class="pd-card">
                <div class="pd-card__head">
                    <i class="bi bi-paperclip"></i> Lampiran (opsional)
                </div>
                <div class="pd-card__body">
                    <input type="file" name="attachments[]" class="form-control"
                           accept=".jpg,.jpeg,.png,.pdf" multiple>
                    <small class="text-muted d-block mt-2">
                        Maks 3 file, masing-masing maks 2MB. Format: JPG, PNG, PDF.
                    </small>
                </div>
            </div>
        </div>

    </div>

    <div class="pd-actions">
        <a href="{{ route('pelapor.complaints.index') }}" class="pd-btn pd-btn--ghost">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="pd-btn pd-btn--primary" id="btnSubmit" disabled>
            <i class="bi bi-send"></i> Kirim Pengaduan ke IT
        </button>
    </div>
</form>

{{-- Modal Konfirmasi Hasil Solusi --}}
<div class="modal fade" id="confirmModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Apakah solusi ini berhasil?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Coba solusi dari riwayat kendala sebelumnya. Apakah masalah teratasi?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" id="btnNotWorked">
                    <i class="bi bi-x-circle"></i> Tidak Berhasil
                </button>
                <button type="button" class="btn btn-success" id="btnWorked">
                    <i class="bi bi-check-circle"></i> Ya, Berhasil
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ==================== JAM REAL-TIME ==================== */
    const whenInput = document.getElementById('whenInput');
    const liveTimeHint = document.getElementById('liveTimeHint');
    let userChangedWhen = false;

    function pad(n) { return n < 10 ? '0' + n : n; }
    function getLocalDateTimeString(date) {
        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }
    function updateWhenField() {
        const now = new Date();
        if (liveTimeHint) {
            liveTimeHint.textContent = pad(now.getHours()) + ':' + pad(now.getMinutes()) + ':' + pad(now.getSeconds());
        }
        if (whenInput && !userChangedWhen) {
            whenInput.value = getLocalDateTimeString(now);
        }
    }
    if (whenInput) whenInput.addEventListener('change', () => { userChangedWhen = true; });
    updateWhenField();
    setInterval(updateWhenField, 1000);

    /* ==================== DETEKSI OTOMATIS ==================== */
    const form = document.getElementById('complaintForm');
    const btnSubmit = document.getElementById('btnSubmit');
    const pcSelect = document.getElementById('pc_id');
    const catSelect = document.getElementById('category_id');
    const whatInput = document.getElementById('what');
    const howInput = document.getElementById('how');
    const descInput = document.getElementById('deskripsi');

    const checkingIndicator = document.getElementById('checkingIndicator');
    const recurringFound = document.getElementById('recurringFound');
    const noRecurringFound = document.getElementById('noRecurringFound');
    const recurringList = document.getElementById('recurringList');

    const checkUrl = "{{ route('pelapor.complaints.check-recurring') }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    let debounceTimer = null;
    let selectedRelatedId = null;
    let hasRecurring = false;

    function scheduleCheck() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(performCheck, 1500);
    }

    function resetState() {
        hasRecurring = false;
        selectedRelatedId = null;
        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="bi bi-send"></i> Kirim Pengaduan ke IT';
        recurringFound.style.display = 'none';
        noRecurringFound.style.display = 'none';
        checkingIndicator.style.display = 'none';
        document.getElementById('relatedComplaintId').value = '';
        document.getElementById('recurringResult').value = '';
    }

    async function performCheck() {
        const pcId = pcSelect.value;
        const catId = catSelect.value;
        const what = whatInput.value.trim();
        const how = howInput.value.trim();
        const desc = descInput.value.trim();

        if (!pcId || !catId || what.length < 10 || how.length < 10) {
            resetState();
            return;
        }

        checkingIndicator.style.display = 'block';
        recurringFound.style.display = 'none';
        noRecurringFound.style.display = 'none';

        try {
            const res = await fetch(checkUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    pc_id: pcId,
                    category_id: catId,
                    what: what,
                    how: how,
                    deskripsi: desc,
                }),
            });

            const data = await res.json();
            checkingIndicator.style.display = 'none';

            if (data.found && data.data.length > 0) {
                hasRecurring = true;
                renderRecurring(data.data);
                recurringFound.style.display = 'block';
                noRecurringFound.style.display = 'none';

                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="bi bi-lock"></i> Pilih Aksi Dulu di Atas';
            } else {
                hasRecurring = false;
                recurringFound.style.display = 'none';
                noRecurringFound.style.display = 'block';

                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="bi bi-send"></i> Kirim Pengaduan ke IT';
            }
        } catch (err) {
            console.error(err);
            checkingIndicator.style.display = 'none';
            hasRecurring = false;
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="bi bi-send"></i> Kirim Pengaduan ke IT';
        }
    }

    function renderRecurring(items) {
        let html = '';
        items.forEach(item => {
            html += `
                <div class="pd-history">
                    <div class="d-flex justify-content-between flex-wrap gap-2 mb-2">
                        <div>
                            <span class="badge bg-secondary">${item.nomor_pengaduan}</span>
                            <span class="text-muted small ms-2">Ditangani: ${item.tanggal_penanganan}</span>
                        </div>
                        <span class="badge bg-primary">Skor: ${item.score}</span>
                    </div>
                    <div class="small">
                        <div class="mb-1"><strong>Perangkat:</strong> ${item.pc}</div>
                        <div class="mb-1"><strong>Kategori:</strong> ${item.kategori}</div>
                        <div class="mb-1"><strong>Kendala:</strong> ${item.what}</div>
                        <hr class="my-2">
                        <div class="mb-1"><strong>Penyebab:</strong> ${item.penyebab}</div>
                        <div class="mb-1"><strong>Tindakan IT:</strong> ${item.tindakan}</div>
                        <div class="pd-solusi">
                            <strong>💡 Solusi dari IT:</strong><br>
                            ${item.solusi}
                        </div>
                        <div class="text-muted mt-2 small">
                            Ditangani oleh <strong>${item.teknisi}</strong> — Hasil: <strong>${item.hasil}</strong>
                        </div>
                    </div>
                </div>
            `;
        });
        recurringList.innerHTML = html;
        selectedRelatedId = items[0].id;
    }

    pcSelect.addEventListener('change', scheduleCheck);
    catSelect.addEventListener('change', scheduleCheck);
    whatInput.addEventListener('input', scheduleCheck);
    howInput.addEventListener('input', scheduleCheck);
    descInput.addEventListener('input', scheduleCheck);

    /* ==================== AKSI: COBA SOLUSI ==================== */
    const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));

    document.getElementById('btnCobaSolusi').addEventListener('click', function () {
        if (!selectedRelatedId) return;
        confirmModal.show();
    });

    document.getElementById('btnWorked').addEventListener('click', function () {
        document.getElementById('relatedComplaintId').value = selectedRelatedId;
        document.getElementById('recurringResult').value = 'berhasil';
        confirmModal.hide();
        form.submit();
    });

    document.getElementById('btnNotWorked').addEventListener('click', function () {
        document.getElementById('relatedComplaintId').value = selectedRelatedId;
        document.getElementById('recurringResult').value = 'gagal';
        confirmModal.hide();
        form.submit();
    });

    /* ==================== AKSI: MASALAH BERBEDA ==================== */
    document.getElementById('btnBedaMasalah').addEventListener('click', function () {
        hasRecurring = false;
        document.getElementById('relatedComplaintId').value = '';
        document.getElementById('recurringResult').value = '';
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = '<i class="bi bi-send"></i> Kirim Pengaduan ke IT';

        recurringFound.style.display = 'none';
        noRecurringFound.style.display = 'block';
        noRecurringFound.innerHTML = '<i class="bi bi-info-circle-fill"></i> Anda memilih "Masalah Berbeda". Klik tombol Kirim untuk mengirim ke IT.';
    });
});
</script>
@endpush