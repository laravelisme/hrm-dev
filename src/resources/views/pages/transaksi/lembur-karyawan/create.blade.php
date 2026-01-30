@extends('layouts.master')

@section('title', 'Tambah Lembur Karyawan')
@section('subtitle', 'Transaksi')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Create Lembur Karyawan</h4>
                        <a href="{{ route('admin.transaksi.lembur-karyawan.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formAddLembur">
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
                                </div>

                                {{-- Atasan 2 (readonly) --}}
                                <div class="col-md-6">
                                    <label class="form-label">Atasan 2</label>
                                    <input type="text" class="form-control" id="atasan2_readonly" value="" readonly>
                                </div>

                                {{-- Tanggal lembur --}}
                                <div class="col-md-4">
                                    <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="date" id="date">
                                    <div class="invalid-feedback" data-error-for="date"></div>
                                </div>

                                {{-- Durasi menit --}}
                                <div class="col-md-4">
                                    <label class="form-label">Durasi Diajukan (menit) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="durasi_diajukan_menit" id="durasi_diajukan_menit"
                                           min="1" step="1" placeholder="60">
                                    <div class="invalid-feedback" data-error-for="durasi_diajukan_menit"></div>
                                    <div class="form-text">Contoh: 60 = 1 jam</div>
                                </div>

                                {{-- Note --}}
                                <div class="col-md-4">
                                    <label class="form-label">Catatan</label>
                                    <input type="text" class="form-control" name="note" placeholder="Opsional">
                                    <div class="invalid-feedback" data-error-for="note"></div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('admin.transaksi.lembur-karyawan.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Save Lembur
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
            const storeUrl = @json(route('admin.transaksi.lembur-karyawan.store'));
            const indexUrl = @json(route('admin.transaksi.lembur-karyawan.index'));

            const karyawanOptionsUrl = @json(route('admin.transaksi.lembur-karyawan.karyawan-options'));
            const karyawanDetailBase = @json(route('admin.transaksi.lembur-karyawan.karyawan-detail', ['id' => '__ID__']));

            function resetFieldErrors() {
                $('#formAddLembur .is-invalid').removeClass('is-invalid');
                $('#formAddLembur [data-error-for]').text('');
                $('#formAddLembur .select2-selection').removeClass('is-invalid');
            }

            function setFieldError(field, message) {
                const $input = $('#formAddLembur [name="' + field + '"]');
                $input.addClass('is-invalid');
                if ($input.is('select')) {
                    $input.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                }
                $('#formAddLembur [data-error-for="' + field + '"]').text(message);
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
                const label = String(e.params.data?.text || '').trim();

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

            // Submit AJAX
            $('#formAddLembur').on('submit', function (e) {
                e.preventDefault();
                resetFieldErrors();

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
                        Swal.fire({ icon:'success', title:'Success', text: res?.message || 'Lembur created' })
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

                        Swal.fire({ icon:'error', title:'Error', text: xhr.responseJSON?.message || 'Failed to create lembur' });
                    }
                });
            });
        });
    </script>
@endpush
