@extends('layouts.master')

@section('title', 'Lembur Karyawan - Detail')
@section('subtitle', 'Transaksi')

@section('content')
    <section class="section">
        <div class="card">

            {{-- HEADER --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">Detail Lembur Karyawan</h4>
                    <small class="text-muted">
                        {{ $lembur->nama_karyawan }} • ID {{ $lembur->id }}
                    </small>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.transaksi.lembur-karyawan.index') }}"
                       class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>

                    @if(!in_array($lembur->status, ['APPROVED','REJECTED']))
                        <button id="btnDelete"
                                class="btn btn-danger btn-sm"
                                data-url="{{ route('admin.transaksi.lembur-karyawan.destroy', $lembur->id) }}"
                                data-name="{{ $lembur->nama_karyawan }}">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    @endif
                </div>
            </div>

            {{-- BODY --}}
            <div class="card-body">

                {{-- STATUS BADGE --}}
                <div class="mb-3">
                <span class="badge
                    @if($lembur->status === 'APPROVED') bg-success
                    @elseif($lembur->status === 'REJECTED') bg-danger
                    @elseif($lembur->status === 'APPROVED_PENDING') bg-warning
                    @else bg-secondary @endif">
                    {{ $lembur->status }}
                </span>
                </div>

                {{-- INFO --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div><b>Karyawan</b>: {{ $lembur->nama_karyawan }}</div>
                            <div><b>Company</b>: {{ $lembur->nama_company }}</div>
                            <div><b>Atasan 1</b>: {{ $lembur->nama_atasan1 ?? '-' }}</div>
                            <div><b>Atasan 2</b>: {{ $lembur->nama_atasan2 ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3">
                            <div><b>Tanggal</b>: {{ $lembur->date }}</div>
                            <div><b>Durasi Diajukan</b>: {{ $lembur->durasi_diajukan_menit }} menit</div>
                            <div><b>Created</b>: {{ $lembur->created_at }}</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="border rounded p-3">
                            <b>Catatan</b>
                            <div class="text-muted">{{ $lembur->note ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- ======================= --}}
                {{-- HR APPROVAL SECTION --}}
                {{-- ======================= --}}

                @if($lembur->status === 'APPROVED_PENDING')
                    {{-- FORM HR --}}
                    <div class="border rounded p-3">
                        <h5 class="mb-3">Verifikasi HR</h5>

                        <form id="formApprove"
                              method="POST"
                              action="{{ route('admin.transaksi.lembur-karyawan.approve-lembur', $lembur->id) }}">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label>Status</label>
                                    <select name="status_approval" class="form-select" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="APPROVED">APPROVED</option>
                                        <option value="REJECTED">REJECTED</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Durasi Verifikasi (menit)</label>
                                    <input type="number"
                                           name="durasi_verifikasi_menit"
                                           min="1"
                                           class="form-control"
                                           required>
                                </div>

                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="button"
                                            id="btnApproveSubmit"
                                            class="btn btn-success w-100">
                                        <i class="bi bi-check-circle"></i> Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                @else
                    {{-- RESULT HR --}}
                    <div class="alert
                    @if($lembur->status === 'APPROVED') alert-success
                    @else alert-danger @endif">
                        <h5 class="mb-2">Hasil Verifikasi HR</h5>

                        <ul class="mb-0">
                            <li><b>Status</b>: {{ $lembur->status }}</li>
                            <li><b>Durasi Disetujui</b>: {{ $lembur->durasi_verifikasi_menit }} menit</li>
                            <li><b>HR</b>: {{ $lembur->nama_hr_approval }}</li>
                            <li><b>Tanggal</b>: {{ $lembur->hr_verify_date }}</li>
                        </ul>
                    </div>
                @endif

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {

            const csrf = $('meta[name="csrf-token"]').attr('content');

            /* =====================
             DELETE LEMBUR
            ====================== */
            $('#btnDelete').on('click', function () {
                const url = $(this).data('url');

                Swal.fire({
                    title: 'Hapus lembur?',
                    text: 'Data tidak bisa dikembalikan',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus'
                }).then(res => {
                    if (!res.isConfirmed) return;

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: csrf,
                            _method: 'DELETE'
                        },
                        success: function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil dihapus',
                                timer: 1200,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = "{{ route('admin.transaksi.lembur-karyawan.index') }}";
                            });
                        },
                        error: function () {
                            Swal.fire('Error', 'Gagal menghapus data', 'error');
                        }
                    });
                });
            });

            /* =====================
             APPROVE / REJECT HR
            ====================== */
            $('#btnApproveSubmit').on('click', function () {

                Swal.fire({
                    title: 'Konfirmasi Verifikasi',
                    text: 'Keputusan HR tidak dapat diubah',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, submit'
                }).then(res => {
                    if (!res.isConfirmed) return;

                    const form = $('#formApprove');

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        beforeSend: function () {
                            Swal.fire({
                                title: 'Memproses...',
                                allowOutsideClick: false,
                                didOpen: () => Swal.showLoading()
                            });
                        },
                        success: function (res) {
                            Swal.fire({
                                icon: 'success',
                                title: res.message ?? 'Verifikasi berhasil',
                                timer: 1200,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function (xhr) {
                            let msg = 'Terjadi kesalahan';

                            if (xhr.responseJSON?.message) {
                                msg = xhr.responseJSON.message;
                            }

                            Swal.fire('Gagal', msg, 'error');
                        }
                    });
                });
            });

        });
    </script>
@endpush

