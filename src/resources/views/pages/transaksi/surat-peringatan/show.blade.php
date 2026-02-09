@extends('layouts.master')

@section('title', 'Surat Peringatan - Detail')
@section('subtitle', 'Transaksi')

@section('content')
    <section class="section">
        <div class="card">

            {{-- HEADER --}}
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">Detail Surat Peringatan</h4>
                    <small class="text-muted">
                        {{ $sp->nama_karyawan }} — {{ $sp->nama_perusahaan }}
                    </small>
                </div>

                <a href="{{ route('admin.transaksi.surat-peringatan.index') }}" class="btn btn-light btn-sm">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>

            {{-- BODY --}}
            <div class="card-body">
                <div class="row g-3">

                    {{-- INFO --}}
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <div class="mb-2">
                                <small class="text-muted">Status</small><br>
                                <span class="badge
                                {{ $sp->status === 'APPROVED' ? 'bg-success' :
                                   ($sp->status === 'REJECTED' ? 'bg-danger' : 'bg-warning') }}">
                                {{ $sp->status }}
                            </span>
                            </div>

                            <div class="mb-2">
                                <small class="text-muted">Periode</small><br>
                                {{ $sp->tanggal_start }} → {{ $sp->tanggal_end }}
                            </div>

                            <div class="mb-2">
                                <small class="text-muted">Atasan</small><br>
                                {{ $sp->nama_atasan }}
                            </div>

                            <div>
                                <small class="text-muted">Catatan Atasan</small><br>
                                {{ $sp->atasan_note ?? '-' }}
                            </div>
                        </div>
                    </div>

                    {{-- DETAIL --}}
                    <div class="col-md-8">
                        <div class="border rounded p-3 h-100">
                            <h6>Detail Pelanggaran</h6>
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>Jenis</th>
                                    <th>Keterangan</th>
                                    <th>File</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($sp->details as $d)
                                    <tr>
                                        <td>{{ $d->jenis }}</td>
                                        <td>{{ $d->keterangan }}</td>
                                        <td>
                                            @if($d->file_pendukung)
                                                <a href="{{ Storage::url($d->file_pendukung) }}" target="_blank">
                                                    Lihat
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
                    </div>

                    {{-- HR APPROVAL FORM --}}
                    @if(!$sp->hr_approved)
                        <div class="col-12">
                            <div class="border rounded p-3">
                                <h5 class="mb-3">HR Approval</h5>

                                <form id="formHrApprove"
                                      action="{{ route('admin.transaksi.surat-peringatan.approve-sp', $sp->id) }}"
                                      method="POST"
                                      enctype="multipart/form-data">
                                    @csrf

                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label">Nomor Surat</label>
                                            <input type="text" name="nomor" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Tanggal Surat</label>
                                            <input type="date" name="tanggal_surat" class="form-control">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">File Surat</label>
                                            <input type="file" name="file_surat" class="form-control">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Catatan HR</label>
                                            <textarea name="hr_note" class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <input type="hidden" name="status_approval" id="statusApproval">

                                    <div class="mt-3 d-flex gap-2">
                                        <button type="button" class="btn btn-success" id="btnApprove">
                                            <i class="bi bi-check"></i> Approve
                                        </button>

                                        <button type="button" class="btn btn-danger" id="btnReject">
                                            <i class="bi bi-x"></i> Reject
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- SUDAH APPROVED --}}
                        <div class="col-12">
                            <div class="alert alert-success">
                                <b>Sudah disetujui HR</b><br>
                                Nomor: {{ $sp->nomor }}<br>
                                Tanggal: {{ $sp->tanggal_surat }}<br>
                                Catatan: {{ $sp->hr_note ?? '-' }}
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
        function submitHr(status)
        {
            $('#statusApproval').val(status);

            let form = document.getElementById('formHrApprove');
            let formData = new FormData(form);

            $.ajax({
                url: form.action,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                    Swal.fire('Berhasil', 'Data berhasil disimpan', 'success')
                        .then(() => location.reload());
                },
                error: function (xhr) {
                    Swal.fire(
                        'Gagal',
                        xhr.responseJSON?.message || 'Terjadi kesalahan',
                        'error'
                    );
                }
            });
        }

        $('#btnApprove').click(function () {
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: 'Surat peringatan akan disetujui HR',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Approve'
            }).then(r => {
                if (r.isConfirmed) submitHr('APPROVED');
            });
        });

        $('#btnReject').click(function () {
            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: 'Surat peringatan akan ditolak HR',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Reject'
            }).then(r => {
                if (r.isConfirmed) submitHr('REJECTED');
            });
        });
    </script>
@endpush
