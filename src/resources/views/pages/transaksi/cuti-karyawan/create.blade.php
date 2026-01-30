@extends('layouts.master')

@section('title', 'Transaksi - Add Cuti Karyawan')
@section('subtitle', 'Add Cuti Karyawan')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Create Cuti Karyawan</h4>
                        <a href="{{ route('admin.transaksi.cuti-karyawan.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formAddCuti">
                            @csrf

                            {{-- REQUIRED hidden fields --}}
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
                                    <div class="invalid-feedback d-block" data-error-for="atasan1_id"></div>
                                </div>

                                {{-- Atasan 2 (readonly) --}}
                                <div class="col-md-6">
                                    <label class="form-label">Atasan 2</label>
                                    <input type="text" class="form-control" id="atasan2_readonly" value="" readonly>
                                    <div class="invalid-feedback d-block" data-error-for="atasan2_id"></div>
                                </div>

                                {{-- Jenis cuti --}}
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Cuti <span class="text-danger">*</span></label>
                                    <select id="m_jenis_cuti_id" name="m_jenis_cuti_id" class="form-select"></select>
                                    <div class="invalid-feedback d-block" data-error-for="m_jenis_cuti_id"></div>
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
                                    <label class="form-label">Jumlah Hari <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="jumlah_hari" id="jumlah_hari" min="1" step="1">
                                    <div class="invalid-feedback" data-error-for="jumlah_hari"></div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Tanggal Kembali</label>
                                    <input type="date" class="form-control" name="tanggal_kembali" id="tanggal_kembali">
                                    <div class="invalid-feedback" data-error-for="tanggal_kembali"></div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Keperluan <span class="text-danger">*</span></label>
                                    <textarea class="form-control" name="keperluan" rows="3"></textarea>
                                    <div class="invalid-feedback" data-error-for="keperluan"></div>
                                </div>

                                <div class="col-md-8">
                                    <label class="form-label">Alamat Selama Cuti</label>
                                    <input type="text" class="form-control" name="alamat_selama_cuti">
                                    <div class="invalid-feedback" data-error-for="alamat_selama_cuti"></div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">No Telp</label>
                                    <input type="text" class="form-control" name="no_telp">
                                    <div class="invalid-feedback" data-error-for="no_telp"></div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('admin.transaksi.cuti-karyawan.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Save Cuti
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
            const storeUrl = @json(route('admin.transaksi.cuti-karyawan.store'));
            const indexUrl = @json(route('admin.transaksi.cuti-karyawan.index'));

            const karyawanOptionsUrl = @json(route('admin.transaksi.cuti-karyawan.karyawan-options'));
            const jenisOptionsUrl    = @json(route('admin.transaksi.cuti-karyawan.jenis-cuti-options'));
            const karyawanDetailBase = @json(route('admin.transaksi.cuti-karyawan.karyawan-detail', ['id' => '__ID__']));

            function resetFieldErrors() {
                $('#formAddCuti .is-invalid').removeClass('is-invalid');
                $('#formAddCuti [data-error-for]').text('');
                $('#formAddCuti .select2-selection').removeClass('is-invalid');
            }

            function setFieldError(field, message) {
                const $input = $('#formAddCuti [name="' + field + '"]');
                $input.addClass('is-invalid');
                if ($input.is('select')) {
                    $input.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                }
                $('#formAddCuti [data-error-for="' + field + '"]').text(message);
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
            initSelect2Ajax($('#m_jenis_cuti_id'), jenisOptionsUrl, 'Pilih Jenis Cuti...');

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

            // Saat karyawan dipilih -> hit detail -> autofill company & atasan
            $('#m_karyawan_id').on('select2:select', function (e) {
                const id = e.params.data?.id;
                const label = (e.params.data?.text || '').trim();

                clearAutoFields();
                $('#nama_karyawan').val(label);

                if (!id) return;

                const url = karyawanDetailBase.replace('__ID__', id);

                $.get(url)
                    .done(function (d) {
                        // company
                        $('#m_company_id').val(d.m_company_id || '');
                        $('#nama_perusahaan').val(d.nama_perusahaan || '');
                        $('#company_readonly').val(d.nama_perusahaan || '-');

                        // atasan
                        $('#atasan1_id').val(d.atasan1_id || '');
                        $('#atasan2_id').val(d.atasan2_id || '');
                        $('#nama_atasan1').val(d.nama_atasan1 || '');
                        $('#nama_atasan2').val(d.nama_atasan2 || '');

                        $('#atasan1_readonly').val(d.nama_atasan1 || '-');
                        $('#atasan2_readonly').val(d.nama_atasan2 || '-');
                    })
                    .fail(function () {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal mengambil detail karyawan' });
                    });
            });

            $('#m_karyawan_id').on('select2:unselect', function () {
                clearAutoFields();
            });

            // Auto hitung jumlah hari + tanggal kembali
            function calcDays() {
                const s = $('#start_date').val();
                const e = $('#end_date').val();
                if (!s || !e) return;

                const start = new Date(s);
                const end = new Date(e);
                if (end < start) return;

                const msDay = 24*60*60*1000;
                const days = Math.floor((end - start) / msDay) + 1;
                $('#jumlah_hari').val(days);

                const back = new Date(end.getTime() + msDay);
                const yyyy = back.getFullYear();
                const mm = String(back.getMonth() + 1).padStart(2,'0');
                const dd = String(back.getDate()).padStart(2,'0');
                $('#tanggal_kembali').val(`${yyyy}-${mm}-${dd}`);
            }
            $('#start_date, #end_date').on('change', calcDays);

            // Submit AJAX
            $('#formAddCuti').on('submit', function (e) {
                e.preventDefault();
                resetFieldErrors();

                // extra guard: company harus terisi setelah pilih karyawan
                if (!$('#m_company_id').val()) {
                    Swal.fire({ icon:'warning', title:'Company kosong', text:'Company belum terisi. Pilih karyawan yang valid.' });
                    return;
                }

                const formData = $(this).serialize();
                $('#btnSubmit').prop('disabled', true);

                $.ajax({
                    url: storeUrl,
                    method: 'POST',
                    data: formData,
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
                    success: function (res) {
                        $('#btnSubmit').prop('disabled', false);
                        Swal.fire({ icon:'success', title:'Success', text: res?.message || 'Cuti created' })
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

                        Swal.fire({ icon:'error', title:'Error', text: xhr.responseJSON?.message || 'Failed to create cuti' });
                    }
                });
            });
        });
    </script>
@endpush
