@extends('layouts.master')

@section('title', 'Tambah Surat Peringatan')
@section('subtitle', 'Transaksi')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Create Surat Peringatan</h4>
                        <a href="{{ route('admin.transaksi.surat-peringatan.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formAddSp" enctype="multipart/form-data">
                            @csrf

                            {{-- required hidden --}}
                            <input type="hidden" name="nama_karyawan" id="nama_karyawan">
                            <input type="hidden" name="m_company_id" id="m_company_id">
                            <input type="hidden" name="nama_perusahaan" id="nama_perusahaan">
                            <input type="hidden" name="atasan_id" id="atasan_id">
                            <input type="hidden" name="nama_atasan" id="nama_atasan">

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Karyawan <span class="text-danger">*</span></label>
                                    <select id="m_karyawan_id" name="m_karyawan_id" class="form-select"></select>
                                    <div class="invalid-feedback d-block" data-error-for="m_karyawan_id"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Company <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="company_readonly" value="" readonly>
                                    <div class="invalid-feedback d-block" data-error-for="m_company_id"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Atasan</label>
                                    <input type="text" class="form-control" id="atasan_readonly" value="" readonly>
                                    <div class="form-text">Akan terisi otomatis setelah memilih karyawan.</div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Jenis SP <span class="text-danger">*</span></label>
                                    <select id="m_jenis_sp_id" name="m_jenis_sp_id" class="form-select"></select>
                                    <div class="invalid-feedback d-block" data-error-for="m_jenis_sp_id"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Nomor</label>
                                    <input type="text" class="form-control" name="nomor" placeholder="Nomor surat...">
                                    <div class="invalid-feedback" data-error-for="nomor"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Surat</label>
                                    <input type="date" class="form-control" name="tanggal_surat" id="tanggal_surat">
                                    <div class="invalid-feedback" data-error-for="tanggal_surat"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Tanggal Start <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tanggal_start" id="tanggal_start">
                                    <div class="invalid-feedback" data-error-for="tanggal_start"></div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Tanggal End <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="tanggal_end" id="tanggal_end">
                                    <div class="invalid-feedback" data-error-for="tanggal_end"></div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Catatan Atasan</label>
                                    <input type="text" class="form-control" name="atasan_note" placeholder="Opsional...">
                                    <div class="invalid-feedback" data-error-for="atasan_note"></div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">File Surat (PDF/JPG/PNG)</label>
                                    <input type="file" class="form-control" name="file_surat" accept=".pdf,.jpg,.jpeg,.png">
                                    <div class="invalid-feedback" data-error-for="file_surat"></div>
                                </div>

                                {{-- Details --}}
                                <div class="col-12">
                                    <div class="border rounded p-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h5 class="mb-0">Detail SP</h5>
                                            <button type="button" class="btn btn-light btn-sm" id="btnAddDetail">
                                                <i class="bi bi-plus-lg me-1"></i> Tambah Detail
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-sm align-middle mb-0" id="detailsTable">
                                                <thead>
                                                <tr>
                                                    <th style="width: 200px;">Jenis</th>
                                                    <th>Keterangan</th>
                                                    <th style="width: 240px;">File Pendukung</th>
                                                    <th style="width: 60px;"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                {{-- default 1 row --}}
                                                <tr data-idx="0">
                                                    <td>
                                                        <input type="text" class="form-control" name="details[0][jenis]" placeholder="Jenis...">
                                                        <div class="invalid-feedback d-block" data-error-for="details.0.jenis"></div>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control" name="details[0][keterangan]" placeholder="Keterangan...">
                                                        <div class="invalid-feedback d-block" data-error-for="details.0.keterangan"></div>
                                                    </td>
                                                    <td>
                                                        <input type="file" class="form-control" name="details[0][file_pendukung]" accept=".pdf,.jpg,.jpeg,.png">
                                                        <div class="invalid-feedback d-block" data-error-for="details.0.file_pendukung"></div>
                                                    </td>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-sm btn-light btnRemoveDetail" disabled>
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="text-muted small mt-2">
                                            *Detail bersifat opsional. Kalau tidak perlu, biarkan 1 baris kosong (atau hapus dengan tombol trash setelah tambah row).
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end gap-2 mt-3">
                                    <a href="{{ route('admin.transaksi.surat-peringatan.index') }}" class="btn btn-light">Cancel</a>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="bi bi-save me-1"></i> Save SP
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
            const storeUrl = @json(route('admin.transaksi.surat-peringatan.store'));
            const indexUrl = @json(route('admin.transaksi.surat-peringatan.index'));

            const karyawanOptionsUrl = @json(route('admin.transaksi.surat-peringatan.karyawan-options'));
            const jenisOptionsUrl    = @json(route('admin.transaksi.surat-peringatan.jenis-sp-options'));
            const karyawanDetailBase = @json(route('admin.transaksi.surat-peringatan.karyawan-detail', ['id' => '__ID__']));

            function resetFieldErrors() {
                $('#formAddSp .is-invalid').removeClass('is-invalid');
                $('#formAddSp [data-error-for]').text('');
                $('#formAddSp .select2-selection').removeClass('is-invalid');
            }

            function setFieldError(field, message) {
                // field bisa "details.0.jenis"
                const $err = $('#formAddSp [data-error-for="' + field + '"]');
                if ($err.length) $err.text(message);

                // tandai input kalau ketemu
                const name = field.includes('.') ? field.replace(/\.(\d+)\./, '[$1][').replace(/\./g, ']') + ']' : field;
                const $input = $('#formAddSp [name="' + name + '"]');
                if ($input.length) {
                    $input.addClass('is-invalid');
                    if ($input.is('select')) $input.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                }
            }

            function initSelect2Ajax($el, url, placeholder) {
                $el.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder,
                    allowClear: true,
                    ajax: {
                        url,
                        dataType: 'json',
                        delay: 250,
                        data: (params) => ({ q: params.term || '', page: params.page || 1, perPage: 20 }),
                        processResults: (data) => ({
                            results: data.results || [],
                            pagination: { more: data.pagination?.more === true }
                        }),
                        cache: true
                    }
                });
            }

            initSelect2Ajax($('#m_karyawan_id'), karyawanOptionsUrl, 'Pilih Karyawan...');
            initSelect2Ajax($('#m_jenis_sp_id'), jenisOptionsUrl, 'Pilih Jenis SP...');

            function clearAuto() {
                $('#nama_karyawan').val('');
                $('#m_company_id').val('');
                $('#nama_perusahaan').val('');
                $('#company_readonly').val('');

                $('#atasan_id').val('');
                $('#nama_atasan').val('');
                $('#atasan_readonly').val('');
            }

            $('#m_karyawan_id').on('select2:select', function (e) {
                const id = e.params.data?.id;
                const label = String(e.params.data?.text || '').trim();

                clearAuto();
                $('#nama_karyawan').val(label);

                if (!id) return;

                const url = karyawanDetailBase.replace('__ID__', id);

                $.get(url).done(function (d) {
                    $('#m_company_id').val(d.m_company_id || '');
                    $('#nama_perusahaan').val(d.nama_perusahaan || '');
                    $('#company_readonly').val(d.nama_perusahaan || '-');

                    $('#atasan_id').val(d.atasan_id || '');
                    $('#nama_atasan').val(d.nama_atasan || '');
                    $('#atasan_readonly').val(d.nama_atasan || '-');
                }).fail(function () {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal mengambil detail karyawan' });
                });
            });

            $('#m_karyawan_id').on('select2:unselect', clearAuto);

            // date guard
            function syncDateMin() {
                const start = $('#tanggal_start').val();
                if (start) $('#tanggal_end').attr('min', start);
            }
            $('#tanggal_start').on('change', syncDateMin);

            // detail rows
            function refreshRemoveButtons() {
                const $rows = $('#detailsTable tbody tr');
                $rows.find('.btnRemoveDetail').prop('disabled', $rows.length <= 1);
            }

            $('#btnAddDetail').on('click', function () {
                const $tbody = $('#detailsTable tbody');
                const lastIdx = parseInt($tbody.find('tr:last').data('idx') || '0', 10);
                const idx = lastIdx + 1;

                const row = `
                    <tr data-idx="${idx}">
                        <td>
                            <input type="text" class="form-control" name="details[${idx}][jenis]" placeholder="Jenis...">
                            <div class="invalid-feedback d-block" data-error-for="details.${idx}.jenis"></div>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="details[${idx}][keterangan]" placeholder="Keterangan...">
                            <div class="invalid-feedback d-block" data-error-for="details.${idx}.keterangan"></div>
                        </td>
                        <td>
                            <input type="file" class="form-control" name="details[${idx}][file_pendukung]" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="invalid-feedback d-block" data-error-for="details.${idx}.file_pendukung"></div>
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-light btnRemoveDetail"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                `;
                $tbody.append(row);
                refreshRemoveButtons();
            });

            $(document).on('click', '.btnRemoveDetail', function () {
                $(this).closest('tr').remove();
                refreshRemoveButtons();
            });

            refreshRemoveButtons();

            // submit (FormData because file upload)
            $('#formAddSp').on('submit', function (e) {
                e.preventDefault();
                resetFieldErrors();

                if (!$('#m_company_id').val()) {
                    Swal.fire({ icon:'warning', title:'Company kosong', text:'Company belum terisi. Pilih karyawan yang valid.' });
                    return;
                }

                const fd = new FormData(this);
                $('#btnSubmit').prop('disabled', true);

                $.ajax({
                    url: storeUrl,
                    method: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
                    success: function (res) {
                        $('#btnSubmit').prop('disabled', false);
                        Swal.fire({ icon:'success', title:'Success', text: res?.message || 'SP created' })
                            .then(() => window.location.href = indexUrl);
                    },
                    error: function (xhr) {
                        $('#btnSubmit').prop('disabled', false);

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON?.errors || {};
                            Object.keys(errors).forEach(k => setFieldError(k, errors[k][0]));
                            Swal.fire({ icon:'error', title:'Validation Error', text:'Please check fields.' });
                            return;
                        }

                        Swal.fire({ icon:'error', title:'Error', text: xhr.responseJSON?.message || 'Failed to create SP' });
                    }
                });
            });
        });
    </script>
@endpush
