@extends('layouts.master')

@section('title', 'Transaksi - Detail Izin')
@section('subtitle', 'Detail Izin Karyawan')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex flex-column flex-lg-row justify-content-between gap-2">
                <div>
                    <h4 class="mb-0">Detail Izin</h4>
                    <p class="text-muted mb-0">
                        {{ $izin->nama_karyawan ?? '-' }}
                        <span class="ms-2 text-muted">Company: <b>{{ $izin->nama_perusahaan ?? '-' }}</b></span>
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.transaksi.izin-karyawan.index') }}" class="btn btn-light btn-sm">
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
                                @php
                                    $st = $izin->status
                                @endphp
                                    <span class="badge
                                        {{ $st==='APPROVED' ? 'bg-light-success text-dark' :
                                           ($st==='REJECTED' ? 'bg-light-danger text-dark' :
                                           ($st==='PENDING_APPROVED' ? 'bg-light-warning text-dark' : 'bg-light-primary text-dark')) }}">
                                        {{ $st ?: '-' }}
                                    </span>
                            </div>

                            <div class="text-muted small mt-3">Jenis Izin</div>
                            <div class="fw-semibold">{{ optional($izin->jenisIzin)->nama_izin ?? $izin->m_jenis_izin_id }}</div>

                            <div class="text-muted small mt-3">Durasi</div>
                            <div class="fw-semibold">{{ $izin->durasi ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="border rounded p-3 h-100">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="text-muted small">Periode</div>
                                    <div class="fw-semibold">
                                        {{ $izin->start_date ?? '-' }} → {{ $izin->end_date ?? '-' }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="text-muted small">Tanggal Kembali</div>
                                    <div class="fw-semibold">{{ $izin->tanggal_kembali ?? '-' }}</div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="text-muted small">Atasan 1</div>
                                    <div class="fw-semibold">{{ $izin->nama_atasan1 ?? '-' }}</div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="text-muted small">Atasan 2</div>
                                    <div class="fw-semibold">{{ $izin->nama_atasan2 ?? '-' }}</div>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="text-muted small">Keperluan</div>
                                    <div class="fw-semibold" style="white-space: pre-wrap;">{{ $izin->keperluan ?? '-' }}</div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="text-muted small">Created At</div>
                                    <div class="fw-semibold">{{ optional($izin->created_at)->format('Y-m-d H:i') }}</div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="text-muted small">Updated At</div>
                                    <div class="fw-semibold">{{ optional($izin->updated_at)->format('Y-m-d H:i') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($izin->file)
                        @php
                            $ext = strtolower(pathinfo($izin->file, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg','jpeg','png']);
                        @endphp

                        <div class="col-12">
                            <div class="border rounded p-3">
                                <h5 class="mb-3">Lampiran</h5>

                                @if($isImage)
                                    <a href="{{ asset($izin->file) }}" target="_blank">
                                        <img src="{{ asset($izin->file) }}"
                                             alt="Lampiran Izin"
                                             style="max-width:300px; border-radius:8px;">
                                    </a>
                                @else
                                    <a href="{{ asset($izin->file) }}" target="_blank" class="btn btn-primary btn-sm">
                                        <i class="bi bi-file-earmark-pdf"></i> Lihat File
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif


                    {{-- Approval (optional) -> hanya tampil kalau kolomnya ada di tabel --}}
                    @php($attrs = method_exists($izin,'getAttributes') ? $izin->getAttributes() : [])
                    @php($hasApprovalCols =
                        array_key_exists('is_approved_atasan1',$attrs) ||
                        array_key_exists('is_approved_atasan2',$attrs) ||
                        array_key_exists('note_atasan1',$attrs) ||
                        array_key_exists('note_atasan2',$attrs)
                    )

                    @if($hasApprovalCols)
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <h5 class="mb-3">Approval</h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="text-muted small">Atasan 1</div>
                                        <div class="fw-semibold">{{ $izin->nama_atasan1 ?? '-' }}</div>
                                        <div class="mt-1">
                                            <span class="badge {{ ($izin->is_approved_atasan1 ?? 0) ? 'bg-light-success text-dark' : 'bg-light-secondary text-dark' }}">
                                                {{ ($izin->is_approved_atasan1 ?? 0) ? 'Approved' : 'Not Approved' }}
                                            </span>
                                        </div>
                                        <div class="text-muted small mt-2">Note</div>
                                        <div>{{ $izin->note_atasan1 ?? '-' }}</div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="text-muted small">Atasan 2</div>
                                        <div class="fw-semibold">{{ $izin->nama_atasan2 ?? '-' }}</div>
                                        <div class="mt-1">
                                            <span class="badge {{ ($izin->is_approved_atasan2 ?? 0) ? 'bg-light-success text-dark' : 'bg-light-secondary text-dark' }}">
                                                {{ ($izin->is_approved_atasan2 ?? 0) ? 'Approved' : 'Not Approved' }}
                                            </span>
                                        </div>
                                        <div class="text-muted small mt-2">Note</div>
                                        <div>{{ $izin->note_atasan2 ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($izin->status === 'PENDING_APPROVED')
                            <button
                                class="btn btn-success btn-sm"
                                id="btnVerifyHr"
                                data-id="{{ $izin->id }}">
                                <i class="bi bi-check-circle me-1"></i> Verify HR
                            </button>
                        @endif
                    @endif

                    @if($izin->hr_verify_date || $izin->nama_hr_approval)
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <h5 class="mb-3">HR Verification</h5>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="text-muted small">Verified By</div>
                                        <div class="fw-semibold">
                                            {{ $izin->nama_hr_approval ?? '-' }}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="text-muted small">Verify Date</div>
                                        <div class="fw-semibold">
                                            {{ $izin->hr_verify_date ?? '-' }}
                                        </div>
                                    </div>

                                    <div class="col-12 mt-2">
                                        <span class="badge bg-light-success text-dark">
                                            HR Verified
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

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
                        url: '/transaksi/izin-karyawan/approve/' + id,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(){

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Izin berhasil diverifikasi.'
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
