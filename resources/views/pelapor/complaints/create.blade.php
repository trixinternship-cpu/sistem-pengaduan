@extends('layouts.app')

@section('title', 'Buat Pengaduan')

@section('content')

<div class="page-header">
    <div>
        <h4>Buat Pengaduan Baru</h4>
        <p>Isi form di bawah sesuai kondisi kendala Anda</p>
    </div>
    <a href="{{ route('pelapor.complaints.index') }}" class="btn btn-outline-secondary">
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
            <div class="card h-100">
                <div class="card-header"><strong>Data Perangkat & Kategori</strong></div>
                <div class="card-body">
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
            <div class="card h-100">
                <div class="card-header"><strong>Siapa & Kapan</strong></div>
                <div class="card-body">
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
            <div class="card">
                <div class="card-header"><strong>Detail Kendala</strong></div>
                <div class="card-body">
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
            <div id="checkingIndicator" class="alert alert-secondary" style="display:none;">
                <span class="spinner-border spinner-border-sm me-2"></span>
                Memeriksa apakah kendala ini pernah ditangani sebelumnya...
            </div>

            <div id="recurringFound" class="alert alert-warning border-warning" style="display:none;">
                <h5 class="alert-heading mb-2">
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

            <div id="noRecurringFound" class="alert alert-success" style="display:none;">
                <i class="bi bi-check-circle-fill"></i>
                Tidak ada kendala serupa. Anda bisa langsung kirim pengaduan ini ke tim IT.
            </div>
        </div>

        {{-- Lampiran --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header"><strong>Lampiran (opsional)</strong></div>
                <div class="card-body">
                    <input type="file" name="attachments[]" class="form-control"
                           accept=".jpg,.jpeg,.png,.pdf" multiple>
                    <small class="text-muted">
                        Maks 3 file, masing-masing maks 2MB. Format: JPG, PNG, PDF.
                    </small>
                </div>
            </div>
        </div>

    </div>

    <div class="d-flex justify-content-between mt-4 flex-wrap gap-2">
        <a href="{{ route('pelapor.complaints.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-primary" id="btnSubmit" disabled>
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
                <p>Coba solusi dari riwayat kendala sebelumnya. Apakah masalah teratasi?</p>
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

    // Panggil cek setiap kali user selesai mengetik
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

        // Wajib: PC + Kategori + What & How minimal 10 karakter
        if (!pcId || !catId || what.length < 10 || how.length < 10) {
            resetState();
            return;
        }

        // Tampilkan indikator
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
                // Ada kendala serupa → tampilkan alert + kunci tombol submit
                hasRecurring = true;
                renderRecurring(data.data);
                recurringFound.style.display = 'block';
                noRecurringFound.style.display = 'none';

                // Tombol submit tetap disabled
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="bi bi-lock"></i> Pilih Aksi Dulu di Atas';
            } else {
                // Tidak ada kendala serupa → aktifkan tombol submit
                hasRecurring = false;
                recurringFound.style.display = 'none';
                noRecurringFound.style.display = 'block';

                btnSubmit.disabled = false;
                btnSubmit.innerHTML = '<i class="bi bi-send"></i> Kirim Pengaduan ke IT';
            }
        } catch (err) {
            console.error(err);
            checkingIndicator.style.display = 'none';
            // Kalau error → izinkan submit (fallback)
            hasRecurring = false;
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="bi bi-send"></i> Kirim Pengaduan ke IT';
        }
    }

    function renderRecurring(items) {
        let html = '';
        items.forEach(item => {
            html += `
                <div class="card mb-2 border-0 shadow-sm">
                    <div class="card-body py-3">
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
                            <div class="mb-1 p-2 bg-light rounded">
                                <strong>💡 Solusi dari IT:</strong><br>
                                ${item.solusi}
                            </div>
                            <div class="text-muted mt-1 small">
                                Ditangani oleh <strong>${item.teknisi}</strong> — Hasil: <strong>${item.hasil}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        recurringList.innerHTML = html;

        // Simpan id kendala pertama sebagai related default
        selectedRelatedId = items[0].id;
    }

    // Trigger otomatis saat user selesai mengetik
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
        // Aktifkan tombol submit, hapus keterkaitan
        hasRecurring = false;
        document.getElementById('relatedComplaintId').value = '';
        document.getElementById('recurringResult').value = '';
        btnSubmit.disabled = false;
        btnSubmit.innerHTML = '<i class="bi bi-send"></i> Kirim Pengaduan ke IT';

        // Sembunyikan alert & tampilkan info
        recurringFound.style.display = 'none';
        noRecurringFound.style.display = 'block';
        noRecurringFound.innerHTML = '<i class="bi bi-info-circle-fill"></i> Anda memilih "Masalah Berbeda". Klik tombol Kirim untuk mengirim ke IT.';
    });
});
</script>
@endpush