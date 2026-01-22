@extends('layouts.master')

@section('title', 'Edit Company')
@section('subtitle', 'Edit Company')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Company</h4>
                        <a href="{{ route('admin.master-data.company.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formEditCompany">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="company_name"
                                           value="{{ old('company_name', $company->company_name) }}">
                                    <div class="invalid-feedback" data-error-for="company_name"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Level <span class="text-danger">*</span></label>
                                    @php($levelVal = old('level', $company->level))
                                    <select class="form-select" name="level">
                                        <option value="HOLDING" {{ $levelVal === 'HOLDING' ? 'selected' : '' }}>HOLDING</option>
                                        <option value="COMPANY" {{ $levelVal === 'COMPANY' ? 'selected' : '' }}>COMPANY</option>
                                    </select>
                                    <div class="invalid-feedback" data-error-for="level"></div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('admin.master-data.company.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Update Company
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
            const updateUrl = @json(route('admin.master-data.company.update', $company->id));
            const indexUrl  = @json(route('admin.master-data.company.index'));

            function resetFieldErrors() {
                $('#formEditCompany .is-invalid').removeClass('is-invalid');
                $('#formEditCompany [data-error-for]').text('');
            }

            function setFieldError(field, message) {
                const $input = $('#formEditCompany [name="' + field + '"]');
                $input.addClass('is-invalid');
                $('#formEditCompany [data-error-for="' + field + '"]').text(message);
            }

            $('#formEditCompany').on('submit', function (e) {
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
                            text: res?.message || 'Company updated successfully',
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
                            text: xhr.responseJSON?.message || 'Failed to update company',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
