@extends('layouts.master')

@section('title', 'Lembur Karyawan - Detail')
@section('subtitle', 'Transaksi')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                <div>
                    <h4 class="mb-0">Detail Lembur Karyawan</h4>
                    <p class="text-muted mb-0">
                        {{ $lembur->nama_karyawan ?? optional($lembur->karyawan)->nama_karyawan ?? '-' }}
                        <span class="ms-2 text-muted small">
                            ID Lembur: <span class="font-monospace">{{ $lembur->id }}</span>
                        </span>
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.transaksi.lembur-karyawan.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>

                    <button type="button"
                            class="btn btn-danger btn-sm"
                            id="btnDelete"
                            data-url="{{ route('admin.transaksi.lembur-karyawan.destroy', $lembur->id) }}"
                            data-name="{{ $lembur->nama_karyawan ?? 'lembur ini' }}">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </div>
            </div>

            <div class="card-body">
                {{-- Badges --}}
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-light-primary text-dark">
                        Status: {{ $lembur->status ?? '-' }}
                    </span>
                    <span class="badge bg-light-secondary text-dark">
                        Company: {{ $lembur->nama_company ?? '-' }}
                    </span>
                    <span class="badge bg-light-secondary text-dark">
                        Tanggal: {{ $lembur->date ?? '-' }}
                    </span>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small">Karyawan</div>
                            <div class="fw-semibold">
                                {{ $lembur->nama_karyawan ?? optional($lembur->karyawan)->nama_karyawan ?? '-' }}
                            </div>

                            <div class="text-muted small mt-2">Company</div>
                            <div class="fw-semibold">{{ $lembur->nama_company ?? '-' }}</div>

                            <div class="text-muted small mt-2">Atasan 1</div>
                            <div class="fw-semibold">
                                {{ $lembur->nama_atasan1 ?? '-' }}
                                @if(!empty($lembur->atasan1_id))
                                    <span class="text-muted small">(ID: {{ $lembur->atasan1_id }})</span>
                                @endif
                            </div>

                            <div class="text-muted small mt-2">Atasan 2</div>
                            <div class="fw-semibold">
                                {{ $lembur->nama_atasan2 ?? '-' }}
                                @if(!empty($lembur->atasan2_id))
                                    <span class="text-muted small">(ID: {{ $lembur->atasan2_id }})</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small">Tanggal Lembur</div>
                            <div class="fw-semibold">{{ $lembur->date ?? '-' }}</div>

                            <div class="text-muted small mt-2">Durasi Diajukan</div>
                            <div class="fw-semibold">
                                {{ (int)($lembur->durasi_diajukan_menit ?? 0) }} menit
                            </div>

                            <div class="text-muted small mt-2">Created At</div>
                            <div class="text-muted small">{{ optional($lembur->created_at)->format('Y-m-d H:i') }}</div>

                            <div class="text-muted small mt-2">Updated At</div>
                            <div class="text-muted small">{{ optional($lembur->updated_at)->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="border rounded p-3">
                            <h5 class="mb-2">Catatan</h5>
                            <div class="text-muted" style="white-space: pre-wrap;">
                                {{ $lembur->note ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const csrf = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();
            const indexUrl = @json(route('admin.transaksi.lembur-karyawan.index'));

            $('#btnDelete').on('click', function () {
                const url  = $(this).data('url');
                const name = $(this).data('name') || 'lembur ini';

                Swal.fire({
                    title: 'Delete lembur?',
                    html: `Yakin hapus lembur <b>${name}</b>?<br><small class="text-muted">Data yang dihapus tidak bisa dikembalikan.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#d33'
                }).then((r) => {
                    if (!r.isConfirmed) return;

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: { _token: csrf, _method: 'DELETE' },
                        success: function (res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: res?.message || 'Lembur deleted successfully',
                                confirmButtonText: 'OK'
                            }).then(() => window.location.href = indexUrl);
                        },
                        error: function (xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Failed to delete lembur',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                });
            });
        });
    </script>
@endpush
