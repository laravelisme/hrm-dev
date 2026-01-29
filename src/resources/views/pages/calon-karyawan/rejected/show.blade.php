@extends('layouts.master')

@section('title', 'Calon Karyawan - Detail')
@section('subtitle', 'Rejected')

@section('content')
    <section class="section">
        <div class="row g-3">

            {{-- LEFT: Summary --}}
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Ringkasan</h4>
                        <a href="{{ route('admin.calon-karyawan.rejected.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-light-danger d-flex align-items-center justify-content-center"
                                 style="width: 44px; height: 44px;">
                                <i class="bi bi-person fs-4"></i>
                            </div>

                            <div class="flex-grow-1">
                                <div class="fw-semibold fs-5 mb-0">{{ $calonKaryawan->nama_lengkap }}</div>
                                <div class="text-muted small">
                                    NIK: <span class="font-monospace" id="nikText">{{ $calonKaryawan->nik }}</span>
                                    <button type="button" class="btn btn-sm btn-light py-0 px-2 ms-1" id="btnCopyNik"
                                            title="Copy NIK">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="small text-muted">Company</div>
                        <div class="fw-semibold">{{ $calonKaryawan->company_name }}</div>

                        <div class="small text-muted mt-2">Department</div>
                        <div class="fw-semibold">{{ $calonKaryawan->department_name }}</div>

                        <div class="small text-muted mt-2">Status</div>
                        <div>
                            @php
                                $status = optional($calonKaryawan->latestStatusRecruitment)->status
                            @endphp
                            <span class="badge bg-light-danger text-dark">
                                {{ $status ?: '-' }}
                            </span>
                        </div>

                        <div class="small text-muted mt-3">Submitted At</div>
                        <div class="text-muted small">{{ optional($calonKaryawan->created_at)->format('Y-m-d H:i') }}</div>

                        <div class="small text-muted mt-1">Updated At</div>
                        <div class="text-muted small">{{ optional($calonKaryawan->updated_at)->format('Y-m-d H:i') }}</div>

                        <hr class="my-3">

                        <div class="d-flex flex-column gap-2">
                            {{-- Kembali ke SHORTLIST_ADMIN --}}
                            <button type="button"
                                    class="btn btn-secondary btn-sm btn-update-status"
                                    data-url="{{ route('admin.calon-karyawan.update-status-recruitment', $calonKaryawan->id) }}"
                                    data-name="{{ $calonKaryawan->nama_lengkap }}"
                                    data-status="SHORTLIST_ADMIN">
                                <i class="bi bi-arrow-return-left me-1"></i> Kembalikan ke Shortlist Admin
                            </button>

                            {{-- Delete --}}
                            <button type="button"
                                    class="btn btn-danger btn-sm btn-delete-calon"
                                    data-url="{{ route('admin.calon-karyawan.rejected.destroy', $calonKaryawan->id) }}"
                                    data-name="{{ $calonKaryawan->nama_lengkap }}"
                                    data-nik="{{ $calonKaryawan->nik }}">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Quick Contact --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="mb-0">Kontak</h4>
                    </div>
                    <div class="card-body">
                        <div class="small text-muted">No. Telp</div>
                        <div class="fw-semibold">
                            <span id="telpText">{{ $calonKaryawan->no_telp }}</span>
                            <button type="button" class="btn btn-sm btn-light py-0 px-2 ms-1" id="btnCopyTelp"
                                    title="Copy No. Telp">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>

                        <div class="small text-muted mt-2">Nama Panggilan</div>
                        <div class="fw-semibold">{{ $calonKaryawan->nama_panggilan }}</div>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Detail Sections --}}
            <div class="col-lg-8">
                {{-- Section: Pendaftaran --}}
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">1) Pendaftaran</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="small text-muted">Company</div>
                                <div class="fw-semibold">{{ $calonKaryawan->company_name }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Department</div>
                                <div class="fw-semibold">{{ $calonKaryawan->department_name }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section: Data Diri --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="mb-0">2) Data Diri</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="small text-muted">Nama Lengkap</div>
                                <div class="fw-semibold">{{ $calonKaryawan->nama_lengkap }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Nama Panggilan</div>
                                <div class="fw-semibold">{{ $calonKaryawan->nama_panggilan }}</div>
                            </div>

                            <div class="col-md-6">
                                <div class="small text-muted">NIK</div>
                                <div class="fw-semibold font-monospace">{{ $calonKaryawan->nik }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">No. Telp</div>
                                <div class="fw-semibold">{{ $calonKaryawan->no_telp }}</div>
                            </div>

                            <div class="col-md-6">
                                <div class="small text-muted">Jenis Kelamin</div>
                                <span class="badge bg-light-secondary text-dark">{{ $calonKaryawan->jenis_kelamin }}</span>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Status Perkawinan</div>
                                <span class="badge bg-light-secondary text-dark">{{ $calonKaryawan->status_perkawinan }}</span>
                            </div>

                            <div class="col-md-6">
                                <div class="small text-muted">Tempat Lahir</div>
                                <div class="fw-semibold">{{ $calonKaryawan->tempat_lahir }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Tanggal Lahir</div>
                                <div class="fw-semibold">{{ optional($calonKaryawan->tanggal_lahir)->format('Y-m-d') }}</div>
                            </div>

                            <div class="col-md-6">
                                <div class="small text-muted">Agama</div>
                                <div class="fw-semibold">{{ $calonKaryawan->agama }}</div>
                            </div>

                            <div class="col-12">
                                <div class="small text-muted">Alamat KTP</div>
                                <div class="fw-semibold" style="white-space: pre-wrap;">{{ $calonKaryawan->alamat_ktp }}</div>
                            </div>

                            <div class="col-12">
                                <div class="small text-muted">Alamat Domisili</div>
                                <div class="fw-semibold" style="white-space: pre-wrap;">{{ $calonKaryawan->alamat_domisili }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section lainnya: Orang Tua, Saudara, Pengalaman Kerja, Pendidikan, Bahasa & Prestasi --}}
                {{-- (biarin sama persis kayak di interview show kamu) --}}
                @includeIf('admin.calon-karyawan.partials.detail-sections', ['calonKaryawan' => $calonKaryawan])
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const indexUrl = @json(route('admin.calon-karyawan.rejected.index'));
            const csrf = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

            // Copy helpers
            function copyText(text) {
                if (!text) return;
                if (navigator.clipboard && window.isSecureContext) {
                    return navigator.clipboard.writeText(text);
                }
                const $tmp = $('<textarea>').val(text).appendTo('body').select();
                document.execCommand('copy');
                $tmp.remove();
                return Promise.resolve();
            }

            $('#btnCopyNik').on('click', function () {
                const text = $('#nikText').text().trim();
                copyText(text).then(() => {
                    Swal.fire({ icon: 'success', title: 'Copied', text: 'NIK copied', timer: 900, showConfirmButton: false });
                });
            });

            $('#btnCopyTelp').on('click', function () {
                const text = $('#telpText').text().trim();
                copyText(text).then(() => {
                    Swal.fire({ icon: 'success', title: 'Copied', text: 'No. Telp copied', timer: 900, showConfirmButton: false });
                });
            });

            // ===== Update status =====
            $(document).on('click', '.btn-update-status', function () {
                const url = $(this).data('url');
                const name = $(this).data('name') || 'calon karyawan';
                const status = $(this).data('status');

                const labelMap = {
                    SHORTLIST_ADMIN: 'Shortlist Admin'
                };

                Swal.fire({
                    title: 'Kembalikan ke Shortlist Admin?',
                    html: `Yakin ubah status <b>${name}</b> ke <b>${labelMap[status] || status}</b>?<br><small class="text-muted">Aksi ini akan mengubah status recruitment.</small>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Kembalikan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#3085d6'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: { _token: csrf, status: status },
                        success: function (res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res?.message || `Status berhasil diubah ke ${labelMap[status] || status}`,
                                confirmButtonText: 'OK'
                            }).then(() => window.location.href = indexUrl);
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Gagal mengubah status';
                            Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonText: 'OK' });
                        }
                    });
                });
            });

            // Delete
            $(document).on('click', '.btn-delete-calon', function () {
                const url  = $(this).data('url');
                const name = $(this).data('name') || 'calon karyawan ini';
                const nik  = $(this).data('nik') || '';

                Swal.fire({
                    title: 'Delete calon karyawan?',
                    html: `Yakin hapus <b>${name}</b>${nik ? ` <span class="text-muted">(NIK: ${nik})</span>` : ''}?<br><small class="text-muted">Data yang dihapus tidak bisa dikembalikan.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#d33'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: { _token: csrf, _method: 'DELETE' },
                        success: function (res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted',
                                text: res?.message || 'Calon karyawan deleted successfully',
                                confirmButtonText: 'OK'
                            }).then(() => window.location.href = indexUrl);
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Failed to delete calon karyawan';
                            Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonText: 'OK' });
                        }
                    });
                });
            });
        });
    </script>
@endpush
