@extends('layouts.master')

@section('title', 'Add Department')
@section('subtitle', 'Add Department')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Create New Department</h4>
                        <a href="{{ route('admin.master-data.department.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formAddDepartment">
                            @csrf
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Department Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="department_name" placeholder="HR / IT / Finance">
                                    <div class="invalid-feedback" data-error-for="department_name"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Company <span class="text-danger">*</span></label>
                                    <select class="form-select select2-company" name="company_id">
                                        <option value="">Pilih Company</option>

                                        @if(old('company_id'))
                                            <option value="{{ old('company_id') }}" selected>
                                                {{ old('company_name_label', 'Selected Company') }}
                                            </option>
                                        @endif
                                    </select>
                                    <div class="invalid-feedback" data-error-for="company_id"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Is HR <span class="text-danger">*</span></label>
                                    <select class="form-select" name="is_hr">
                                        <option value="" selected disabled>Pilih</option>
                                        <option value="1">HR</option>
                                        <option value="0">Non HR</option>
                                    </select>
                                    <div class="invalid-feedback" data-error-for="is_hr"></div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('admin.master-data.department.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Save Department
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
            const companyOptionsUrl = @json(route('admin.master-data.department.company-options'));
            const oldCompanyId = @json(old('company_id'));

            $('.select2-company').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih Company',
                allowClear: true,
                ajax: {
                    url: companyOptionsUrl,
                    dataType: 'json',
                    delay: 300,
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
                            pagination: { more: !!(data.pagination && data.pagination.more) }
                        };
                    },
                    cache: true
                }
            });

            if (oldCompanyId) {
                $.ajax({
                    url: companyOptionsUrl,
                    dataType: 'json',
                    data: { q: '', page: 1, perPage: 1, id: oldCompanyId }
                });
            }

            const storeUrl = @json(route('admin.master-data.department.store'));
            const indexUrl = @json(route('admin.master-data.department.index'));

            function resetFieldErrors() {
                $('#formAddDepartment .is-invalid').removeClass('is-invalid');
                $('#formAddDepartment [data-error-for]').text('');
            }

            function setFieldError(field, message) {
                const $input = $('#formAddDepartment [name="' + field + '"]');
                $input.addClass('is-invalid');
                $('#formAddDepartment [data-error-for="' + field + '"]').text(message);
            }

            $('#formAddDepartment').on('submit', function (e) {
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
                            text: res?.message || 'Department created successfully',
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
                            text: xhr.responseJSON?.message || 'Failed to create department',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
