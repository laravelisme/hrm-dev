@extends('layouts.master')

@section('title', 'Edit Grup Jam Kerja')
@section('subtitle', 'Edit Grup Jam Kerja')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Grup Jam Kerja</h4>
                        <a href="{{ route('admin.master-data.grup-jam-kerja.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formEdit">
                            @csrf
                            @method('PUT')

                            <!-- Nama Grup -->
                            <div class="mb-3">
                                <label class="form-label">Nama Grup <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ $grupJamKerja->name }}" placeholder="e.g., Shift Pagi">
                                <div class="invalid-feedback" data-error-for="name"></div>
                            </div>

                            <!-- Tabs -->
                            <ul class="nav nav-tabs" id="shiftTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="default-tab" data-bs-toggle="tab" data-bs-target="#default-panel" type="button">
                                        Default Schedule
                                    </button>
                                </li>
                                @foreach(['monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday', 'sunday' => 'Sunday'] as $key => $label)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="{{ $key }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $key }}-panel" type="button">
                                            {{ $label }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content border border-top-0 p-3" id="shiftTabContent">
                                <!-- Default Schedule Tab -->
                                <div class="tab-pane fade show active" id="default-panel">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Start Time</label>
                                            <input type="time" class="form-control" name="start" value="{{ $grupJamKerja->start }}">
                                            <div class="invalid-feedback" data-error-for="start"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">End Time</label>
                                            <input type="time" class="form-control" name="end" value="{{ $grupJamKerja->end }}">
                                            <div class="invalid-feedback" data-error-for="end"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Min Check-in</label>
                                            <input type="time" class="form-control" name="min_check_in" value="{{ $grupJamKerja->min_check_in }}">
                                            <div class="invalid-feedback" data-error-for="min_check_in"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Max Check-in</label>
                                            <input type="time" class="form-control" name="max_check_in" value="{{ $grupJamKerja->max_check_in }}">
                                            <div class="invalid-feedback" data-error-for="max_check_in"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Min Check-out</label>
                                            <input type="time" class="form-control" name="min_check_out" value="{{ $grupJamKerja->min_check_out }}">
                                            <div class="invalid-feedback" data-error-for="min_check_out"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Max Check-out</label>
                                            <input type="time" class="form-control" name="max_check_out" value="{{ $grupJamKerja->max_check_out }}">
                                            <div class="invalid-feedback" data-error-for="max_check_out"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Per-Day Tabs -->
                                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                                    <div class="tab-pane fade" id="{{ $day }}-panel">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label">Day Type</label>
                                                <select class="form-select" name="{{ $day }}_type">
                                                    <option value="">Not Set</option>
                                                    <option value="WEEKDAY" @selected($grupJamKerja->{$day.'_type'} === 'WEEKDAY')>WEEKDAY</option>
                                                    <option value="WEEKEND" @selected($grupJamKerja->{$day.'_type'} === 'WEEKEND')>WEEKEND</option>
                                                    <option value="FULL" @selected($grupJamKerja->{$day.'_type'} === 'FULL')>FULL</option>
                                                    <option value="OFF" @selected($grupJamKerja->{$day.'_type'} === 'OFF')>OFF</option>
                                                </select>
                                                <div class="invalid-feedback" data-error-for="{{ $day }}_type"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Start Time</label>
                                                <input type="time" class="form-control" name="{{ $day }}_start" value="{{ $grupJamKerja->{$day.'_start'} }}">
                                                <div class="invalid-feedback" data-error-for="{{ $day }}_start"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">End Time</label>
                                                <input type="time" class="form-control" name="{{ $day }}_end" value="{{ $grupJamKerja->{$day.'_end'} }}">
                                                <div class="invalid-feedback" data-error-for="{{ $day }}_end"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Min Check-in</label>
                                                <input type="time" class="form-control" name="{{ $day }}_min_check_in" value="{{ $grupJamKerja->{$day.'_min_check_in'} }}">
                                                <div class="invalid-feedback" data-error-for="{{ $day }}_min_check_in"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Max Check-in</label>
                                                <input type="time" class="form-control" name="{{ $day }}_max_check_in" value="{{ $grupJamKerja->{$day.'_max_check_in'} }}">
                                                <div class="invalid-feedback" data-error-for="{{ $day }}_max_check_in"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Min Check-out</label>
                                                <input type="time" class="form-control" name="{{ $day }}_min_check_out" value="{{ $grupJamKerja->{$day.'_min_check_out'} }}">
                                                <div class="invalid-feedback" data-error-for="{{ $day }}_min_check_out"></div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Max Check-out</label>
                                                <input type="time" class="form-control" name="{{ $day }}_max_check_out" value="{{ $grupJamKerja->{$day.'_max_check_out'} }}">
                                                <div class="invalid-feedback" data-error-for="{{ $day }}_max_check_out"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('admin.master-data.grup-jam-kerja.index') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary" id="btnSubmit">
                                    <i class="bi bi-save me-1"></i> Update Grup Jam Kerja
                                </button>
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
            const updateUrl = @json(route('admin.master-data.grup-jam-kerja.update', $grupJamKerja->id));
            const indexUrl = @json(route('admin.master-data.grup-jam-kerja.index'));

            function resetFieldErrors() {
                $('#formEdit .is-invalid').removeClass('is-invalid');
                $('#formEdit [data-error-for]').text('');
            }

            function setFieldError(field, message) {
                const $input = $('#formEdit [name="' + field + '"]');
                $input.addClass('is-invalid');
                $('#formEdit [data-error-for="' + field + '"]').text(message);
            }

            $('#formEdit').on('submit', function (e) {
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
                            text: res?.message || 'Grup jam kerja updated successfully',
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
                            text: xhr.responseJSON?.message || 'Failed to update grup jam kerja',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
