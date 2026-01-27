@extends('layouts.master')

@section('title', 'Dashboard HRM')
@section('meta-tag')
    <meta name="description" content="Dashboard HRM">
@endsection

@section('subtitle', 'Dashboard HRM')

@section('content')
    <section class="section">

        {{-- Header --}}
        <div class="row g-3 mb-3">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1">Dashboard HRM</h4>
                            <div class="text-muted">Dummy dashboard untuk menu HRM</div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="#" class="btn btn-light">
                                <i class="bi bi-question-circle me-1"></i> Panduan
                            </a>
                            <a href="#" class="btn btn-primary">
                                <i class="bi bi-gear me-1"></i> Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick stat dummy --}}
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small">Aktivitas Hari Ini</div>
                                <div class="fs-3 fw-bold">—</div>
                            </div>
                            <div class="fs-2 text-muted">
                                <i class="bi bi-activity"></i>
                            </div>
                        </div>
                        <div class="text-muted small mt-2">
                            *Dummy data (belum ada query)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MASTER DATA --}}
        <div class="card mb-3">
            <div class="card-header">
                <h4 class="card-title mb-0">Master Data</h4>
                <small class="text-muted">Kelola data referensi HRM</small>
            </div>
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">Hari Libur</div>
                                            <div class="text-muted small">Tanggal merah & cuti bersama</div>
                                        </div>
                                        <div class="fs-4 text-muted"><i class="bi bi-calendar2-week"></i></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">Grup Jam Kerja</div>
                                            <div class="text-muted small">Shift & aturan check-in/out</div>
                                        </div>
                                        <div class="fs-4 text-muted"><i class="bi bi-clock-history"></i></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">Saldo Cuti</div>
                                            <div class="text-muted small">Master saldo cuti karyawan</div>
                                        </div>
                                        <div class="fs-4 text-muted"><i class="bi bi-wallet2"></i></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">Jabatan</div>
                                            <div class="text-muted small">Role & level jabatan</div>
                                        </div>
                                        <div class="fs-4 text-muted"><i class="bi bi-person-badge"></i></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">Department</div>
                                            <div class="text-muted small">Struktur divisi</div>
                                        </div>
                                        <div class="fs-4 text-muted"><i class="bi bi-diagram-3"></i></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">Company</div>
                                            <div class="text-muted small">Unit / entitas perusahaan</div>
                                        </div>
                                        <div class="fs-4 text-muted"><i class="bi bi-building"></i></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">Lokasi Kerja</div>
                                            <div class="text-muted small">Kantor, site, area kerja</div>
                                        </div>
                                        <div class="fs-4 text-muted"><i class="bi bi-geo-alt"></i></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                </div>
            </div>
        </div>

        {{-- TRANSAKSI --}}
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Transaksi</h4>
                <small class="text-muted">Operasional HRM</small>
            </div>
            <div class="card-body">
                <div class="row g-3">

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">Saldo Cuti Tahunan</div>
                                            <div class="text-muted small">Generate/adjust tahunan</div>
                                        </div>
                                        <div class="fs-4 text-muted"><i class="bi bi-arrow-repeat"></i></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">Pembaruan Data Karyawan</div>
                                            <div class="text-muted small">Verifikasi update profile</div>
                                        </div>
                                        <div class="fs-4 text-muted"><i class="bi bi-person-check"></i></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">Cuti</div>
                                            <div class="text-muted small">Pengajuan & approval cuti</div>
                                        </div>
                                        <div class="fs-4 text-muted"><i class="bi bi-calendar-check"></i></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">Izin</div>
                                            <div class="text-muted small">Izin sakit / izin keluar</div>
                                        </div>
                                        <div class="fs-4 text-muted"><i class="bi bi-journal-check"></i></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">Presensi</div>
                                            <div class="text-muted small">Kehadiran & absensi</div>
                                        </div>
                                        <div class="fs-4 text-muted"><i class="bi bi-fingerprint"></i></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">Lembur</div>
                                            <div class="text-muted small">Pengajuan lembur</div>
                                        </div>
                                        <div class="fs-4 text-muted"><i class="bi bi-hourglass-split"></i></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="#" class="text-decoration-none">
                            <div class="card border h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="fw-semibold">Surat Peringatan</div>
                                            <div class="text-muted small">SP1 / SP2 / SP3</div>
                                        </div>
                                        <div class="fs-4 text-muted"><i class="bi bi-exclamation-triangle"></i></div>
                                    </div>
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
    <script>
        console.log('Dummy Dashboard HRM loaded');
    </script>
@endsection
