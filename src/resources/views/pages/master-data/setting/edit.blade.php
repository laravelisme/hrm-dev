@extends('layouts.master')

@section('title', 'Edit Setting')
@section('subtitle', 'Edit Setting')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Setting</h4>
                        <a href="{{ route('admin.master-data.setting.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formEditSetting">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name"
                                           value="{{ old('name', $setting->name) }}">
                                    <div class="invalid-feedback" data-error-for="name"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Kode <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="kode"
                                           value="{{ old('kode', $setting->kode) }}">
                                    <div class="invalid-feedback" data-error-for="kode"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Value (1 - 100) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="val" min="1" max="100" step="1"
                                           value="{{ old('val', $setting->val) }}">
                                    <div class="invalid-feedback" data-error-for="val"></div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('admin.master-data.setting.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Update Setting
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
            const updateUrl = @json(route('admin.master-data.setting.update', $setting->id));
            const indexUrl  = @json(route('admin.master-data.setting.index'));

            function resetFieldErrors() {
                $('#formEditSetting .is-invalid').removeClass('is-invalid');
                $('#formEditSetting [data-error-for]').text('');
            }

            function setFieldError(field, message) {
                const $input = $('#formEditSetting [name="' + field + '"]');
                $input.addClass('is-invalid');
                $('#formEditSetting [data-error-for="' + field + '"]').text(message);
            }

            $('#formEditSetting').on('submit', function (e) {
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
                            text: res?.message || 'Setting updated successfully',
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
                            text: xhr.responseJSON?.message || 'Failed to update setting',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
