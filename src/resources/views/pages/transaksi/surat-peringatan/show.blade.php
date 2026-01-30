@extends('layouts.master')

@section('title', 'Surat Peringatan - Detail')
@section('subtitle', 'Transaksi')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex flex-column flex-lg-row justify-content-between gap-2">
                <div>
                    <h4 class="mb-0">Detail Surat Peringatan</h4>
                    <p class="text-muted mb-0">
                        {{ $sp->nama_karyawan ?? '-' }}
                        <span class="ms-2 text-muted">Company: <b>{{ $sp->nama_perusahaan ?? '-' }}</b></span>
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.transaksi.surat-peringatan.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>

                    <button type="button"
                            class="btn btn-danger btn-sm"
                            id="btnDelete"
                            data-url="{{ route('admin.transaksi.surat-peringatan.destroy', $sp->id) }}"
                            data-name="{{ $sp->nama_karyawan ?? 'surat ini' }}">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small">Status</div>
                            <div class="mt-1">
                                @php($st = $sp->status)
                                <span class="badge
                                    {{ $st==='APPROVED' ? 'bg-light-success text-dark' :
                                       ($st==='REJECTED' ? 'bg-light-danger text-dark' :
                                       ($st==='PENDING_APPROVED' ? 'bg-light-warning text-dark' : 'bg-light-primary text-dark')) }}">
                                    {{ $st }}
                                </span>
                            </div>

                            <div class="text-muted small mt-3">Nomor</div>
                            <div class="fw-semibold">{{ $sp->nomor ?? '-' }}</div>

                            <div class="text-muted small mt-3">Tanggal Surat</div>
                            <div class="fw-semibold">{{ $sp->tanggal_surat ?? '-' }}</div>

                            <div class="text-muted small mt-3">Created At</div>
                            <div class="fw-semibold">{{ optional($sp->created_at)->format('Y-m-d H:i') }}</div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="border rounded p-3 h-100">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="text-muted small">Periode</div>
                                    <div class="fw-semibold">
                                        {{ $sp->tanggal_start ?? '-' }} → {{ $sp->tanggal_end ?? '-' }}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="text-muted small">Atasan</div>
                                    <div class="fw-semibold">{{ $sp->nama_atasan ?? '-' }}</div>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="text-muted small">Catatan Atasan</div>
                                    <div class="fw-semibold">{{ $sp->atasan_note ?? '-' }}</div>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="text-muted small">File Surat</div>
                                    <div class="fw-semibold">
                                        @if($sp->file_surat)
                                            <a href="{{ \Illuminate\Support\Facades\Storage::url($sp->file_surat) }}" target="_blank">
                                                <i class="bi bi-file-earmark-text me-1"></i> Lihat File
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 mt-2">
                                    <div class="text-muted small">HR Approved</div>
                                    <div class="fw-semibold">
                                        <span class="badge {{ $sp->hr_approved ? 'bg-light-success text-dark' : 'bg-light-secondary text-dark' }}">
                                            {{ $sp->hr_approved ? 'Approved' : 'Not Approved' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="text-muted small">HR Note</div>
                                    <div class="fw-semibold">{{ $sp->hr_note ?? '-' }}</div>
                                </div>

                                <div class="col-md-6 mt-2">
                                    <div class="text-muted small">HR Approval Date</div>
                                    <div class="fw-semibold">{{ $sp->hr_approval_date ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Details --}}
                    <div class="col-12">
                        <div class="border rounded p-3">
                            <h5 class="mb-3">Detail SP</h5>

                            @if(($sp->details ?? collect())->count() === 0)
                                <div class="text-muted">Tidak ada detail.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle">
                                        <thead>
                                        <tr>
                                            <th style="width: 200px;">Jenis</th>
                                            <th>Keterangan</th>
                                            <th style="width: 200px;">File</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($sp->details as $d)
                                            <tr>
                                                <td class="fw-semibold">{{ $d->jenis }}</td>
                                                <td>{{ $d->keterangan }}</td>
                                                <td>
                                                    @if($d->file_pendukung)
                                                        <a href="{{ \Illuminate\Support\Facades\Storage::url($d->file_pendukung) }}" target="_blank">
                                                            <i class="bi bi-paperclip me-1"></i> Lihat
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
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
            const indexUrl = @json(route('admin.transaksi.surat-peringatan.index'));

            $('#btnDelete').on('click', function () {
                const url  = $(this).data('url');
                const name = $(this).data('name') || 'surat ini';

                Swal.fire({
                    title: 'Delete surat peringatan?',
                    html: `Yakin hapus <b>${name}</b>?<br><small class="text-muted">Data yang dihapus tidak bisa dikembalikan.</small>`,
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
                            Swal.fire({ icon:'success', title:'Deleted', text: res?.message || 'Deleted' })
                                .then(() => window.location.href = indexUrl);
                        },
                        error: function (xhr) {
                            Swal.fire({ icon:'error', title:'Error', text: xhr.responseJSON?.message || 'Failed to delete' });
                        }
                    });
                });
            });
        });
    </script>
@endpush
