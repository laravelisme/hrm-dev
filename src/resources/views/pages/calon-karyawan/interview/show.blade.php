@extends('layouts.master')

@section('title', 'Calon Karyawan - Detail')
@section('subtitle', 'Interview')

@section('content')
    <section class="section">
        <div class="row g-3">

            {{-- LEFT: Summary --}}
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Ringkasan</h4>
                        <a href="{{ route('admin.calon-karyawan.interview.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-start gap-3">
                            <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center"
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
                            <span class="badge bg-light-success text-dark">
                                {{ $status ?: '-' }}
                            </span>
                        </div>

                        <div class="small text-muted mt-3">Submitted At</div>
                        <div class="text-muted small">{{ optional($calonKaryawan->created_at)->format('Y-m-d H:i') }}</div>

                        <div class="small text-muted mt-1">Updated At</div>
                        <div class="text-muted small">{{ optional($calonKaryawan->updated_at)->format('Y-m-d H:i') }}</div>

                        <hr class="my-3">

                        <div class="d-flex gap-2">
                            <button type="button"
                                    class="btn btn-danger btn-sm flex-grow-1 btn-delete-calon"
                                    data-url="{{ route('admin.calon-karyawan.interview.destroy', $calonKaryawan->id) }}"
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

                {{-- Section: Data Orang Tua --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="mb-0">3) Data Orang Tua</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 fw-semibold">Ayah</div>
                            <div class="col-md-6">
                                <div class="small text-muted">Nama Ayah</div>
                                <div class="fw-semibold">{{ $calonKaryawan->nama_ayah }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Pekerjaan Ayah</div>
                                <div class="fw-semibold">{{ $calonKaryawan->pekerjaan_ayah ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Tempat Lahir Ayah</div>
                                <div class="fw-semibold">{{ $calonKaryawan->tempat_lahir_ayah }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Tanggal Lahir Ayah</div>
                                <div class="fw-semibold">{{ optional($calonKaryawan->tanggal_lahir_ayah)->format('Y-m-d') }}</div>
                            </div>

                            <hr class="my-2">

                            <div class="col-12 fw-semibold">Ibu</div>
                            <div class="col-md-6">
                                <div class="small text-muted">Nama Ibu</div>
                                <div class="fw-semibold">{{ $calonKaryawan->nama_ibu }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Pekerjaan Ibu</div>
                                <div class="fw-semibold">{{ $calonKaryawan->pekerjaan_ibu ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Tempat Lahir Ibu</div>
                                <div class="fw-semibold">{{ $calonKaryawan->tempat_lahir_ibu }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Tanggal Lahir Ibu</div>
                                <div class="fw-semibold">{{ optional($calonKaryawan->tanggal_lahir_ibu)->format('Y-m-d') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section: Saudara Kandung --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="mb-0">4) Saudara Kandung</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $siblings = [];
                            for ($n=1; $n<=4; $n++) {
                                $siblings[] = [
                                    'n' => $n,
                                    'nama' => data_get($calonKaryawan, "nama_saudara_kandung_$n"),
                                    'tempat' => data_get($calonKaryawan, "tempat_saudara_kandung_$n"),
                                    'tanggal' => data_get($calonKaryawan, "tanggal_saudara_kandung_$n"),
                                    'pekerjaan' => data_get($calonKaryawan, "pekerjaan_saudara_kandung_$n"),
                                ];
                            }
                            $hasSibling = collect($siblings)->contains(fn($s) => !empty($s['nama']) || !empty($s['tempat']) || !empty($s['tanggal']) || !empty($s['pekerjaan']));
                        @endphp

                        @if(!$hasSibling)
                            <div class="alert alert-light-primary mb-0">
                                <i class="bi bi-info-circle me-1"></i> Tidak ada data saudara kandung.
                            </div>
                        @else
                            @foreach($siblings as $s)
                                @if(!empty($s['nama']) || !empty($s['tempat']) || !empty($s['tanggal']) || !empty($s['pekerjaan']))
                                    <div class="border rounded p-3 mb-3">
                                        <div class="fw-semibold mb-2">Saudara Kandung #{{ $s['n'] }}</div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="small text-muted">Nama</div>
                                                <div class="fw-semibold">{{ $s['nama'] ?: '-' }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small text-muted">Pekerjaan</div>
                                                <div class="fw-semibold">{{ $s['pekerjaan'] ?: '-' }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small text-muted">Tempat</div>
                                                <div class="fw-semibold">{{ $s['tempat'] ?: '-' }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small text-muted">Tanggal Lahir</div>
                                                <div class="fw-semibold">
                                                    {{ $s['tanggal'] ? \Illuminate\Support\Carbon::parse($s['tanggal'])->format('Y-m-d') : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Section: Pengalaman Kerja --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="mb-0">5) Pengalaman Kerja</h4>
                    </div>
                    <div class="card-body">
                        @php
                            $works = [];
                            for ($n=1; $n<=3; $n++) {
                                $works[] = [
                                    'n' => $n,
                                    'perusahaan' => data_get($calonKaryawan, "pengalaman_kerja_$n"),
                                    'industri' => data_get($calonKaryawan, "industri_pengalaman_kerja_$n"),
                                    'alamat' => data_get($calonKaryawan, "alamat_pengalaman_kerja_$n"),
                                    'posisi' => data_get($calonKaryawan, "posisi_pengalaman_kerja_$n"),
                                    'gaji_awal' => data_get($calonKaryawan, "gaji_awal_pengalaman_kerja_$n"),
                                    'gaji_akhir' => data_get($calonKaryawan, "gaji_akhir_pengalaman_kerja_$n"),
                                    'alasan' => data_get($calonKaryawan, "alasan_berhenti_pengalaman_kerja_$n"),
                                    'ket' => data_get($calonKaryawan, "keterangan_pengalaman_kerja_$n"),
                                ];
                            }
                            $hasWork = collect($works)->contains(fn($w) => collect($w)->except(['n'])->filter()->isNotEmpty());
                        @endphp

                        @if(!$hasWork)
                            <div class="alert alert-light-primary mb-0">
                                <i class="bi bi-info-circle me-1"></i> Tidak ada data pengalaman kerja.
                            </div>
                        @else
                            @foreach($works as $w)
                                @if(collect($w)->except(['n'])->filter()->isNotEmpty())
                                    <div class="border rounded p-3 mb-3">
                                        <div class="fw-semibold mb-2">Pengalaman Kerja #{{ $w['n'] }}</div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="small text-muted">Perusahaan</div>
                                                <div class="fw-semibold">{{ $w['perusahaan'] ?: '-' }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small text-muted">Industri</div>
                                                <div class="fw-semibold">{{ $w['industri'] ?: '-' }}</div>
                                            </div>
                                            <div class="col-12">
                                                <div class="small text-muted">Alamat</div>
                                                <div class="fw-semibold">{{ $w['alamat'] ?: '-' }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small text-muted">Posisi</div>
                                                <div class="fw-semibold">{{ $w['posisi'] ?: '-' }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="small text-muted">Gaji Awal</div>
                                                <div class="fw-semibold">{{ $w['gaji_awal'] ?: '-' }}</div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="small text-muted">Gaji Akhir</div>
                                                <div class="fw-semibold">{{ $w['gaji_akhir'] ?: '-' }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small text-muted">Alasan Berhenti</div>
                                                <div class="fw-semibold">{{ $w['alasan'] ?: '-' }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="small text-muted">Keterangan</div>
                                                <div class="fw-semibold">{{ $w['ket'] ?: '-' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Section: Pendidikan & Training --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="mb-0">6) Pendidikan & Training</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="small text-muted">Pendidikan Terakhir</div>
                                <div class="fw-semibold">{{ $calonKaryawan->pendidikan_terakhir }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Nama Sekolah/Universitas</div>
                                <div class="fw-semibold">{{ $calonKaryawan->nama_sekolah_universitas ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Jurusan</div>
                                <div class="fw-semibold">{{ $calonKaryawan->jurusan ?: '-' }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">IPK/Nilai Akhir</div>
                                <div class="fw-semibold">{{ $calonKaryawan->ipk_nilai_akhir ?: '-' }}</div>
                            </div>
                            <div class="col-md-3">
                                <div class="small text-muted">Tahun Lulus</div>
                                <div class="fw-semibold">{{ $calonKaryawan->tahun_lulus ?: '-' }}</div>
                            </div>

                            <hr class="my-2">

                            <div class="col-md-6">
                                <div class="small text-muted">Nama Lembaga Training</div>
                                <div class="fw-semibold">{{ $calonKaryawan->nama_lembaga_training ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Jenis Training</div>
                                <div class="fw-semibold">{{ $calonKaryawan->jenis_training ?: '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section: Bahasa & Prestasi --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h4 class="mb-0">7) Bahasa & Prestasi</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 fw-semibold">Bahasa Asing #1</div>
                            <div class="col-md-6">
                                <div class="small text-muted">Bahasa</div>
                                <div class="fw-semibold">{{ $calonKaryawan->keahlian_bahasa_asing_1 ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Kemampuan Bicara</div>
                                <div class="fw-semibold">{{ $calonKaryawan->kemampuan_bicara_1 ?: '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-muted">Mendengar</div>
                                <div class="fw-semibold">{{ $calonKaryawan->kemampuan_mendengar_1 ?: '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-muted">Menulis</div>
                                <div class="fw-semibold">{{ $calonKaryawan->kemampuan_menulis_1 ?: '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-muted">Membaca</div>
                                <div class="fw-semibold">{{ $calonKaryawan->kemampuan_membaca_1 ?: '-' }}</div>
                            </div>

                            <hr class="my-2">

                            <div class="col-12 fw-semibold">Bahasa Asing #2</div>
                            <div class="col-md-6">
                                <div class="small text-muted">Bahasa</div>
                                <div class="fw-semibold">{{ $calonKaryawan->keahlian_bahasa_asing_2 ?: '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-muted">Kemampuan Bicara</div>
                                <div class="fw-semibold">{{ $calonKaryawan->kemampuan_bicara_2 ?: '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-muted">Mendengar</div>
                                <div class="fw-semibold">{{ $calonKaryawan->kemampuan_mendengar_2 ?: '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-muted">Menulis</div>
                                <div class="fw-semibold">{{ $calonKaryawan->kemampuan_menulis_2 ?: '-' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-muted">Membaca</div>
                                <div class="fw-semibold">{{ $calonKaryawan->kemampuan_membaca_2 ?: '-' }}</div>
                            </div>

                            <hr class="my-2">

                            <div class="col-12">
                                <div class="small text-muted">Prestasi</div>
                                <div class="fw-semibold">{{ $calonKaryawan->prestasi ?: '-' }}</div>
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
            const indexUrl = @json(route('admin.calon-karyawan.interview.index'));
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
