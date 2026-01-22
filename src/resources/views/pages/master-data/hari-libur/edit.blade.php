@extends('layouts.master')

@section('title', 'Edit Hari Libur')
@section('subtitle', 'Edit Hari Libur')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Hari Libur</h4>
                        <a href="{{ route('admin.master-data.hari-libur.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formEditHariLibur">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">

                                <div class="col-md-6">
                                    <label class="form-label">Nama Hari Libur <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="hari_libur"
                                           value="{{ old('hari_libur', $hariLibur->hari_libur ?? '') }}">
                                    <div class="invalid-feedback" data-error-for="hari_libur"></div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tanggal_mulai"
                                           value="{{ old('tanggal_mulai', $hariLibur->tanggal_mulai) }}">
                                    <div class="invalid-feedback" data-error-for="tanggal_mulai"></div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tanggal_selesai"
                                           value="{{ old('tanggal_selesai', $hariLibur->tanggal_selesai) }}">
                                    <div class="invalid-feedback" data-error-for="tanggal_selesai"></div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Cuti Bersama <span class="text-danger">*</span></label>
                                    @php($valBersama = (string) old('is_cuti_bersama', (int)($hariLibur->is_cuti_bersama ?? 0)))
                                    <select class="form-select" name="is_cuti_bersama">
                                        <option value="0" @selected($valBersama === '0')>Tidak</option>
                                        <option value="1" @selected($valBersama === '1')>Ya</option>
                                    </select>
                                    <div class="invalid-feedback" data-error-for="is_cuti_bersama"></div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Terapkan disemua perusahaan <span class="text-danger">*</span></label>
                                    @php($valUmum = (string) old('is_umum', (int)($hariLibur->is_umum ?? 1)))
                                    <select class="form-select" name="is_umum">
                                        <option value="1" @selected($valUmum === '1')>Ya</option>
                                        <option value="0" @selected($valUmum === '0')>Tidak</option>
                                    </select>
                                    <div class="invalid-feedback" data-error-for="is_umum"></div>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Repeat (Tahunan) <span class="text-danger">*</span></label>
                                    @php($valRepeat = (string) old('is_repeat', (int)($hariLibur->is_repeat ?? 0)))
                                    <select class="form-select" name="is_repeat">
                                        <option value="0" @selected($valRepeat === '0')>Tidak</option>
                                        <option value="1" @selected($valRepeat === '1')>Ya</option>
                                    </select>
                                    <div class="invalid-feedback" data-error-for="is_repeat"></div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('admin.master-data.hari-libur.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Update Hari Libur
                                    </button>
                                </div>

                            </div>
                        </form>
                        <small class="text-muted d-block mt-3">
                            Tips: jika hanya 1 hari, set tanggal mulai dan tanggal selesai sama.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const updateUrl = @json(route('admin.master-data.hari-libur.update', $hariLibur->id));
            const indexUrl  = @json(route('admin.master-data.hari-libur.index'));

            function resetFieldErrors() {
                $('#formEditHariLibur .is-invalid').removeClass('is-invalid');
                $('#formEditHariLibur [data-error-for]').text('');
            }

            function setFieldError(field, message) {
                const $input = $('#formEditHariLibur [name="' + field + '"]');
                $input.addClass('is-invalid');
                $('#formEditHariLibur [data-error-for="' + field + '"]').text(message);
            }

            $('#formEditHariLibur').on('submit', function (e) {
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
                            text: res?.message || 'Hari libur updated successfully',
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
                            text: xhr.responseJSON?.message || 'Failed to update hari libur',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
