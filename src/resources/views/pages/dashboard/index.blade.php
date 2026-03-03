@extends('layouts.master')

@section('title', 'Dashboard HRM')

@section('meta-tag')
    <meta name="description" content="Dashboard HRM - Monitoring HR System">
@endsection

@section('subtitle', 'Dashboard HRM')

@section('content')
    <section class="section">

        {{-- ================= HEADER ================= --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h4 class="mb-1">Dashboard HRM</h4>
                            <div class="text-muted">
                                Ringkasan data dan aktivitas sistem Human Resource Management
                            </div>
                        </div>
                        <div>
                        <span class="badge bg-light text-dark">
                            {{ now()->format('d F Y') }}
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- ================= STATISTIK ================= --}}
        <div class="row g-3 mb-4">

            <div class="col-md-6 col-xl-3">
                <div class="card border shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="text-muted small">Total Karyawan Aktif</div>
                        <div class="fs-2 fw-bold">{{ $totalKaryawan }}</div>
                        <div class="text-muted small mt-1">
                            Jumlah seluruh karyawan aktif dalam sistem
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card border shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="text-muted small">Total Tenant</div>
                        <div class="fs-2 fw-bold">{{ $totalTenant }}</div>
                        <div class="text-muted small mt-1">
                            Total tenant terdaftar
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card border shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="text-muted small">Total Company</div>
                        <div class="fs-2 fw-bold">{{ $totalCompany }}</div>
                        <div class="text-muted small mt-1">
                            Perusahaan yang tergabung dalam sistem
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card border shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="text-muted small">Total Department</div>
                        <div class="fs-2 fw-bold">{{ $totalDepartment }}</div>
                        <div class="text-muted small mt-1">
                            Total divisi dalam perusahaan
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card border shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="text-muted small">Izin Hari Ini</div>
                        <div class="fs-2 fw-bold">{{ $izinHariIni }}</div>
                        <div class="text-muted small">Bulan ini: {{ $izinBulanIni }}</div>
                        <div class="text-muted small mt-1">
                            Pengajuan izin tidak masuk kerja
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card border shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="text-muted small">SP Bulan Ini</div>
                        <div class="fs-2 fw-bold">{{ $spBulanIni }}</div>
                        <div class="text-muted small mt-1">
                            Surat peringatan yang diterbitkan bulan ini
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card border shadow-sm h-100 stat-card">
                    <div class="card-body">
                        <div class="text-muted small">Cuti Hari Ini</div>
                        <div class="fs-2 fw-bold">{{ $cutiHariIni }}</div>
                        <div class="text-muted small">Bulan ini: {{ $cutiBulanIni }}</div>
                        <div class="text-muted small mt-1">
                            Karyawan yang sedang mengambil cuti
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- ================= MASTER DATA ================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Master Data</h5>
                <small class="text-muted">Pengelolaan data utama sistem HRM</small>
            </div>
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.master-data.hari-libur.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Hari Libur</div>
                                    <div class="text-muted small mt-1">Pengaturan hari libur nasional & perusahaan</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.master-data.grup-jam-kerja.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Grup Jam Kerja</div>
                                    <div class="text-muted small mt-1">Manajemen shift & jam kerja</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.master-data.saldo-cuti.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Saldo Cuti</div>
                                    <div class="text-muted small mt-1">Monitoring saldo cuti karyawan</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.master-data.jabatan.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Jabatan</div>
                                    <div class="text-muted small mt-1">Struktur jabatan organisasi</div>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>


        {{-- ================= TRANSAKSI ================= --}}
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Transaksi</h5>
                <small class="text-muted">Aktivitas operasional & administrasi karyawan</small>
            </div>
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.transaksi.cuti-karyawan.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Cuti</div>
                                    <div class="text-muted small mt-1">Pengajuan & persetujuan cuti</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.transaksi.izin-karyawan.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Izin</div>
                                    <div class="text-muted small mt-1">Monitoring izin kerja</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.transaksi.presensi.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Presensi</div>
                                    <div class="text-muted small mt-1">Rekap absensi karyawan</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.transaksi.surat-peringatan.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Surat Peringatan</div>
                                    <div class="text-muted small mt-1">Manajemen pelanggaran karyawan</div>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>


        {{-- ================= PROSES RECRUITMENT ================= --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Proses Recruitment</h5>
                <small class="text-muted">Tahapan seleksi dan manajemen calon karyawan</small>
            </div>
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.calon-karyawan.generate-link.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Generate Link</div>
                                    <div class="text-muted small mt-1">Membuat link pendaftaran kandidat</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.calon-karyawan.shortlist-admin.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Shortlist Admin</div>
                                    <div class="text-muted small mt-1">Seleksi awal dan penyaringan kandidat</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.calon-karyawan.test-tulis.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Test Tulis</div>
                                    <div class="text-muted small mt-1">Pengelolaan tes tertulis kandidat</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.calon-karyawan.interview.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Interview</div>
                                    <div class="text-muted small mt-1">Proses wawancara & link Zoom</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.calon-karyawan.talent-pool.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Talent Pool</div>
                                    <div class="text-muted small mt-1">Database kandidat potensial</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.calon-karyawan.offering.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Offering</div>
                                    <div class="text-muted small mt-1">Pengiriman offering letter</div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <a href="{{ route('admin.calon-karyawan.rejected.index') }}" class="text-decoration-none">
                            <div class="card border h-100 module-card">
                                <div class="card-body">
                                    <div class="fw-semibold text-dark">Rejected</div>
                                    <div class="text-muted small mt-1">Data kandidat tidak lolos seleksi</div>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>

    </section>
@endsection


@section('scripts')
    <style>
        .stat-card,
        .module-card {
            transition: 0.2s ease-in-out;
        }
        .stat-card:hover,
        .module-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
    </style>
@endsection
