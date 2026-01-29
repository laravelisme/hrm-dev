@extends('layouts.master')

@section('title', 'Add Saldo Cuti')
@section('subtitle', 'Add Saldo Cuti')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Create New Saldo Cuti</h4>
                        <a href="{{ route('admin.master-data.saldo-cuti.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formAddSaldoCuti">
                            @csrf
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Jenis Cuti <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="jenis" placeholder="Cuti Tahunan">
                                    <div class="invalid-feedback" data-error-for="jenis"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="jumlah" placeholder="12" min="0" step="1">
                                    <div class="invalid-feedback" data-error-for="jumlah"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                    <select id="m_jabatan_id" name="m_jabatan_id" class="form-select">
                                        {{-- kosong, nanti diisi select2 ajax --}}
                                    </select>
                                    <div class="invalid-feedback d-block" data-error-for="m_jabatan_id"></div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('admin.master-data.saldo-cuti.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Save Saldo Cuti
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
            const storeUrl = @json(route('admin.master-data.saldo-cuti.store'));
            const indexUrl = @json(route('admin.master-data.saldo-cuti.index'));

            const jabatanOptionsUrl = @json(route('admin.master-data.saldo-cuti.jabatan-options'));

            function initJabatanSelect2() {
                const $el = $('#m_jabatan_id');

                $el.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Pilih Jabatan...',
                    allowClear: true,
                    ajax: {
                        url: jabatanOptionsUrl,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term || '',
                                page: params.page || 1,
                                perPage: 20
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results || [],
                                pagination: { more: data.pagination?.more === true }
                            };
                        },
                        cache: true
                    }
                });

                // remove error state when changed
                $el.on('change', function () {
                    $el.removeClass('is-invalid');
                    $el.next('.select2-container').find('.select2-selection').removeClass('is-invalid');
                    $('[data-error-for="m_jabatan_id"]').text('');
                });
            }

            initJabatanSelect2();

            function resetFieldErrors() {
                $('#formAddSaldoCuti .is-invalid').removeClass('is-invalid');
                $('#formAddSaldoCuti [data-error-for]').text('');

                $('#formAddSaldoCuti .select2-selection').removeClass('is-invalid');
            }

            function setFieldError(field, message) {
                const $input = $('#formAddSaldoCuti [name="' + field + '"]');
                $input.addClass('is-invalid');

                if ($input.is('select')) {
                    $input.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                }

                $('#formAddSaldoCuti [data-error-for="' + field + '"]').text(message);
            }

            $('#formAddSaldoCuti').on('submit', function (e) {
                e.preventDefault();
                resetFieldErrors();

                const formData = $(this).serialize();
                $('#btnSubmit').prop('disabled', true);

                $.ajax({
                    url: storeUrl,
                    method: 'POST',
                    data: formData,
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
                    success: function (res) {
                        $('#btnSubmit').prop('disabled', false);

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res?.message || 'Saldo cuti created successfully',
                            confirmButtonText: 'OK'
                        }).then(() => window.location.href = indexUrl);
                    },
                    error: function (xhr) {
                        $('#btnSubmit').prop('disabled', false);

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON?.errors || {};
                            Object.keys(errors).forEach(key => setFieldError(key, errors[key][0]));

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please check the highlighted fields.',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to create saldo cuti',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
