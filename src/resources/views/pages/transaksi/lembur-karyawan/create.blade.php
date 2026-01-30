@extends('layouts.master')

@section('title', 'Transaksi - Tambah Lembur Karyawan')
@section('subtitle', 'Tambah Lembur Karyawan')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Tambah Lembur Karyawan</h4>
                <a href="{{ route('admin.transaksi.lembur-karyawan.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>

            <div class="card-body">
                <form id="formAddLembur">
                    @csrf

                    {{-- Hidden auto-filled --}}
                    <input type="hidden" name="nama_karyawan" id="nama_karyawan">
                    <input type="hidden" name="m_company_id" id="m_company_id">
                    <input type="hidden" name="nama_perusahaan" id="nama_perusahaan">
                    <input type="hidden" name="atasan1_id" id="atasan1_id">
                    <input type="hidden" name="atasan2_id" id="atasan2_id">
                    <input type="hidden" name="nama_atasan1" id="nama_atasan1">
                    <input type="hidden" name="nama_atasan2" id="nama_atasan2">

                    <div class="row g-3">

                        {{-- Karyawan --}}
                        <div class="col-md-6">
                            <label class="form-label">Karyawan</label>
                            <select id="m_karyawan_id" name="m_karyawan_id" class="form-select"></select>
                            <div class="invalid-feedback" data-field="m_karyawan_id"></div>
                        </div>

                        {{-- Company (readonly) --}}
                        <div class="col-md-6">
                            <label class="form-label">Company</label>
                            <input type="text" id="company_readonly" class="form-control" readonly>
                        </div>

                        {{-- Tanggal --}}
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Lembur</label>
                            <input type="date" name="date" class="form-control">
                            <div class="invalid-feedback" data-field="date"></div>
                        </div>

                        {{-- Durasi --}}
                        <div class="col-md-6">
                            <label class="form-label">Durasi (menit)</label>
                            <input type="number" name="durasi_diajukan_menit" class="form-control" min="1">
                            <div class="invalid-feedback" data-field="durasi_diajukan_menit"></div>
                        </div>

                        {{-- Note --}}
                        <div class="col-12">
                            <label class="form-label">Catatan</label>
                            <textarea name="note" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Simpan Lembur
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection


@push('scripts')
    <script>
        $(function () {
            const storeUrl = @json(route('admin.transaksi.lembur-karyawan.store'));
            const indexUrl = @json(route('admin.transaksi.lembur-karyawan.index'));
            const csrf = $('meta[name="csrf-token"]').attr('content');

            // ===== Select2 Karyawan =====
            $('#m_karyawan_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Pilih Karyawan...',
                ajax: {
                    url: @json(route('admin.transaksi.lembur-karyawan.karyawan-options')),
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ q: params.term || '', page: params.page || 1 }),
                    processResults: (data) => ({
                        results: data.results || []
                    })
                }
            });

            // ===== Auto fill data saat karyawan dipilih =====
            $('#m_karyawan_id').on('select2:select', function (e) {
                const d = e.params.data;

                $('#nama_karyawan').val(d.nama);
                $('#company_readonly').val(d.company_nama);
                $('#m_company_id').val(d.company_id);
                $('#nama_perusahaan').val(d.company_nama);

                $('#atasan1_id').val(d.atasan1_id);
                $('#atasan2_id').val(d.atasan2_id);
                $('#nama_atasan1').val(d.atasan1_nama);
                $('#nama_atasan2').val(d.atasan2_nama);
            });

            // ===== Submit AJAX =====
            $('#formAddLembur').on('submit', function (e) {
                e.preventDefault();

                const $btn = $(this).find('button[type="submit"]');
                $btn.prop('disabled', true);

                $('.invalid-feedback').text('');
                $('.form-control, .form-select').removeClass('is-invalid');

                $.ajax({
                    url: storeUrl,
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: { 'X-CSRF-TOKEN': csrf },
                    success: function (res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message || 'Lembur berhasil disimpan'
                        }).then(() => window.location.href = indexUrl);
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            for (let field in errors) {
                                $(`[name="${field}"]`).addClass('is-invalid');
                                $(`.invalid-feedback[data-field="${field}"]`).text(errors[field][0]);
                            }
                        } else {
                            Swal.fire('Error', xhr.responseJSON?.message || 'Terjadi kesalahan', 'error');
                        }
                    },
                    complete: function () {
                        $btn.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
