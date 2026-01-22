@extends('layouts.master')

@section('title', 'Edit Department')
@section('subtitle', 'Edit Department')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Department</h4>
                        <a href="{{ route('admin.master-data.department.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formEditDepartment">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Department Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="department_name"
                                           value="{{ old('department_name', $department->department_name) }}">
                                    <div class="invalid-feedback" data-error-for="department_name"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Company <span class="text-danger">*</span></label>
                                    <select class="form-select select2-company" name="company_id">
                                        <option value="" disabled>Pilih Company</option>
                                        @foreach($companies as $c)
                                            <option value="{{ $c->id }}"
                                                @selected((string)old('company_id', $department->company_id) === (string)$c->id)>
                                                {{ $c->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" data-error-for="company_id"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Is HR <span class="text-danger">*</span></label>
                                    <select class="form-select" name="is_hr">
                                        <option value="1" {{ $department->is_hr === true ? 'selected' : '' }}>HR</option>
                                        <option value="0" {{ $department->is_hr === false ? 'selected' : '' }}>Non HR</option>
                                    </select>
                                    <div class="invalid-feedback" data-error-for="is_hr"></div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('admin.master-data.department.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Update Department
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
            $('.select2-company').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih Company',
                allowClear: true
            });

            const updateUrl = @json(route('admin.master-data.department.update', $department->id));
            const indexUrl  = @json(route('admin.master-data.department.index'));

            function resetFieldErrors() {
                $('#formEditDepartment .is-invalid').removeClass('is-invalid');
                $('#formEditDepartment [data-error-for]').text('');
            }

            function setFieldError(field, message) {
                const $input = $('#formEditDepartment [name="' + field + '"]');
                $input.addClass('is-invalid');
                $('#formEditDepartment [data-error-for="' + field + '"]').text(message);
            }

            $('#formEditDepartment').on('submit', function (e) {
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
                            text: res?.message || 'Department updated successfully',
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
                            text: xhr.responseJSON?.message || 'Failed to update department',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
