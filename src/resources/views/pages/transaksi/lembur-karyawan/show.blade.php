@extends('layouts.master')

@section('title', 'Transaksi - Detail Lembur Karyawan')
@section('subtitle', 'Detail Lembur Karyawan')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Detail Pengajuan Lembur</h4>
                <a href="{{ route('admin.transaksi.lembur-karyawan.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label text-muted">Nama Karyawan</label>
                        <div class="fw-semibold">{{ $lembur->nama_karyawan }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted">Perusahaan</label>
                        <div class="fw-semibold">{{ $lembur->nama_perusahaan }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted">Tanggal Lembur</label>
                        <div class="fw-semibold">
                            {{ \Carbon\Carbon::parse($lembur->date)->translatedFormat('d F Y') }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted">Durasi Diajukan</label>
                        <div class="fw-semibold">
                            {{ $lembur->durasi_diajukan_menit }} menit
                            ({{ round($lembur->durasi_diajukan_menit / 60, 2) }} jam)
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted">Atasan 1</label>
                        <div class="fw-semibold">{{ $lembur->nama_atasan1 ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted">Atasan 2</label>
                        <div class="fw-semibold">{{ $lembur->nama_atasan2 ?? '-' }}</div>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-muted">Catatan</label>
                        <div class="fw-semibold">{{ $lembur->note ?? '-' }}</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted">Status Pengajuan</label>
                        <div>
                            @if($lembur->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($lembur->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($lembur->status == 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($lembur->status) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted">Dibuat Pada</label>
                        <div class="fw-semibold">
                            {{ $lembur->created_at->translatedFormat('d F Y H:i') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- OPTIONAL: APPROVAL ACTION --}}
        @if($lembur->status === 'pending')
            <div class="card mt-3">
                <div class="card-body d-flex justify-content-end gap-2">
                    <button class="btn btn-danger btnReject" data-id="{{ $lembur->id }}">
                        <i class="bi bi-x-circle"></i> Reject
                    </button>
                    <button class="btn btn-success btnApprove" data-id="{{ $lembur->id }}">
                        <i class="bi bi-check-circle"></i> Approve
                    </button>
                </div>
            </div>
        @endif
    </section>
@endsection


@push('scripts')
    <script>
        $(function () {
            const approveUrl = id => `/admin/transaksi/lembur-karyawan/${id}/approve`;
            const rejectUrl  = id => `/admin/transaksi/lembur-karyawan/${id}/reject`;
            const csrf = $('meta[name="csrf-token"]').attr('content');

            $('.btnApprove').click(function () {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Approve lembur ini?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Approve'
                }).then(result => {
                    if (!result.isConfirmed) return;

                    $.post({
                        url: approveUrl(id),
                        headers: { 'X-CSRF-TOKEN': csrf },
                        success: res => {
                            Swal.fire('Berhasil', res.message, 'success')
                                .then(() => location.reload());
                        },
                        error: err => {
                            Swal.fire('Error', err.responseJSON?.message || 'Terjadi kesalahan', 'error');
                        }
                    });
                });
            });

            $('.btnReject').click(function () {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Reject lembur ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Reject'
                }).then(result => {
                    if (!result.isConfirmed) return;

                    $.post({
                        url: rejectUrl(id),
                        headers: { 'X-CSRF-TOKEN': csrf },
                        success: res => {
                            Swal.fire('Berhasil', res.message, 'success')
                                .then(() => location.reload());
                        },
                        error: err => {
                            Swal.fire('Error', err.responseJSON?.message || 'Terjadi kesalahan', 'error');
                        }
                    });
                });
            });
        });
    </script>
@endpush
