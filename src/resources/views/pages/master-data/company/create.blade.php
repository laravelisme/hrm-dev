@extends('layouts.master')

@section('title', 'Add Company')
@section('subtitle', 'Add Company')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Create New Company</h4>
                        <a href="{{ route('admin.master-data.company.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formAddCompany">
                            @csrf
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="company_name" placeholder="PT Contoh Sejahtera">
                                    <div class="invalid-feedback" data-error-for="company_name"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Level <span class="text-danger">*</span></label>
                                    <select class="form-select" name="level">
                                        <option value="" selected disabled>Pilih Level</option>
                                        <option value="HOLDING">HOLDING</option>
                                        <option value="COMPANY">COMPANY</option>
                                    </select>
                                    <div class="invalid-feedback" data-error-for="level"></div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('admin.master-data.company.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Save Company
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
            const storeUrl = @json(route('admin.master-data.company.store'));
            const indexUrl = @json(route('admin.master-data.company.index'));

            function resetFieldErrors() {
                $('#formAddCompany .is-invalid').removeClass('is-invalid');
                $('#formAddCompany [data-error-for]').text('');
            }

            function setFieldError(field, message) {
                const $input = $('#formAddCompany [name="' + field + '"]');
                $input.addClass('is-invalid');
                $('#formAddCompany [data-error-for="' + field + '"]').text(message);
            }

            $('#formAddCompany').on('submit', function (e) {
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
                            text: res?.message || 'Company created successfully',
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
                            text: xhr.responseJSON?.message || 'Failed to create company',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
