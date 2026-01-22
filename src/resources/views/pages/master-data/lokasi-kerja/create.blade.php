@extends('layouts.master')

@section('title', 'Add Lokasi Kerja')
@section('subtitle', 'Add Lokasi Kerja')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Create New Lokasi Kerja</h4>
                        <a href="{{ route('admin.master-data.lokasi-kerja.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formAddLokasiKerja">
                            @csrf
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" placeholder="Kantor Pusat">
                                    <div class="invalid-feedback" data-error-for="name"></div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('admin.master-data.lokasi-kerja.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Save Lokasi Kerja
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
            const storeUrl = @json(route('admin.master-data.lokasi-kerja.store'));
            const indexUrl = @json(route('admin.master-data.lokasi-kerja.index'));

            function resetFieldErrors() {
                $('#formAddLokasiKerja .is-invalid').removeClass('is-invalid');
                $('#formAddLokasiKerja [data-error-for]').text('');
            }

            function setFieldError(field, message) {
                const $input = $('#formAddLokasiKerja [name="' + field + '"]');
                $input.addClass('is-invalid');
                $('#formAddLokasiKerja [data-error-for="' + field + '"]').text(message);
            }

            $('#formAddLokasiKerja').on('submit', function (e) {
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
                            text: res?.message || 'Lokasi kerja created successfully',
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
                            text: xhr.responseJSON?.message || 'Failed to create lokasi kerja',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
