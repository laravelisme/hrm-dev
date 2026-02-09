@extends('layouts.master')

@section('title', 'Transaksi - Detail Cuti')
@section('subtitle', 'Detail Cuti Karyawan')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex flex-column flex-lg-row justify-content-between gap-2">
                <div>
                    <h4 class="mb-0">Detail Cuti</h4>
                    <p class="text-muted mb-0">
                        {{ $cuti->nama_karyawan ?? '-' }}
                        <span class="ms-2 text-muted">Company: <b>{{ $cuti->nama_perusahaan ?? '-' }}</b></span>
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.transaksi.cuti-karyawan.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small">Status</div>
                            <div class="mt-1">
                            <span class="badge
                                {{ $cuti->status==='APPROVED' ? 'bg-light-success text-dark' :
                                   ($cuti->status==='REJECTED' ? 'bg-light-danger text-dark' :
                                   ($cuti->status==='PENDING_APPROVED' ? 'bg-light-warning text-dark' : 'bg-light-primary text-dark')) }}">
                                {{ $cuti->status }}
                            </span>
                            </div>

                            <div class="text-muted small mt-3">Jenis Cuti</div>
                            <div class="fw-semibold">{{ optional($cuti->jenisCuti)->nama_cuti ?? $cuti->m_jenis_cuti_id }}</div>

                            <div class="text-muted small mt-3">Jumlah Hari</div>
                            <div class="fw-semibold">{{ $cuti->jumlah_hari }}</div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="border rounded p-3 h-100">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="text-muted small">Periode</div>
                                    <div class="fw-semibold">
                                        {{ $cuti->start_date }} → {{ $cuti->end_date }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted small">Tanggal Kembali</div>
                                    <div class="fw-semibold">{{ $cuti->tanggal_kembali ?? '-' }}</div>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="text-muted small">Keperluan</div>
                                    <div class="fw-semibold">{{ $cuti->keperluan }}</div>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="text-muted small">Alamat Selama Cuti</div>
                                    <div class="fw-semibold">{{ $cuti->alamat_selama_cuti ?? '-' }}</div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="text-muted small">No Telp</div>
                                    <div class="fw-semibold">{{ $cuti->no_telp ?? '-' }}</div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="text-muted small">Created At</div>
                                    <div class="fw-semibold">{{ optional($cuti->created_at)->format('Y-m-d H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Approval --}}
                    <div class="col-12">
                        <div class="border rounded p-3">
                            <h5 class="mb-3">Approval</h5>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="text-muted small">Atasan 1</div>
                                    <div class="fw-semibold">{{ $cuti->nama_atasan1 ?? '-' }}</div>
                                    <div class="mt-1">
                                    <span class="badge {{ $cuti->is_approved_atasan1 ? 'bg-light-success text-dark' : 'bg-light-secondary text-dark' }}">
                                        {{ $cuti->is_approved_atasan1 ? 'Approved' : 'Not Approved' }}
                                    </span>
                                    </div>
                                    <div class="text-muted small mt-2">Note</div>
                                    <div>{{ $cuti->note_atasan1 ?? '-' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <div class="text-muted small">Atasan 2</div>
                                    <div class="fw-semibold">{{ $cuti->nama_atasan2 ?? '-' }}</div>
                                    <div class="mt-1">
                                    <span class="badge {{ $cuti->is_approved_atasan2 ? 'bg-light-success text-dark' : 'bg-light-secondary text-dark' }}">
                                        {{ $cuti->is_approved_atasan2 ? 'Approved' : 'Not Approved' }}
                                    </span>
                                    </div>
                                    <div class="text-muted small mt-2">Note</div>
                                    <div>{{ $cuti->note_atasan2 ?? '-' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <div class="text-muted small">HR Verified</div>
                                    <div class="mt-1">
                                    <span class="badge {{ $cuti->is_hr_verified ? 'bg-light-success text-dark' : 'bg-light-secondary text-dark' }}">
                                        {{ $cuti->is_hr_verified ? 'Verified' : 'Not Verified' }}
                                    </span>
                                    </div>
                                    <div class="text-muted small mt-2">Nama HR Approval</div>
                                    <div class="fw-semibold">{{ $cuti->nama_hr_approval ?? '-' }}</div>
                                </div>

                                <div class="col-md-6">
                                    <div class="text-muted small">HR Verify Date</div>
                                    <div class="fw-semibold">{{ optional($cuti->hr_verify_date)->format('Y-m-d') ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($cuti->status === 'PENDING_APPROVED')
                        <button
                            class="btn btn-success btn-sm"
                            id="btnVerifyHr"
                            data-id="{{ $cuti->id }}">
                            <i class="bi bi-check-circle me-1"></i> Verify HR
                        </button>
                    @endif

                    {{-- Saldo info --}}
                    <div class="col-12">
                        <div class="border rounded p-3 bg-light">
                            <h6 class="mb-2">Saldo</h6>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="text-muted small">Saldo Sebelumnya</div>
                                    <div class="fw-semibold">{{ $cuti->saldo_sebelumnya }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted small">Saldo Terpakai</div>
                                    <div class="fw-semibold">{{ $cuti->saldo_terpakai }}</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-muted small">Saldo Sisa</div>
                                    <div class="fw-semibold">{{ $cuti->saldo_sisa }}</div>
                                </div>
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
        $(document).on('click', '#btnVerifyHr', function(){

            let id = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data izin akan diverifikasi oleh HR.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Verifikasi',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: '/transaksi/cuti-karyawan/approve/' + id,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(){

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Cuti berhasil diverifikasi.'
                            }).then(() => {
                                location.reload();
                            });

                        },
                        error: function(){
                            Swal.fire('Gagal', 'Verifikasi gagal dilakukan.', 'error');
                        }
                    });

                }

            });

        });
    </script>
@endpush
