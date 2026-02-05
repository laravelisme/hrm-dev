@extends('layouts.master')

@section('title', 'Create Domain')
@section('subtitle', 'Tenancy Management')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Create New Tenant Domain</h4>
                        <a href="{{ route('tenancy.domain.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formCreateDomain">
                            @csrf
                            <div class="row g-3">

                                {{-- DOMAIN --}}
                                <div class="col-md-6">
                                    <label class="form-label">Domain <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="domain" placeholder="example.yourapp.com">
                                    <div class="invalid-feedback" data-error-for="domain"></div>
                                </div>

                                {{-- COMPANY NAME --}}
                                <div class="col-md-6">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" class="form-control" name="nama_company" placeholder="PT Maju Jaya">
                                    <div class="invalid-feedback" data-error-for="nama_company"></div>
                                </div>

                                {{-- ADMIN USERNAME --}}
                                <div class="col-md-6">
                                    <label class="form-label">Admin Username</label>
                                    <input type="text" class="form-control" name="username" placeholder="admin">
                                    <div class="invalid-feedback" data-error-for="username"></div>
                                </div>

                                {{-- ADMIN EMAIL --}}
                                <div class="col-md-6">
                                    <label class="form-label">Admin Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="admin@company.com">
                                    <div class="invalid-feedback" data-error-for="email"></div>
                                </div>

                                {{-- ADMIN PASSWORD --}}
                                <div class="col-md-6">
                                    <label class="form-label">Admin Password</label>
                                    <input type="text" class="form-control" name="password" placeholder="Kosongkan untuk auto-generate">
                                    <div class="invalid-feedback" data-error-for="password"></div>
                                    <small class="text-muted">Jika dikosongkan, sistem akan generate password otomatis</small>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('tenancy.domain.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Create Tenant
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

            const storeUrl = @json(route('tenancy.domain.store'));
            const indexUrl = @json(route('tenancy.domain.index'));

            function resetFieldErrors() {
                $('#formCreateDomain .is-invalid').removeClass('is-invalid');
                $('#formCreateDomain [data-error-for]').text('');
            }

            function setFieldError(field, message) {
                const $input = $('#formCreateDomain [name="' + field + '"]');
                $input.addClass('is-invalid');
                $('#formCreateDomain [data-error-for="' + field + '"]').text(message);
            }

            $('#formCreateDomain').on('submit', function (e) {
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
                            text: res?.message || 'Tenant created successfully',
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
                            text: xhr.responseJSON?.message || 'Failed to create tenant',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });

        });
    </script>
@endpush
