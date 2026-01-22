@extends('layouts.master')

@section('title', 'Edit Jabatan')
@section('subtitle', 'Edit Jabatan')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Jabatan</h4>
                        <a href="{{ route('master-data.jabatan.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formEditJabatan">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Nama Jabatan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name', $jabatan->name) }}">
                                    <div class="invalid-feedback" data-error-for="name"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Kode</label>
                                    <input type="text" class="form-control" name="kode" value="{{ old('kode', $jabatan->kode) }}">
                                    <div class="invalid-feedback" data-error-for="kode"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Level</label>
                                    <input type="number" class="form-control" name="level" value="{{ old('level', $jabatan->level) }}">
                                    <div class="invalid-feedback" data-error-for="level"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Aktif</label>
                                    <select class="form-select" name="is_active">
                                        <option value="1" {{ $jabatan->is_active ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !$jabatan->is_active ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    <div class="invalid-feedback" data-error-for="is_active"></div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('master-data.jabatan.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Update Jabatan
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
            const updateUrl = @json(route('master-data.jabatan.update', $jabatan->id));
            const indexUrl = @json(route('master-data.jabatan.index'));

            function resetFieldErrors() {
                $('#formEditJabatan .is-invalid').removeClass('is-invalid');
                $('#formEditJabatan [data-error-for]').text('');
            }

            function setFieldError(field, message) {
                const $input = $('#formEditJabatan [name="' + field + '"]');
                $input.addClass('is-invalid');
                $('#formEditJabatan [data-error-for="' + field + '"]').text(message);
            }

            $('#formEditJabatan').on('submit', function (e) {
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
                            text: res?.message || 'Jabatan updated successfully',
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
                            text: xhr.responseJSON?.message || 'Failed to update jabatan',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
