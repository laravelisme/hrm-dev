@extends('layouts.master')

@section('title', 'Create Domain')
@section('subtitle', 'Tenancy Management')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Create New Tenant Domain</h4>
                        <a href="{{ route('tenancy.domain.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formCreateDomain" enctype="multipart/form-data">
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

                                {{-- USERNAME --}}
                                <div class="col-md-6">
                                    <label class="form-label">Admin Username</label>
                                    <input type="text" class="form-control" name="username" placeholder="admin">
                                    <div class="invalid-feedback" data-error-for="username"></div>
                                </div>

                                {{-- EMAIL --}}
                                <div class="col-md-6">
                                    <label class="form-label">Admin Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="admin@company.com">
                                    <div class="invalid-feedback" data-error-for="email"></div>
                                </div>

                                {{-- PASSWORD --}}
                                <div class="col-md-6">
                                    <label class="form-label">Admin Password</label>
                                    <input type="text" class="form-control" name="password" placeholder="Kosongkan untuk auto-generate">
                                    <small class="text-muted">Jika kosong, sistem akan generate otomatis</small>
                                    <div class="invalid-feedback" data-error-for="password"></div>
                                </div>

                                {{-- LOGO --}}
                                <div class="col-md-4">
                                    <label class="form-label">Company Logo</label>
                                    <input type="file" class="form-control" name="logo" accept="image/*">
                                    <div class="invalid-feedback" data-error-for="logo"></div>
                                </div>

                                {{-- BACKGROUND --}}
                                <div class="col-md-4">
                                    <label class="form-label">Login Background</label>
                                    <input type="file" class="form-control" name="background" accept="image/*">
                                    <div class="invalid-feedback" data-error-for="background"></div>
                                </div>

                                {{-- FAVICON --}}
                                <div class="col-md-4">
                                    <label class="form-label">Favicon</label>
                                    <input type="file" class="form-control" name="favicon" accept="image/*">
                                    <div class="invalid-feedback" data-error-for="favicon"></div>
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
                const input = $('#formCreateDomain [name="' + field + '"]');
                input.addClass('is-invalid');
                $('#formCreateDomain [data-error-for="' + field + '"]').text(message);
            }

            $('#formCreateDomain').on('submit', function (e) {
                e.preventDefault();
                resetFieldErrors();

                let formData = new FormData(this);

                $('#btnSubmit').prop('disabled', true);

                $.ajax({
                    url: storeUrl,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },

                    success: function (res) {
                        $('#btnSubmit').prop('disabled', false);

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res?.message || 'Tenant created successfully'
                        }).then(() => window.location.href = indexUrl);
                    },

                    error: function (xhr) {
                        $('#btnSubmit').prop('disabled', false);

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors || {};
                            Object.keys(errors).forEach(key => setFieldError(key, errors[key][0]));

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please check the highlighted fields.'
                            });
                            return;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to create tenant'
                        });
                    }
                });
            });

        });
    </script>
@endpush
