<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Recruitment - Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

    <style>
        .select2-container .select2-selection--single {
            height: 38px;
            padding: 4px 10px;
            border: 1px solid #dee2e6;
            border-radius: .375rem;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
            right: 8px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
        }

        .select2-selection.is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 .25rem rgba(220,53,69,.25);
        }

        #stepNav .list-group-item {
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container-fluid">
        <span class="navbar-brand fw-semibold">
            <i class="bi bi-person-check me-2"></i> Recruitment
        </span>
        <span class="text-muted small d-none d-md-inline">Form Pendaftaran Calon Karyawan</span>
    </div>
</nav>

<main class="container py-4">
    <div class="mb-3">
        <h4 class="mb-0">Form Pendaftaran Calon Karyawan</h4>
        <div class="text-muted">Recruitment - Register</div>
    </div>

    <section class="section">
        <div class="row g-3">
            <div class="col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="list-group" id="stepNav">
                            <button type="button" class="list-group-item list-group-item-action active" data-step="1">
                                1. Pendaftaran
                            </button>
                            <button type="button" class="list-group-item list-group-item-action" data-step="2">
                                2. Data Diri
                            </button>
                            <button type="button" class="list-group-item list-group-item-action" data-step="3">
                                3. Data Orang Tua
                            </button>
                            <button type="button" class="list-group-item list-group-item-action" data-step="4">
                                4. Saudara Kandung
                            </button>
                            <button type="button" class="list-group-item list-group-item-action" data-step="5">
                                5. Pengalaman Kerja
                            </button>
                            <button type="button" class="list-group-item list-group-item-action" data-step="6">
                                6. Pendidikan & Training
                            </button>
                            <button type="button" class="list-group-item list-group-item-action" data-step="7">
                                7. Bahasa & Prestasi
                            </button>
                        </div>

                        <div class="mt-3 small text-muted">
                            Token: <span class="font-monospace">{{ $tokenStr }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0" id="stepTitle">1. Pendaftaran</h5>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->has('general'))
                            <div class="alert alert-danger">
                                <i class="bi bi-x-circle me-1"></i> {{ $errors->first('general') }}
                            </div>
                        @endif

                        <form id="registerForm" class="needs-validation" novalidate
                              method="POST" action="{{ route('recruitment.register.store') }}">
                            @csrf

                            <input type="hidden" name="token" value="{{ $tokenStr }}">

                            {{-- =========================
                                STEP 1 - PENDAFTARAN
                            ========================== --}}
                            <div class="form-step" data-step="1">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Company <span class="text-danger">*</span></label>
                                        <select id="m_company_id" name="m_company_id" class="form-select" required>
                                            <option value=""></option>
                                            @if(!empty($selectedCompany))
                                                <option value="{{ $selectedCompany->id }}" selected>
                                                    {{ $selectedCompany->company_name }}
                                                </option>
                                            @endif
                                        </select>
                                        <div class="invalid-feedback">Company wajib dipilih.</div>
                                        @error('m_company_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Department <span class="text-danger">*</span></label>
                                        <select id="m_department_id" name="m_department_id" class="form-select" required>
                                            <option value=""></option>
                                            @if(!empty($selectedDepartment))
                                                <option value="{{ $selectedDepartment->id }}" selected>
                                                    {{ $selectedDepartment->department_name }}
                                                </option>
                                            @endif
                                        </select>
                                        <div class="invalid-feedback">Department wajib dipilih.</div>
                                        @error('m_department_id')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- =========================
                                STEP 2 - DATA DIRI
                            ========================== --}}
                            <div class="form-step d-none" data-step="2">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                                        <div class="invalid-feedback">Nama lengkap wajib diisi.</div>
                                        @error('nama_lengkap')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Nama Panggilan <span class="text-danger">*</span></label>
                                        <input class="form-control" name="nama_panggilan" value="{{ old('nama_panggilan') }}" required>
                                        <div class="invalid-feedback">Nama panggilan wajib diisi.</div>
                                        @error('nama_panggilan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">No. Telp <span class="text-danger">*</span></label>
                                        <input class="form-control" name="no_telp" value="{{ old('no_telp') }}" required>
                                        <div class="invalid-feedback">No. telp wajib diisi.</div>
                                        @error('no_telp')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">NIK <span class="text-danger">*</span></label>
                                        <input class="form-control" name="nik" value="{{ old('nik') }}" required>
                                        <div class="invalid-feedback">NIK wajib diisi.</div>
                                        @error('nik')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-select" name="jenis_kelamin" required>
                                            <option value="" disabled selected>Pilih</option>
                                            <option value="MALE" @selected(old('jenis_kelamin')==='MALE')>MALE</option>
                                            <option value="FEMALE" @selected(old('jenis_kelamin')==='FEMALE')>FEMALE</option>
                                        </select>
                                        <div class="invalid-feedback">Jenis kelamin wajib dipilih.</div>
                                        @error('jenis_kelamin')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Status Perkawinan <span class="text-danger">*</span></label>
                                        <select class="form-select" name="status_perkawinan" required>
                                            <option value="" disabled selected>Pilih</option>
                                            @foreach(['SINGLE','MARRIED','DIVORCED','WIDOWED'] as $st)
                                                <option value="{{ $st }}" @selected(old('status_perkawinan')===$st)>{{ $st }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Status perkawinan wajib dipilih.</div>
                                        @error('status_perkawinan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Tempat Lahir <span class="text-danger">*</span></label>
                                        <input class="form-control" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required>
                                        <div class="invalid-feedback">Tempat lahir wajib diisi.</div>
                                        @error('tempat_lahir')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                                        <div class="invalid-feedback">Tanggal lahir wajib diisi.</div>
                                        @error('tanggal_lahir')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Agama <span class="text-danger">*</span></label>
                                        <input class="form-control" name="agama" value="{{ old('agama') }}" required>
                                        <div class="invalid-feedback">Agama wajib diisi.</div>
                                        @error('agama')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Alamat KTP <span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="2" name="alamat_ktp" required>{{ old('alamat_ktp') }}</textarea>
                                        <div class="invalid-feedback">Alamat KTP wajib diisi.</div>
                                        @error('alamat_ktp')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Alamat Domisili <span class="text-danger">*</span></label>
                                        <textarea class="form-control" rows="2" name="alamat_domisili" required>{{ old('alamat_domisili') }}</textarea>
                                        <div class="invalid-feedback">Alamat domisili wajib diisi.</div>
                                        @error('alamat_domisili')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- STEP 3 --}}
                            <div class="form-step d-none" data-step="3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Ayah <span class="text-danger">*</span></label>
                                        <input class="form-control" name="nama_ayah" value="{{ old('nama_ayah') }}" required>
                                        <div class="invalid-feedback">Nama ayah wajib diisi.</div>
                                        @error('nama_ayah')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Pekerjaan Ayah</label>
                                        <input class="form-control" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}">
                                        @error('pekerjaan_ayah')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tempat Lahir Ayah <span class="text-danger">*</span></label>
                                        <input class="form-control" name="tempat_lahir_ayah" value="{{ old('tempat_lahir_ayah') }}" required>
                                        <div class="invalid-feedback">Wajib diisi.</div>
                                        @error('tempat_lahir_ayah')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Lahir Ayah <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_lahir_ayah" value="{{ old('tanggal_lahir_ayah') }}" required>
                                        <div class="invalid-feedback">Wajib diisi.</div>
                                        @error('tanggal_lahir_ayah')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <hr class="my-2">

                                    <div class="col-md-6">
                                        <label class="form-label">Nama Ibu <span class="text-danger">*</span></label>
                                        <input class="form-control" name="nama_ibu" value="{{ old('nama_ibu') }}" required>
                                        <div class="invalid-feedback">Nama ibu wajib diisi.</div>
                                        @error('nama_ibu')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Pekerjaan Ibu</label>
                                        <input class="form-control" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}">
                                        @error('pekerjaan_ibu')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tempat Lahir Ibu <span class="text-danger">*</span></label>
                                        <input class="form-control" name="tempat_lahir_ibu" value="{{ old('tempat_lahir_ibu') }}" required>
                                        <div class="invalid-feedback">Wajib diisi.</div>
                                        @error('tempat_lahir_ibu')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Tanggal Lahir Ibu <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_lahir_ibu" value="{{ old('tanggal_lahir_ibu') }}" required>
                                        <div class="invalid-feedback">Wajib diisi.</div>
                                        @error('tanggal_lahir_ibu')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- STEP 4 --}}
                            <div class="form-step d-none" data-step="4">
                                @for($n=1; $n<=4; $n++)
                                    <div class="border rounded p-3 mb-3 bg-white">
                                        <div class="fw-semibold mb-2">Saudara Kandung #{{ $n }} (opsional)</div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Nama</label>
                                                <input class="form-control" name="nama_saudara_kandung_{{ $n }}" value="{{ old('nama_saudara_kandung_'.$n) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Pekerjaan</label>
                                                <input class="form-control" name="pekerjaan_saudara_kandung_{{ $n }}" value="{{ old('pekerjaan_saudara_kandung_'.$n) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Tempat</label>
                                                <input class="form-control" name="tempat_saudara_kandung_{{ $n }}" value="{{ old('tempat_saudara_kandung_'.$n) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Tanggal Lahir</label>
                                                <input type="date" class="form-control" name="tanggal_saudara_kandung_{{ $n }}" value="{{ old('tanggal_saudara_kandung_'.$n) }}">
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            {{-- STEP 5 --}}
                            <div class="form-step d-none" data-step="5">
                                @for($n=1; $n<=3; $n++)
                                    <div class="border rounded p-3 mb-3 bg-white">
                                        <div class="fw-semibold mb-2">Pengalaman Kerja #{{ $n }} (opsional)</div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Perusahaan</label>
                                                <input class="form-control" name="pengalaman_kerja_{{ $n }}" value="{{ old('pengalaman_kerja_'.$n) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Industri</label>
                                                <input class="form-control" name="industri_pengalaman_kerja_{{ $n }}" value="{{ old('industri_pengalaman_kerja_'.$n) }}">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label">Alamat</label>
                                                <input class="form-control" name="alamat_pengalaman_kerja_{{ $n }}" value="{{ old('alamat_pengalaman_kerja_'.$n) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Posisi</label>
                                                <input class="form-control" name="posisi_pengalaman_kerja_{{ $n }}" value="{{ old('posisi_pengalaman_kerja_'.$n) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Gaji Awal</label>
                                                <input class="form-control" name="gaji_awal_pengalaman_kerja_{{ $n }}" value="{{ old('gaji_awal_pengalaman_kerja_'.$n) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Gaji Akhir</label>
                                                <input class="form-control" name="gaji_akhir_pengalaman_kerja_{{ $n }}" value="{{ old('gaji_akhir_pengalaman_kerja_'.$n) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Alasan Berhenti</label>
                                                <input class="form-control" name="alasan_berhenti_pengalaman_kerja_{{ $n }}" value="{{ old('alasan_berhenti_pengalaman_kerja_'.$n) }}">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Keterangan</label>
                                                <input class="form-control" name="keterangan_pengalaman_kerja_{{ $n }}" value="{{ old('keterangan_pengalaman_kerja_'.$n) }}">
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            {{-- STEP 6 --}}
                            <div class="form-step d-none" data-step="6">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                        <select class="form-select" name="pendidikan_terakhir" required>
                                            <option value="" disabled selected>Pilih</option>
                                            @foreach(['SD','SMP','SMA','D3','S1/D4','S2','S3'] as $p)
                                                <option value="{{ $p }}" @selected(old('pendidikan_terakhir')===$p)>{{ $p }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">Wajib dipilih.</div>
                                        @error('pendidikan_terakhir')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Nama Sekolah/Universitas</label>
                                        <input class="form-control" name="nama_sekolah_universitas" value="{{ old('nama_sekolah_universitas') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Jurusan</label>
                                        <input class="form-control" name="jurusan" value="{{ old('jurusan') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">IPK/Nilai Akhir</label>
                                        <input class="form-control" name="ipk_nilai_akhir" value="{{ old('ipk_nilai_akhir') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label">Tahun Lulus</label>
                                        <input class="form-control" name="tahun_lulus" value="{{ old('tahun_lulus') }}">
                                    </div>

                                    <hr class="my-2">

                                    <div class="col-md-6">
                                        <label class="form-label">Nama Lembaga Training</label>
                                        <input class="form-control" name="nama_lembaga_training" value="{{ old('nama_lembaga_training') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Jenis Training</label>
                                        <input class="form-control" name="jenis_training" value="{{ old('jenis_training') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- STEP 7 --}}
                            <div class="form-step d-none" data-step="7">
                                <div class="border rounded p-3 mb-3 bg-white">
                                    <div class="fw-semibold mb-2">Bahasa Asing #1 (opsional)</div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Bahasa</label>
                                            <input class="form-control" name="keahlian_bahasa_asing_1" value="{{ old('keahlian_bahasa_asing_1') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Kemampuan Bicara (1-5)</label>
                                            <input type="number" min="1" max="5" class="form-control" name="kemampuan_bicara_1" value="{{ old('kemampuan_bicara_1') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Mendengar</label>
                                            <input class="form-control" name="kemampuan_mendengar_1" value="{{ old('kemampuan_mendengar_1') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Menulis</label>
                                            <input class="form-control" name="kemampuan_menulis_1" value="{{ old('kemampuan_menulis_1') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Membaca</label>
                                            <input class="form-control" name="kemampuan_membaca_1" value="{{ old('kemampuan_membaca_1') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="border rounded p-3 mb-3 bg-white">
                                    <div class="fw-semibold mb-2">Bahasa Asing #2 (opsional)</div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Bahasa</label>
                                            <input class="form-control" name="keahlian_bahasa_asing_2" value="{{ old('keahlian_bahasa_asing_2') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Kemampuan Bicara (1-5)</label>
                                            <input type="number" min="1" max="5" class="form-control" name="kemampuan_bicara_2" value="{{ old('kemampuan_bicara_2') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Mendengar</label>
                                            <input class="form-control" name="kemampuan_mendengar_2" value="{{ old('kemampuan_mendengar_2') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Menulis</label>
                                            <input class="form-control" name="kemampuan_menulis_2" value="{{ old('kemampuan_menulis_2') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Membaca</label>
                                            <input class="form-control" name="kemampuan_membaca_2" value="{{ old('kemampuan_membaca_2') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Prestasi (opsional)</label>
                                        <input class="form-control" name="prestasi" value="{{ old('prestasi') }}">
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary" id="btnPrev">
                                    <i class="bi bi-arrow-left me-1"></i> Back
                                </button>

                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary" id="btnNext">
                                        Next <i class="bi bi-arrow-right ms-1"></i>
                                    </button>
                                    <button type="submit" class="btn btn-success d-none" id="btnSubmit">
                                        <i class="bi bi-send me-1"></i> Submit
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(function () {
        const steps = [
            {n:1, title:'1. Pendaftaran'},
            {n:2, title:'2. Data Diri'},
            {n:3, title:'3. Data Orang Tua'},
            {n:4, title:'4. Saudara Kandung'},
            {n:5, title:'5. Pengalaman Kerja'},
            {n:6, title:'6. Pendidikan & Training'},
            {n:7, title:'7. Bahasa & Prestasi'},
        ];

        let currentStep = 1;

        function showStep(step) {
            currentStep = step;

            $('.form-step').addClass('d-none');
            $(`.form-step[data-step="${step}"]`).removeClass('d-none');

            $('#stepTitle').text(steps.find(s => s.n === step)?.title || '');

            $('#stepNav .list-group-item').removeClass('active');
            $(`#stepNav .list-group-item[data-step="${step}"]`).addClass('active');

            $('#btnPrev').prop('disabled', step === 1);
            const isLast = step === steps.length;
            $('#btnNext').toggleClass('d-none', isLast);
            $('#btnSubmit').toggleClass('d-none', !isLast);

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function markSelect2Invalid($select, invalid) {
            const $container = $select.next('.select2-container');
            const $selection = $container.find('.select2-selection');
            $selection.toggleClass('is-invalid', !!invalid);
        }

        function validateCurrentStep() {
            const $stepEl = $(`.form-step[data-step="${currentStep}"]`);
            const inputs = $stepEl.find('input, select, textarea').toArray();

            let ok = true;

            inputs.forEach(el => {
                if ($(el).hasClass('select2-hidden-accessible') && el.hasAttribute('required')) {
                    const hasVal = !!$(el).val();
                    markSelect2Invalid($(el), !hasVal);
                    if (!hasVal) ok = false;
                    return;
                }

                if (el.hasAttribute('required')) {
                    if (!el.checkValidity()) ok = false;
                }
            });

            if (!ok) {
                $('#registerForm').addClass('was-validated');

                const $firstSelect2Invalid = $stepEl.find('select.select2-hidden-accessible[required]').filter(function(){
                    return !$(this).val();
                }).first();

                if ($firstSelect2Invalid.length) {
                    const $c = $firstSelect2Invalid.next('.select2-container');
                    if ($c.length) $c[0].scrollIntoView({behavior:'smooth', block:'center'});
                    return false;
                }

                const firstInvalid = inputs.find(el => el.hasAttribute('required') && !el.checkValidity());
                if (firstInvalid) firstInvalid.scrollIntoView({behavior:'smooth', block:'center'});
            }

            return ok;
        }

        $('#stepNav').on('click', '.list-group-item', function () {
            const to = parseInt($(this).data('step'), 10);
            if (to > currentStep) {
                if (!validateCurrentStep()) return;
            }
            showStep(to);
        });

        $('#btnNext').on('click', function () {
            if (!validateCurrentStep()) return;
            showStep(currentStep + 1);
        });

        $('#btnPrev').on('click', function () {
            showStep(Math.max(1, currentStep - 1));
        });

        const companiesUrl   = @json(route('recruitment.select2.companies'));
        const departmentsUrl = @json(route('recruitment.select2.departments'));

        const $company = $('#m_company_id');
        const $dept    = $('#m_department_id');

        function setDeptEnabled(enabled) {
            $dept.prop('disabled', !enabled);
            markSelect2Invalid($dept, false);
        }

        $company.select2({
            width: '100%',
            placeholder: 'Pilih Company',
            allowClear: true,
            ajax: {
                url: companiesUrl,
                dataType: 'json',
                delay: 350, // debounce
                data: function (params) {
                    return { q: params.term || '', page: params.page || 1 };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results || [],
                        pagination: { more: !!data.pagination?.more }
                    };
                },
                cache: true
            }
        }).on('change', function () {
            markSelect2Invalid($company, !$(this).val());

            $dept.val(null).trigger('change');
            setDeptEnabled(!!$(this).val());
        });

        $dept.select2({
            width: '100%',
            placeholder: 'Pilih Department',
            allowClear: true,
            ajax: {
                url: departmentsUrl,
                dataType: 'json',
                delay: 350, // debounce
                data: function (params) {
                    return {
                        q: params.term || '',
                        page: params.page || 1,
                        company_id: $company.val() || ''
                    };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.results || [],
                        pagination: { more: !!data.pagination?.more }
                    };
                },
                cache: true
            }
        }).on('change', function () {
            markSelect2Invalid($dept, !$(this).val());
        });

        setDeptEnabled(!!$company.val());

        $dept.on('select2:opening', function (e) {
            if (!$company.val()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Pilih Company dulu',
                    text: 'Department akan muncul setelah Company dipilih.',
                    confirmButtonText: 'OK'
                });
            }
        });

        @if($errors->any())
        (function(){
            const fieldToStep = {
                m_company_id:1, m_department_id:1,
                nama_lengkap:2, nama_panggilan:2, no_telp:2, nik:2, jenis_kelamin:2, tempat_lahir:2, tanggal_lahir:2, agama:2, alamat_ktp:2, alamat_domisili:2, status_perkawinan:2,
                nama_ayah:3, tempat_lahir_ayah:3, tanggal_lahir_ayah:3, pekerjaan_ayah:3,
                nama_ibu:3, tempat_lahir_ibu:3, tanggal_lahir_ibu:3, pekerjaan_ibu:3,
                pendidikan_terakhir:6, nama_sekolah_universitas:6, jurusan:6, ipk_nilai_akhir:6, tahun_lulus:6,
            };

            const firstKey = @json(array_key_first($errors->toArray()));
            const step = fieldToStep[firstKey] || 1;
            showStep(step);

            markSelect2Invalid($company, !$company.val());
            markSelect2Invalid($dept, !$dept.val());
        })();
        @else
        showStep(1);
        @endif
    });
</script>

</body>
</html>
