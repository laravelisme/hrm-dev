@extends('layouts.master')

@section('title', 'Transaksi - Add Izin Karyawan')
@section('subtitle', 'Add Izin Karyawan')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Create Izin Karyawan</h4>
                        <a href="{{ route('admin.transaksi.izin-karyawan.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formAddIzin">
                            @csrf

                            {{-- REQUIRED hidden fields (biar konsisten sama Cuti) --}}
                            <input type="hidden" name="nama_karyawan" id="nama_karyawan">
                            <input type="hidden" name="m_company_id" id="m_company_id">
                            <input type="hidden" name="nama_perusahaan" id="nama_perusahaan">
                            <input type="hidden" name="atasan1_id" id="atasan1_id">
                            <input type="hidden" name="atasan2_id" id="atasan2_id">
                            <input type="hidden" name="nama_atasan1" id="nama_atasan1">
                            <input type="hidden" name="nama_atasan2" id="nama_atasan2">

                            <div class="row g-3">
                                {{-- Karyawan --}}
                                <div class="col-md-6">
                                    <label class="form-label">Karyawan <span class="text-danger">*</span></label>
                                    <select id="m_karyawan_id" name="m_karyawan_id" class="form-select"></select>
                                    <div class="invalid-feedback d-block" data-error-for="m_karyawan_id"></div>
                                </div>

                                {{-- Company (readonly) --}}
                                <div class="col-md-6">
                                    <label class="form-label">Company <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="company_readonly" value="" readonly>
                                    <div class="invalid-feedback d-block" data-error-for="m_company_id"></div>
                                </div>

                                {{-- Atasan 1 (readonly) --}}
                                <div class="col-md-6">
                                    <label class="form-label">Atasan 1</label>
                                    <input type="text" class="form-control" id="atasan1_readonly" value="" readonly>
                                </div>

                                {{-- Atasan 2 (readonly) --}}
                                <div class="col-md-6">
                                    <label class="form-label">Atasan 2</label>
                                    <input type="text" class="form-control" id="atasan2_readonly" value="" readonly>
                                </div>

                                {{-- Jenis Izin --}}
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Izin <span class="text-danger">*</span></label>
                                    <select id="m_jenis_izin_id" name="m_jenis_izin_id" class="form-select"></select>
                                    <div class="invalid-feedback d-block" data-error-for="m_jenis_izin_id"></div>
                                    <div class="form-text">
                                        Jenis 1 & 2: end_date otomatis sama dengan start_date. Jenis 3 & 4: end_date bisa berbeda.
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="start_date" id="start_date">
                                    <div class="invalid-feedback" data-error-for="start_date"></div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="end_date" id="end_date">
                                    <div class="invalid-feedback" data-error-for="end_date"></div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Durasi <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="durasi" id="durasi" min="1" step="1" placeholder="1">
                                    <div class="invalid-feedback" data-error-for="durasi"></div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Tanggal Kembali</label>
                                    <input type="date" class="form-control" name="tanggal_kembali" id="tanggal_kembali">
                                    <div class="invalid-feedback" data-error-for="tanggal_kembali"></div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Keperluan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="keperluan" rows="3"></textarea>
                                    <div class="invalid-feedback" data-error-for="keperluan"></div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('admin.transaksi.izin-karyawan.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Save Izin
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const storeUrl = @json(route('admin.transaksi.izin-karyawan.store'));
            const indexUrl = @json(route('admin.transaksi.izin-karyawan.index'));

            const karyawanOptionsUrl = @json(route('admin.transaksi.izin-karyawan.karyawan-options'));
            const jenisOptionsUrl    = @json(route('admin.transaksi.izin-karyawan.jenis-izin-options'));
            const karyawanDetailBase = @json(route('admin.transaksi.izin-karyawan.karyawan-detail', ['id' => '__ID__']));

            function resetFieldErrors() {
                $('#formAddIzin .is-invalid').removeClass('is-invalid');
                $('#formAddIzin [data-error-for]').text('');
                $('#formAddIzin .select2-selection').removeClass('is-invalid');
            }

            function setFieldError(field, message) {
                const $input = $('#formAddIzin [name="' + field + '"]');
                $input.addClass('is-invalid');
                if ($input.is('select')) {
                    $input.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                }
                $('#formAddIzin [data-error-for="' + field + '"]').text(message);
            }

            function initSelect2Ajax($el, url, placeholder) {
                $el.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder,
                    allowClear: true,
                    ajax: {
                        url,
                        dataType: 'json',
                        delay: 250,
                        data: (params) => ({ q: params.term || '', page: params.page || 1, perPage: 20 }),
                        processResults: (data) => ({
                            results: data.results || [],
                            pagination: { more: data.pagination?.more === true }
                        }),
                        cache: true
                    }
                });
            }

            initSelect2Ajax($('#m_karyawan_id'), karyawanOptionsUrl, 'Pilih Karyawan...');
            initSelect2Ajax($('#m_jenis_izin_id'), jenisOptionsUrl, 'Pilih Jenis Izin...');

            function clearAutoFields() {
                $('#nama_karyawan').val('');
                $('#m_company_id').val('');
                $('#nama_perusahaan').val('');
                $('#atasan1_id').val('');
                $('#atasan2_id').val('');
                $('#nama_atasan1').val('');
                $('#nama_atasan2').val('');

                $('#company_readonly').val('');
                $('#atasan1_readonly').val('');
                $('#atasan2_readonly').val('');
            }

            // ===== Dynamic end_date rule (1/2 sama, 3/4 beda) =====
            function applyEndDateRule() {
                const jenis = parseInt($('#m_jenis_izin_id').val() || '0', 10);
                const start = $('#start_date').val();

                // set min end_date >= start
                if (start) $('#end_date').attr('min', start);

                if (jenis === 1 || jenis === 2) {
                    if (start) $('#end_date').val(start);
                    $('#end_date').prop('disabled', true);
                } else if (jenis === 3 || jenis === 4) {
                    $('#end_date').prop('disabled', false);
                } else {
                    $('#end_date').prop('disabled', false);
                }
            }

            // ===== Auto hitung durasi + tanggal kembali (mirip cuti) =====
            function calcDurasiKembali() {
                const s = $('#start_date').val();
                const e = $('#end_date').val();
                if (!s || !e) return;

                const start = new Date(s);
                const end = new Date(e);
                if (isNaN(start.getTime()) || isNaN(end.getTime())) return;
                if (end < start) return;

                const msDay = 24*60*60*1000;
                const days = Math.floor((end - start) / msDay) + 1;
                $('#durasi').val(days);

                const back = new Date(end.getTime() + msDay);
                const yyyy = back.getFullYear();
                const mm = String(back.getMonth() + 1).padStart(2,'0');
                const dd = String(back.getDate()).padStart(2,'0');
                $('#tanggal_kembali').val(`${yyyy}-${mm}-${dd}`);
            }

            $('#m_jenis_izin_id').on('change', function () {
                applyEndDateRule();
                calcDurasiKembali();
            });

            $('#start_date').on('change', function () {
                applyEndDateRule();
                calcDurasiKembali();
            });

            $('#end_date').on('change', function () {
                calcDurasiKembali();
            });

            // ===== Saat karyawan dipilih -> autofill company & atasan =====
            $('#m_karyawan_id').on('select2:select', function (e) {
                const id = e.params.data?.id;
                const label = String(e.params.data?.text || '').trim();

                clearAutoFields();
                $('#nama_karyawan').val(label);

                if (!id) return;

                const url = karyawanDetailBase.replace('__ID__', id);

                $.get(url)
                    .done(function (d) {
                        $('#m_company_id').val(d.m_company_id || '');
                        $('#nama_perusahaan').val(d.nama_perusahaan || '');
                        $('#company_readonly').val(d.nama_perusahaan || '-');

                        $('#atasan1_id').val(d.atasan1_id || '');
                        $('#atasan2_id').val(d.atasan2_id || '');
                        $('#nama_atasan1').val(d.nama_atasan1 || '');
                        $('#nama_atasan2').val(d.nama_atasan2 || '');

                        $('#atasan1_readonly').val(d.nama_atasan1 || '-');
                        $('#atasan2_readonly').val(d.nama_atasan2 || '-');
                    })
                    .fail(function () {
                        Swal.fire({ icon:'error', title:'Error', text:'Gagal mengambil detail karyawan' });
                    });
            });

            $('#m_karyawan_id').on('select2:unselect', function () {
                clearAutoFields();
            });

            // ===== Submit AJAX =====
            $('#formAddIzin').on('submit', function (e) {
                e.preventDefault();
                resetFieldErrors();

                // guard: company harus keisi setelah pilih karyawan
                if (!$('#m_company_id').val()) {
                    Swal.fire({ icon:'warning', title:'Company kosong', text:'Company belum terisi. Pilih karyawan yang valid.' });
                    return;
                }

                applyEndDateRule();

                // kalau end_date disabled (jenis 1/2), serialize gak akan ikut -> enable sementara
                const wasDisabled = $('#end_date').prop('disabled');
                if (wasDisabled) $('#end_date').prop('disabled', false);

                const formData = $(this).serialize();

                // balikin state
                if (wasDisabled) $('#end_date').prop('disabled', true);

                $('#btnSubmit').prop('disabled', true);

                $.ajax({
                    url: storeUrl,
                    method: 'POST',
                    data: formData,
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
                    success: function (res) {
                        $('#btnSubmit').prop('disabled', false);
                        Swal.fire({ icon:'success', title:'Success', text: res?.message || 'Izin created' })
                            .then(() => window.location.href = indexUrl);
                    },
                    error: function (xhr) {
                        $('#btnSubmit').prop('disabled', false);

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON?.errors || {};
                            Object.keys(errors).forEach(k => setFieldError(k, errors[k][0]));
                            Swal.fire({ icon:'error', title:'Validation Error', text:'Please check fields.' });
                            return;
                        }

                        Swal.fire({ icon:'error', title:'Error', text: xhr.responseJSON?.message || 'Failed to create izin' });
                    }
                });
            });

            // initial
            applyEndDateRule();
        });
    </script>
@endpush
