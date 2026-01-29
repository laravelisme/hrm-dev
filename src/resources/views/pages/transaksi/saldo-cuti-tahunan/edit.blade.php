@extends('layouts.master')

@section('title', 'Edit Saldo Cuti Tahunan')
@section('subtitle', 'Edit Saldo Cuti Tahunan')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">Edit Saldo Cuti Tahunan</h4>
                            <div class="text-muted small">
                                {{ $saldoCuti->nama_karyawan }} • Tahun: <b>{{ $saldoCuti->tahun }}</b>
                            </div>
                        </div>

                        <a href="{{ route('admin.transaksi.saldo-cuti-tahunan.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formEditSaldoCutiTahunan">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Karyawan</label>
                                    <input type="text" class="form-control" value="{{ $saldoCuti->nama_karyawan }}" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Tahun</label>
                                    <input type="text" class="form-control" value="{{ $saldoCuti->tahun }}" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Jabatan</label>
                                    <input type="text" class="form-control" value="{{ $saldoCuti->nama_jabatan ?? '-' }}" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Saldo <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="saldo" min="0" step="1"
                                           value="{{ old('saldo', $saldoCuti->saldo) }}">
                                    <div class="invalid-feedback" data-error-for="saldo"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Sisa Saldo <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="sisa_saldo" min="0" step="1"
                                           value="{{ old('sisa_saldo', $saldoCuti->sisa_saldo) }}">
                                    <div class="invalid-feedback" data-error-for="sisa_saldo"></div>
                                </div>

                                <div class="col-12">
                                    <div class="border rounded p-3 bg-light">
                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <div class="text-muted small">Company</div>
                                                <div class="fw-semibold">{{ $saldoCuti->nama_perusahaan ?? '-' }}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-muted small">Department</div>
                                                <div class="fw-semibold">{{ $saldoCuti->nama_department ?? '-' }}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-muted small">Updated At</div>
                                                <div class="fw-semibold">{{ optional($saldoCuti->updated_at)->format('Y-m-d H:i') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('admin.transaksi.saldo-cuti-tahunan.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Update
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
            const updateUrl = @json(route('admin.transaksi.saldo-cuti-tahunan.update', $saldoCuti->id));
            const indexUrl  = @json(route('admin.transaksi.saldo-cuti-tahunan.index'));

            function resetFieldErrors() {
                $('#formEditSaldoCutiTahunan .is-invalid').removeClass('is-invalid');
                $('#formEditSaldoCutiTahunan [data-error-for]').text('');
            }

            function setFieldError(field, message) {
                const $input = $('#formEditSaldoCutiTahunan [name="' + field + '"]');
                $input.addClass('is-invalid');
                $('#formEditSaldoCutiTahunan [data-error-for="' + field + '"]').text(message);
            }

            $('#formEditSaldoCutiTahunan').on('submit', function (e) {
                e.preventDefault();
                resetFieldErrors();

                const formData = $(this).serialize();
                $('#btnSubmit').prop('disabled', true);

                $.ajax({
                    url: updateUrl,
                    method: 'POST',
                    data: formData,
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
                    success: function (res) {
                        $('#btnSubmit').prop('disabled', false);

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res?.message || 'Saldo cuti tahunan updated successfully',
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
                            text: xhr.responseJSON?.message || 'Failed to update saldo cuti tahunan',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
