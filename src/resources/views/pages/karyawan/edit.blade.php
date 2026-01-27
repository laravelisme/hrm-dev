@extends('layouts.master')

@section('title', 'Master Data - Karyawan')
@section('subtitle', 'Edit Karyawan')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Edit Karyawan</h4>
                        <a href="{{ route('admin.karyawan.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formEdit" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            {{-- Tabs (per migration) --}}
                            <ul class="nav nav-tabs" id="karyawanTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-karyawans" type="button">
                                        Data Karyawan
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-riwayat-jabatan" type="button">
                                        Riwayat Jabatan
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pendidikan" type="button">
                                        Pendidikan
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pengalaman" type="button">
                                        Pengalaman
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-organisasi" type="button">
                                        Organisasi
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-bahasa" type="button">
                                        Bahasa
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-anak" type="button">
                                        Anak
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-saudara" type="button">
                                        Saudara
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content border border-top-0 p-3">

                                {{-- ================== TAB: m_karyawans ================== --}}
                                <div class="tab-pane fade show active" id="tab-karyawans">

                                    <div class="alert alert-light mb-3">
                                        <small class="text-muted">
                                            Edit karyawan tidak membuat user baru. Password user tidak diubah di form ini.
                                        </small>
                                    </div>

                                    {{-- IDENTITAS --}}
                                    <div class="fw-semibold mb-2">Identitas</div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label">Kode Karyawan</label>
                                            <input type="text" class="form-control" name="kode_karyawan"
                                                   value="{{ $karyawan->kode_karyawan }}" placeholder="KRY-000001">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Nama Karyawan <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nama_karyawan"
                                                   value="{{ $karyawan->nama_karyawan }}" placeholder="Nama lengkap">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">NIK <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nik"
                                                   value="{{ $karyawan->nik }}" placeholder="NIK">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email"
                                                   value="{{ $karyawan->email }}" placeholder="email@domain.com">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">No HP</label>
                                            <input type="text" class="form-control" name="no_hp"
                                                   value="{{ $karyawan->no_hp }}" placeholder="08xxxx">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4 d-flex align-items-end">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                                                    {{ (int)($karyawan->is_active ?? 0) === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">Active</label>
                                            </div>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    {{-- BIODATA --}}
                                    <div class="fw-semibold mb-2">Biodata</div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label">Tempat Lahir</label>
                                            <input type="text" class="form-control" name="tempat_lahir"
                                                   value="{{ $karyawan->tempat_lahir }}">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <input type="date" class="form-control" name="tanggal_lahir"
                                                   value="{{ $karyawan->tanggal_lahir ? \Illuminate\Support\Carbon::parse($karyawan->tanggal_lahir)->format('Y-m-d') : '' }}">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Jenis Kelamin</label>
                                            <select class="form-select" name="jenis_kelamin">
                                                <option value="">- pilih -</option>
                                                <option value="LAKI-LAKI" {{ $karyawan->jenis_kelamin === 'LAKI-LAKI' ? 'selected' : '' }}>LAKI-LAKI</option>
                                                <option value="PEREMPUAN" {{ $karyawan->jenis_kelamin === 'PEREMPUAN' ? 'selected' : '' }}>PEREMPUAN</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Agama</label>
                                            <input type="text" class="form-control" name="agama"
                                                   value="{{ $karyawan->agama }}" placeholder="Islam/Kristen/...">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Status Perkawinan</label>
                                            <input type="text" class="form-control" name="status_perkawinan"
                                                   value="{{ $karyawan->status_perkawinan }}" placeholder="Lajang/Menikah/...">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Foto</label>
                                            <input type="file" class="form-control" name="foto" accept="image/*">
                                            <div class="invalid-feedback"></div>
                                            @if(!empty($karyawan->foto))
                                                <small class="text-muted d-block mt-1">
                                                    File saat ini: <span class="fw-semibold">{{ basename($karyawan->foto) }}</span>
                                                </small>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- ALAMAT --}}
                                    <div class="fw-semibold mb-2">Alamat</div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label class="form-label">Alamat KTP</label>
                                            <input type="text" class="form-control" name="alamat_ktp"
                                                   value="{{ $karyawan->alamat_ktp }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Alamat Domisili</label>
                                            <input type="text" class="form-control" name="alamat_domisili"
                                                   value="{{ $karyawan->alamat_domisili }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    {{-- JABATAN SAAT INI --}}
                                    <div class="fw-semibold mb-2">Jabatan Saat Ini</div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label">Jabatan</label>
                                            <select name="m_jabatan_id" class="form-select select2-jabatan-current">
                                                <option value="">- pilih jabatan -</option>
                                                @if($karyawan->m_jabatan_id)
                                                    <option value="{{ $karyawan->m_jabatan_id }}" selected>{{ $karyawan->nama_jabatan ?? 'Selected' }}</option>
                                                @endif
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Department</label>
                                            <select name="m_department_id" class="form-select select2-department-current">
                                                <option value="">- pilih department -</option>
                                                @if($karyawan->m_department_id)
                                                    <option value="{{ $karyawan->m_department_id }}" selected>{{ $karyawan->nama_departement ?? 'Selected' }}</option>
                                                @endif
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Company</label>
                                            <select name="m_company_id" class="form-select select2-company-current">
                                                <option value="">- pilih company -</option>
                                                @if($karyawan->m_company_id)
                                                    <option value="{{ $karyawan->m_company_id }}" selected>{{ $karyawan->nama_company ?? 'Selected' }}</option>
                                                @endif
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Grup Jam Kerja</label>
                                            <select name="m_group_kerja_id" class="form-select select2-group-kerja">
                                                <option value="">- pilih grup jam kerja -</option>
                                                @if(!empty($selectedGroupKerja))
                                                    <option value="{{ $selectedGroupKerja->id }}" selected>{{ $selectedGroupKerja->name }}</option>
                                                @endif
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Atasan 1</label>
                                            <select name="atasan1_id" class="form-select select2-atasan1">
                                                <option value="">- pilih atasan -</option>
                                                @if($karyawan->atasan1_id)
                                                    <option value="{{ $karyawan->atasan1_id }}" selected>{{ $karyawan->nama_atasan1 ?? 'Selected' }}</option>
                                                @endif
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Atasan 2</label>
                                            <select name="atasan2_id" class="form-select select2-atasan2">
                                                <option value="">- pilih atasan -</option>
                                                @if($karyawan->atasan2_id)
                                                    <option value="{{ $karyawan->atasan2_id }}" selected>{{ $karyawan->nama_atasan2 ?? 'Selected' }}</option>
                                                @endif
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Tanggal Bergabung</label>
                                            <input type="date" class="form-control" name="tanggal_bergabung"
                                                   value="{{ $karyawan->tanggal_bergabung ? \Illuminate\Support\Carbon::parse($karyawan->tanggal_bergabung)->format('Y-m-d') : '' }}">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Status Karyawan</label>
                                            <input type="text" class="form-control" name="status_karyawan"
                                                   value="{{ $karyawan->status_karyawan }}" placeholder="Tetap/Kontrak/...">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        {{-- Lokasi Kerja: SELECT2 dari table m_lokasi_kerjas --}}
                                        <div class="col-md-6">
                                            <label class="form-label">Lokasi Kerja</label>
                                            <select name="m_lokasi_kerja_id" class="form-select select2-lokasi-kerja-current">
                                                <option value="">- pilih lokasi kerja -</option>
                                                @if($karyawan->m_lokasi_kerja_id)
                                                    <option value="{{ $karyawan->m_lokasi_kerja_id }}" selected>{{ $karyawan->nama_lokasi_kerja ?? 'Selected' }}</option>
                                                @endif
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Nama Lokasi Kerja (auto)</label>
                                            <input type="text" class="form-control" name="nama_lokasi_kerja"
                                                   value="{{ $karyawan->nama_lokasi_kerja }}" readonly>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-6 d-flex align-items-end">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="skip_level_two" value="1" id="skip_level_two"
                                                    {{ (int)($karyawan->skip_level_two ?? 0) === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="skip_level_two">Skip Level Two</label>
                                            </div>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="alert alert-light mb-0">
                                            <small class="text-muted">
                                                Saat submit: sistem akan mengisi <b>nama_jabatan / nama_departement / nama_company / nama_lokasi_kerja</b> di `m_karyawans`.
                                            </small>
                                        </div>
                                    </div>

                                    {{-- KONTAK DARURAT --}}
                                    <div class="fw-semibold mb-2">Kontak Darurat</div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-3">
                                            <label class="form-label">Nama</label>
                                            <input type="text" class="form-control" name="darurat_nama" value="{{ $karyawan->darurat_nama }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Hubungan</label>
                                            <input type="text" class="form-control" name="darurat_hubungan" value="{{ $karyawan->darurat_hubungan }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">No HP</label>
                                            <input type="text" class="form-control" name="darurat_hp" value="{{ $karyawan->darurat_hp }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Alamat</label>
                                            <input type="text" class="form-control" name="darurat_alamat" value="{{ $karyawan->darurat_alamat }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    {{-- KELUARGA --}}
                                    <div class="fw-semibold mb-2">Keluarga</div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-12"><div class="text-muted small">Orang Tua</div></div>

                                        <div class="col-md-4">
                                            <label class="form-label">Nama Ayah</label>
                                            <input class="form-control" name="nama_ayah" value="{{ $karyawan->nama_ayah }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tanggal Lahir Ayah</label>
                                            <input type="date" class="form-control" name="tanggal_lahir_ayah"
                                                   value="{{ $karyawan->tanggal_lahir_ayah ? \Illuminate\Support\Carbon::parse($karyawan->tanggal_lahir_ayah)->format('Y-m-d') : '' }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Pekerjaan Ayah</label>
                                            <input class="form-control" name="pekerjaan_ayah" value="{{ $karyawan->pekerjaan_ayah }}">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Nama Ibu</label>
                                            <input class="form-control" name="nama_ibu" value="{{ $karyawan->nama_ibu }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tanggal Lahir Ibu</label>
                                            <input type="date" class="form-control" name="tanggal_lahir_ibu"
                                                   value="{{ $karyawan->tanggal_lahir_ibu ? \Illuminate\Support\Carbon::parse($karyawan->tanggal_lahir_ibu)->format('Y-m-d') : '' }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Pekerjaan Ibu</label>
                                            <input class="form-control" name="pekerjaan_ibu" value="{{ $karyawan->pekerjaan_ibu }}">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-12 mt-2"><div class="text-muted small">Pasangan</div></div>

                                        <div class="col-md-4">
                                            <label class="form-label">Nama Pasangan</label>
                                            <input class="form-control" name="nama_pasangan" value="{{ $karyawan->nama_pasangan }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tempat Lahir Pasangan</label>
                                            <input class="form-control" name="tempat_lahir_pasangan" value="{{ $karyawan->tempat_lahir_pasangan }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tanggal Lahir Pasangan</label>
                                            <input type="date" class="form-control" name="tanggal_lahir_pasangan"
                                                   value="{{ $karyawan->tanggal_lahir_pasangan ? \Illuminate\Support\Carbon::parse($karyawan->tanggal_lahir_pasangan)->format('Y-m-d') : '' }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Pekerjaan Pasangan</label>
                                            <input class="form-control" name="pekerjaan_pasangan" value="{{ $karyawan->pekerjaan_pasangan }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Nama Perusahaan Pasangan</label>
                                            <input class="form-control" name="nama_perusahaan_pasangan" value="{{ $karyawan->nama_perusahaan_pasangan }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Jabatan Pasangan</label>
                                            <input class="form-control" name="jabatan_pasangan" value="{{ $karyawan->jabatan_pasangan }}">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Anak Ke- (karyawan)</label>
                                            <input type="number" class="form-control" name="anak_ke" value="{{ $karyawan->anak_ke }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Jumlah Anak</label>
                                            <input type="number" class="form-control" name="jumlah_anak" value="{{ $karyawan->jumlah_anak }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Jumlah Saudara Kandung</label>
                                            <input type="number" class="form-control" name="jumlah_saudara_kandung" value="{{ $karyawan->jumlah_saudara_kandung }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    {{-- DETAIL JABATAN (Tambahan) --}}
                                    <div class="fw-semibold mb-2">Detail Jabatan (Tambahan)</div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label">Tugas Utama</label>
                                            <input class="form-control" name="jabatan_tugas_utama" value="{{ $karyawan->jabatan_tugas_utama }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tugas</label>
                                            <input class="form-control" name="jabatan_tugas" value="{{ $karyawan->jabatan_tugas }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Aplikasi</label>
                                            <input class="form-control" name="jabatan_aplikasi" value="{{ $karyawan->jabatan_aplikasi }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Fasilitas</label>
                                            <input class="form-control" name="fasilitas" value="{{ $karyawan->fasilitas }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Rencana Karir</label>
                                            <input class="form-control" name="rencana_karir" value="{{ $karyawan->rencana_karir }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tanggal Mulai Jabatan</label>
                                            <input type="date" class="form-control" name="jabatan_tanggal_mulai"
                                                   value="{{ $karyawan->jabatan_tanggal_mulai ? \Illuminate\Support\Carbon::parse($karyawan->jabatan_tanggal_mulai)->format('Y-m-d') : '' }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Tanggal Selesai Jabatan</label>
                                            <input type="date" class="form-control" name="jabatan_tanggal_selesai"
                                                   value="{{ $karyawan->jabatan_tanggal_selesai ? \Illuminate\Support\Carbon::parse($karyawan->jabatan_tanggal_selesai)->format('Y-m-d') : '' }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    {{-- KESEHATAN & KENDARAAN --}}
                                    <div class="fw-semibold mb-2">Kesehatan & Kendaraan</div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-3">
                                            <label class="form-label">Tinggi Badan</label>
                                            <input class="form-control" name="tinggi_badan" value="{{ $karyawan->tinggi_badan }}" placeholder="cm">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Berat Badan</label>
                                            <input class="form-control" name="berat_badan" value="{{ $karyawan->berat_badan }}" placeholder="kg">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Golongan Darah</label>
                                            <input class="form-control" name="golongan_darah" value="{{ $karyawan->golongan_darah }}" placeholder="A/B/AB/O">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">SIM</label>
                                            <input class="form-control" name="sim" value="{{ $karyawan->sim }}" placeholder="A/C/...">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Riwayat Penyakit</label>
                                            <input class="form-control" name="riwayat_penyakit" value="{{ $karyawan->riwayat_penyakit }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Kendaraan</label>
                                            <input class="form-control" name="kendaraan" value="{{ $karyawan->kendaraan }}">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    {{-- FLAGS / STATUS --}}
                                    <div class="fw-semibold mb-2">Flags / Status</div>
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_active_organisasi" value="1" id="is_active_organisasi"
                                                    {{ (int)($karyawan->is_active_organisasi ?? 0) === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active_organisasi">Active Organisasi</label>
                                            </div>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_active_daerah_lain" value="1" id="is_active_daerah_lain"
                                                    {{ (int)($karyawan->is_active_daerah_lain ?? 0) === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active_daerah_lain">Active Daerah Lain</label>
                                            </div>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Alasan Daerah Lain</label>
                                            <input class="form-control" name="alaasan_daerah_lain" value="{{ $karyawan->alaasan_daerah_lain }}">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_perjalanan_dinas" value="1" id="is_perjalanan_dinas"
                                                    {{ (int)($karyawan->is_perjalanan_dinas ?? 0) === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_perjalanan_dinas">Perjalanan Dinas</label>
                                            </div>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-9">
                                            <label class="form-label">Alasan Perjalanan Dinas</label>
                                            <input class="form-control" name="alasan_perjalanan_dinas" value="{{ $karyawan->alasan_perjalanan_dinas }}">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_presensi" value="1" id="is_presensi"
                                                    {{ (int)($karyawan->is_presensi ?? 0) === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_presensi">Presensi</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_cuti" value="1" id="is_cuti"
                                                    {{ (int)($karyawan->is_cuti ?? 0) === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_cuti">Cuti</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_izin" value="1" id="is_izin"
                                                    {{ (int)($karyawan->is_izin ?? 0) === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_izin">Izin</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_lembur" value="1" id="is_lembur"
                                                    {{ (int)($karyawan->is_lembur ?? 0) === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_lembur">Lembur</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_pembaruan_data" value="1" id="is_pembaruan_data"
                                                    {{ (int)($karyawan->is_pembaruan_data ?? 0) === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_pembaruan_data">Pembaruan Data</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_resign" value="1" id="is_resign"
                                                    {{ (int)($karyawan->is_resign ?? 0) === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_resign">Resign</label>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_data_benar" value="1" id="is_data_benar"
                                                    {{ (int)($karyawan->is_data_benar ?? 1) === 1 ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_data_benar">Data Benar</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- DOKUMEN --}}
                                    <div class="fw-semibold mb-2">Dokumen</div>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">KTP File</label>
                                            <input type="file" class="form-control" name="ktp_file">
                                            <div class="invalid-feedback"></div>
                                            @if(!empty($karyawan->ktp_file))
                                                <small class="text-muted d-block mt-1">Saat ini: <span class="fw-semibold">{{ basename($karyawan->ktp_file) }}</span></small>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">KK File</label>
                                            <input type="file" class="form-control" name="kk_file">
                                            <div class="invalid-feedback"></div>
                                            @if(!empty($karyawan->kk_file))
                                                <small class="text-muted d-block mt-1">Saat ini: <span class="fw-semibold">{{ basename($karyawan->kk_file) }}</span></small>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">NPWP File</label>
                                            <input type="file" class="form-control" name="npwp_file">
                                            <div class="invalid-feedback"></div>
                                            @if(!empty($karyawan->npwp_file))
                                                <small class="text-muted d-block mt-1">Saat ini: <span class="fw-semibold">{{ basename($karyawan->npwp_file) }}</span></small>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">SIM File</label>
                                            <input type="file" class="form-control" name="sim_file">
                                            <div class="invalid-feedback"></div>
                                            @if(!empty($karyawan->sim_file))
                                                <small class="text-muted d-block mt-1">Saat ini: <span class="fw-semibold">{{ basename($karyawan->sim_file) }}</span></small>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Ijazah File</label>
                                            <input type="file" class="form-control" name="ijazah_file">
                                            <div class="invalid-feedback"></div>
                                            @if(!empty($karyawan->ijazah_file))
                                                <small class="text-muted d-block mt-1">Saat ini: <span class="fw-semibold">{{ basename($karyawan->ijazah_file) }}</span></small>
                                            @endif
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">CV File</label>
                                            <input type="file" class="form-control" name="cv_file">
                                            <div class="invalid-feedback"></div>
                                            @if(!empty($karyawan->cv_file))
                                                <small class="text-muted d-block mt-1">Saat ini: <span class="fw-semibold">{{ basename($karyawan->cv_file) }}</span></small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- ================== TAB: m_karyawan_jabatans ================== --}}
                                <div class="tab-pane fade" id="tab-riwayat-jabatan">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-semibold">Riwayat Jabatan</div>
                                        <button type="button" class="btn btn-sm btn-light" id="btnAddRiwayatJabatan">
                                            <i class="bi bi-plus-lg me-1"></i> Add
                                        </button>
                                    </div>
                                    <div class="alert alert-light mb-3">
                                        <small class="text-muted">
                                            Ini akan mengisi table <b>m_karyawan_jabatans</b>. Bisa input lebih dari 1.
                                            Pada update: data lama akan di-<b>replace</b> (delete lalu insert ulang).
                                        </small>
                                    </div>
                                    <div id="listRiwayatJabatan"></div>
                                </div>

                                {{-- ================== TAB: m_karyawan_pendidikans ================== --}}
                                <div class="tab-pane fade" id="tab-pendidikan">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-semibold">Daftar Pendidikan</div>
                                        <button type="button" class="btn btn-sm btn-light" id="btnAddPendidikan">
                                            <i class="bi bi-plus-lg me-1"></i> Add
                                        </button>
                                    </div>
                                    <div id="listPendidikan"></div>
                                </div>

                                {{-- ================== TAB: m_karyawan_pengalaman_kerjas ================== --}}
                                <div class="tab-pane fade" id="tab-pengalaman">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-semibold">Pengalaman Kerja</div>
                                        <button type="button" class="btn btn-sm btn-light" id="btnAddPengalaman">
                                            <i class="bi bi-plus-lg me-1"></i> Add
                                        </button>
                                    </div>
                                    <div id="listPengalaman"></div>
                                </div>

                                {{-- ================== TAB: m_karyawan_organisasis ================== --}}
                                <div class="tab-pane fade" id="tab-organisasi">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-semibold">Organisasi</div>
                                        <button type="button" class="btn btn-sm btn-light" id="btnAddOrganisasi">
                                            <i class="bi bi-plus-lg me-1"></i> Add
                                        </button>
                                    </div>
                                    <div id="listOrganisasi"></div>
                                </div>

                                {{-- ================== TAB: m_karyawan_bahasas ================== --}}
                                <div class="tab-pane fade" id="tab-bahasa">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-semibold">Bahasa Asing</div>
                                        <button type="button" class="btn btn-sm btn-light" id="btnAddBahasa">
                                            <i class="bi bi-plus-lg me-1"></i> Add
                                        </button>
                                    </div>
                                    <div id="listBahasa"></div>
                                </div>

                                {{-- ================== TAB: m_karyawan_anaks ================== --}}
                                <div class="tab-pane fade" id="tab-anak">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-semibold">Anak</div>
                                        <button type="button" class="btn btn-sm btn-light" id="btnAddAnak">
                                            <i class="bi bi-plus-lg me-1"></i> Add
                                        </button>
                                    </div>
                                    <div id="listAnak"></div>
                                </div>

                                {{-- ================== TAB: m_karyawan_saudaras ================== --}}
                                <div class="tab-pane fade" id="tab-saudara">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-semibold">Saudara</div>
                                        <button type="button" class="btn btn-sm btn-light" id="btnAddSaudara">
                                            <i class="bi bi-plus-lg me-1"></i> Add
                                        </button>
                                    </div>
                                    <div id="listSaudara"></div>
                                </div>

                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('admin.karyawan.index') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary" id="btnSubmit">
                                    <i class="bi bi-save me-1"></i> Update Karyawan
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const updateUrl = @json(route('admin.karyawan.update', $karyawan->id ?? 0));
            const indexUrl  = @json(route('admin.karyawan.index'));

            const jabatanUrl     = @json(route('admin.karyawan.options.jabatan'));
            const departmentUrl  = @json(route('admin.karyawan.options.department'));
            const companyUrl     = @json(route('admin.karyawan.options.company'));
            const atasanUrl      = @json(route('admin.karyawan.options.atasan'));
            const lokasiKerjaUrl = @json(route('admin.karyawan.options.lokasi-kerja'));
            const groupKerjaUrl = @json(route('admin.karyawan.options.grup_jam_kerja'));

            // ===== Select2 init =====
            function initSelect2($el, url, placeholder) {
                $el.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder,
                    allowClear: true,
                    minimumInputLength: 0,
                    dropdownAutoWidth: true,
                    dropdownParent: $el.closest('.tab-pane'),
                    ajax: {
                        url,
                        dataType: 'json',
                        delay: 250,
                        data: (params) => ({
                            q: params.term || '',
                            page: params.page || 1,
                            perPage: 20
                        }),
                        processResults: (data, params) => {
                            params.page = params.page || 1;
                            return {
                                results: data.results || [],
                                pagination: { more: !!(data.pagination && data.pagination.more) }
                            };
                        },
                        cache: true
                    },
                    language: {
                        searching: () => 'Mencari...',
                        noResults: () => 'Data tidak ditemukan'
                    }
                });
            }

            // current select2
            initSelect2($('.select2-jabatan-current'), jabatanUrl, 'Pilih Jabatan');
            initSelect2($('.select2-department-current'), departmentUrl, 'Pilih Department');
            initSelect2($('.select2-company-current'), companyUrl, 'Pilih Company');
            initSelect2($('.select2-group-kerja'), groupKerjaUrl, 'Pilih Grup Jam Kerja');

            // atasan select2
            initSelect2($('.select2-atasan1'), atasanUrl, 'Pilih Atasan');
            initSelect2($('.select2-atasan2'), atasanUrl, 'Pilih Atasan');

            // lokasi kerja select2
            initSelect2($('.select2-lokasi-kerja-current'), lokasiKerjaUrl, 'Pilih Lokasi Kerja');

            // auto-fill nama lokasi kerja
            $('.select2-lokasi-kerja-current')
                .on('select2:select', function (e) {
                    const text = e.params?.data?.text || '';
                    $('input[name="nama_lokasi_kerja"]').val(text);
                })
                .on('select2:clear', function () {
                    $('input[name="nama_lokasi_kerja"]').val('');
                });

            // ===== Helpers: error handling =====
            function resetFieldErrors() {
                $('#formEdit .is-invalid').removeClass('is-invalid');
                $('#formEdit .invalid-feedback').text('');
                $('#formEdit .select2-selection').removeClass('is-invalid');
            }

            function dotKeyToName(dotKey) {
                return dotKey
                    .replace(/\.(\d+)\./g, '[$1][')
                    .replace(/\./g, '][') + (dotKey.includes('.') ? ']' : '');
            }

            function setFieldError(dotKey, message) {
                const name = dotKeyToName(dotKey);
                const $input = $('#formEdit [name="' + name + '"]');
                if (!$input.length) return;

                $input.addClass('is-invalid');

                if ($input.hasClass('select2-hidden-accessible')) {
                    $input.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                }

                const $wrap = $input.closest('.col-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-8,.col-md-9,.col-md-12');
                const $fb = $wrap.find('.invalid-feedback').first();
                if ($fb.length) $fb.text(message);
                else $input.next('.invalid-feedback').text(message);
            }

            // ===== Repeater Templates =====
            let idxRiwayatJabatan = 0, idxPendidikan = 0, idxPengalaman = 0, idxOrganisasi = 0, idxBahasa = 0, idxAnak = 0, idxSaudara = 0;

            function rowCard(html) {
                return `<div class="card border mb-2"><div class="card-body py-3">${html}</div></div>`;
            }

            function riwayatJabatanRow(i, v = {}) {
                return rowCard(`
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-semibold">Riwayat Jabatan #${i+1}</div>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">Remove</button>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Jabatan</label>
                            <select class="form-select select2-jabatan-hist" name="jabatans[${i}][m_jabatan_id]">
                                ${v.m_jabatan_id ? `<option value="${v.m_jabatan_id}" selected>${(v.nama_jabatan || 'Selected')}</option>` : `<option value="">- pilih jabatan -</option>`}
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <select class="form-select select2-department-hist" name="jabatans[${i}][m_department_id]">
                                ${v.m_department_id ? `<option value="${v.m_department_id}" selected>${(v.nama_department || 'Selected')}</option>` : `<option value="">- pilih department -</option>`}
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Company</label>
                            <select class="form-select select2-company-hist" name="jabatans[${i}][m_company_id]">
                                ${v.m_company_id ? `<option value="${v.m_company_id}" selected>${(v.nama_company || 'Selected')}</option>` : `<option value="">- pilih company -</option>`}
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tugas Utama</label>
                            <input class="form-control" name="jabatans[${i}][tugas_utama]" value="${v.tugas_utama ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tugas</label>
                            <input class="form-control" name="jabatans[${i}][tugas]" value="${v.tugas ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Aplikasi</label>
                            <input class="form-control" name="jabatans[${i}][aplikasi]" value="${v.aplikasi ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="jabatans[${i}][tanggal_mulai]" value="${v.tanggal_mulai ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" name="jabatans[${i}][tanggal_selesai]" value="${v.tanggal_selesai ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                `);
            }

            function pendidikanRow(i, v = {}) {
                return rowCard(`
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-semibold">Pendidikan #${i+1}</div>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">Remove</button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Jenjang</label>
                            <input class="form-control" name="pendidikan[${i}][jenjang_pendidikan]" value="${v.jenjang_pendidikan ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Nama</label>
                            <input class="form-control" name="pendidikan[${i}][nama]" value="${v.nama ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Program Studi</label>
                            <input class="form-control" name="pendidikan[${i}][program_studi]" value="${v.program_studi ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tahun Masuk</label>
                            <input class="form-control" name="pendidikan[${i}][tahun_masuk]" value="${v.tahun_masuk ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tahun Lulus</label>
                            <input class="form-control" name="pendidikan[${i}][tahun_lulus]" value="${v.tahun_lulus ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Urutan</label>
                            <input type="number" class="form-control" name="pendidikan[${i}][urutan]" value="${v.urutan ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                `);
            }

            function pengalamanRow(i, v = {}) {
                return rowCard(`
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-semibold">Pengalaman #${i+1}</div>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">Remove</button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Perusahaan</label>
                            <input class="form-control" name="pengalaman_kerja[${i}][nama_perusahaan]" value="${v.nama_perusahaan ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jabatan</label>
                            <input class="form-control" name="pengalaman_kerja[${i}][jabatan]" value="${v.jabatan ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Mulai</label>
                            <input class="form-control" name="pengalaman_kerja[${i}][tahun_mulai]" value="${v.tahun_mulai ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Selesai</label>
                            <input class="form-control" name="pengalaman_kerja[${i}][tahun_selesai]" value="${v.tahun_selesai ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tugas</label>
                            <input class="form-control" name="pengalaman_kerja[${i}][riwayat_tugas]" value="${v.riwayat_tugas ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Alamat Perusahaan</label>
                            <input class="form-control" name="pengalaman_kerja[${i}][riwayat_alamat_perusahaan]" value="${v.riwayat_alamat_perusahaan ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Alasan Berhenti</label>
                            <input class="form-control" name="pengalaman_kerja[${i}][riwayat_berhenti]" value="${v.riwayat_berhenti ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Gaji</label>
                            <input class="form-control" name="pengalaman_kerja[${i}][gaji]" value="${v.gaji ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Urutan</label>
                            <input type="number" class="form-control" name="pengalaman_kerja[${i}][urutan]" value="${v.urutan ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                `);
            }

            function organisasiRow(i, v = {}) {
                const activeVal = (v.is_active === 0 || v.is_active === '0') ? '0' : '1';
                return rowCard(`
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-semibold">Organisasi #${i+1}</div>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">Remove</button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Nama</label>
                            <input class="form-control" name="organisasi[${i}][nama]" value="${v.nama ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Posisi</label>
                            <input class="form-control" name="organisasi[${i}][posisi]" value="${v.posisi ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Active</label>
                            <select class="form-select" name="organisasi[${i}][is_active]">
                                <option value="1" ${activeVal === '1' ? 'selected' : ''}>Yes</option>
                                <option value="0" ${activeVal === '0' ? 'selected' : ''}>No</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Urutan</label>
                            <input type="number" class="form-control" name="organisasi[${i}][urutan]" value="${v.urutan ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                `);
            }

            function bahasaRow(i, v = {}) {
                return rowCard(`
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-semibold">Bahasa #${i+1}</div>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">Remove</button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Bahasa</label>
                            <input class="form-control" name="bahasa[${i}][bahasa_asing]" value="${v.bahasa_asing ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Berbicara</label>
                            <input class="form-control" name="bahasa[${i}][kemampuan_berbicara]" value="${v.kemampuan_berbicara ?? ''}" placeholder="Baik/Cukup/...">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Menulis</label>
                            <input class="form-control" name="bahasa[${i}][kemampuan_menulis]" value="${v.kemampuan_menulis ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Membaca</label>
                            <input class="form-control" name="bahasa[${i}][kemampuan_membaca]" value="${v.kemampuan_membaca ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Urutan</label>
                            <input type="number" class="form-control" name="bahasa[${i}][urutan]" value="${v.urutan ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                `);
            }

            function anakRow(i, v = {}) {
                return rowCard(`
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-semibold">Anak #${i+1}</div>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">Remove</button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Anak ke-</label>
                            <input type="number" class="form-control" name="anak[${i}][anak_ke]" value="${v.anak_ke ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nama</label>
                            <input class="form-control" name="anak[${i}][nama]" value="${v.nama ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <input class="form-control" name="anak[${i}][jenis_kelamin]" value="${v.jenis_kelamin ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="anak[${i}][tanggal_lahir]" value="${v.tanggal_lahir ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tempat Lahir</label>
                            <input class="form-control" name="anak[${i}][tempat_lahir]" value="${v.tempat_lahir ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <input class="form-control" name="anak[${i}][pendidikan_terakhir]" value="${v.pendidikan_terakhir ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                `);
            }

            function saudaraRow(i, v = {}) {
                return rowCard(`
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-semibold">Saudara #${i+1}</div>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">Remove</button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Anak ke-</label>
                            <input type="number" class="form-control" name="saudara[${i}][anak_ke]" value="${v.anak_ke ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Nama</label>
                            <input class="form-control" name="saudara[${i}][nama]" value="${v.nama ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <input class="form-control" name="saudara[${i}][jenis_kelamin]" value="${v.jenis_kelamin ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="saudara[${i}][tanggal_lahir]" value="${v.tanggal_lahir ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <input class="form-control" name="saudara[${i}][pendidikan_terakhir]" value="${v.pendidikan_terakhir ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pekerjaan</label>
                            <input class="form-control" name="saudara[${i}][pekerjaan]" value="${v.pekerjaan ?? ''}">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                `);
            }

            function applySelect2Hist($container) {
                initSelect2($container.find('.select2-jabatan-hist'), jabatanUrl, 'Pilih Jabatan');
                initSelect2($container.find('.select2-department-hist'), departmentUrl, 'Pilih Department');
                initSelect2($container.find('.select2-company-hist'), companyUrl, 'Pilih Company');
            }

            const existing = @json($existing);


            function initListFromExisting(listKey, $listEl, rowBuilder, idxRefName, afterAppend) {
                const rows = existing[listKey] || [];
                if (!rows.length) {
                    // minimal 1 row
                    $listEl.append(rowBuilder(window[idxRefName]++));
                    if (afterAppend) afterAppend($listEl.find('.card').last(), null);
                    return;
                }
                rows.forEach((v) => {
                    $listEl.append(rowBuilder(window[idxRefName]++, v));
                    if (afterAppend) afterAppend($listEl.find('.card').last(), v);
                });
            }

            // local counter holders on window for helper
            window.idxRiwayatJabatan = 0;
            window.idxPendidikan = 0;
            window.idxPengalaman = 0;
            window.idxOrganisasi = 0;
            window.idxBahasa = 0;
            window.idxAnak = 0;
            window.idxSaudara = 0;

            initListFromExisting('jabatans', $('#listRiwayatJabatan'), riwayatJabatanRow, 'idxRiwayatJabatan', ($card) => applySelect2Hist($card));
            initListFromExisting('pendidikan', $('#listPendidikan'), pendidikanRow, 'idxPendidikan');
            initListFromExisting('pengalaman_kerja', $('#listPengalaman'), pengalamanRow, 'idxPengalaman');
            initListFromExisting('organisasi', $('#listOrganisasi'), organisasiRow, 'idxOrganisasi');
            initListFromExisting('bahasa', $('#listBahasa'), bahasaRow, 'idxBahasa');
            initListFromExisting('anak', $('#listAnak'), anakRow, 'idxAnak');
            initListFromExisting('saudara', $('#listSaudara'), saudaraRow, 'idxSaudara');

            // add row handlers
            $('#btnAddRiwayatJabatan').on('click', () => {
                $('#listRiwayatJabatan').append(riwayatJabatanRow(window.idxRiwayatJabatan++));
                applySelect2Hist($('#listRiwayatJabatan .card').last());
            });

            $('#btnAddPendidikan').on('click', () => $('#listPendidikan').append(pendidikanRow(window.idxPendidikan++)));
            $('#btnAddPengalaman').on('click', () => $('#listPengalaman').append(pengalamanRow(window.idxPengalaman++)));
            $('#btnAddOrganisasi').on('click', () => $('#listOrganisasi').append(organisasiRow(window.idxOrganisasi++)));
            $('#btnAddBahasa').on('click', () => $('#listBahasa').append(bahasaRow(window.idxBahasa++)));
            $('#btnAddAnak').on('click', () => $('#listAnak').append(anakRow(window.idxAnak++)));
            $('#btnAddSaudara').on('click', () => $('#listSaudara').append(saudaraRow(window.idxSaudara++)));

            // remove row
            $(document).on('click', '.btn-remove-row', function () {
                const $card = $(this).closest('.card');
                $card.find('select.select2-hidden-accessible').each(function () {
                    try { $(this).select2('destroy'); } catch (e) {}
                });
                $card.remove();
            });

            // ===== Submit AJAX (FormData) =====
            $('#formEdit').on('submit', function (e) {
                e.preventDefault();
                resetFieldErrors();

                const formData = new FormData(this);
                $('#btnSubmit').prop('disabled', true);

                $.ajax({
                    url: updateUrl,
                    method: 'POST', // spoof PUT via _method
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
                    success: function (res) {
                        $('#btnSubmit').prop('disabled', false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res?.message || 'Karyawan updated',
                            confirmButtonText: 'OK'
                        }).then(() => window.location.href = indexUrl);
                    },
                    error: function (xhr) {
                        $('#btnSubmit').prop('disabled', false);

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON?.errors || {};
                            Object.keys(errors).forEach(key => setFieldError(key, errors[key][0]));
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please check the highlighted fields.',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message || 'Failed to update karyawan',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
