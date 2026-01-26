@extends('layouts.master')

@section('title', 'Master Data - Karyawan')
@section('subtitle', 'Tambah Karyawan')

@section('content')
    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Create New Karyawan</h4>
                        <a href="{{ route('admin.karyawan.index') }}" class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>

                    <div class="card-body">
                        <form id="formCreate">
                            @csrf

                            {{-- Tabs --}}
                            <ul class="nav nav-tabs" id="karyawanTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-utama" type="button">Data Utama</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-jabatan" type="button">Jabatan</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pendidikan" type="button">Pendidikan</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-pengalaman" type="button">Pengalaman</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-organisasi" type="button">Organisasi</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-bahasa" type="button">Bahasa</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-keluarga" type="button">Keluarga</button>
                                </li>
                            </ul>

                            <div class="tab-content border border-top-0 p-3">
                                {{-- ================== TAB UTAMA ================== --}}
                                <div class="tab-pane fade show active" id="tab-utama">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Kode Karyawan <small class="text-muted">(opsional, auto)</small></label>
                                            <input type="text" class="form-control" name="kode_karyawan" placeholder="KRY-000001">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Nama Karyawan <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nama_karyawan" placeholder="Nama lengkap">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">NIK <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nik" placeholder="NIK">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" placeholder="email@domain.com">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">No HP</label>
                                            <input type="text" class="form-control" name="no_hp" placeholder="08xxxx">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Tempat Lahir</label>
                                            <input type="text" class="form-control" name="tempat_lahir">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Tanggal Lahir</label>
                                            <input type="date" class="form-control" name="tanggal_lahir">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Jenis Kelamin</label>
                                            <select class="form-select" name="jenis_kelamin">
                                                <option value="">- pilih -</option>
                                                <option value="LAKI-LAKI">LAKI-LAKI</option>
                                                <option value="PEREMPUAN">PEREMPUAN</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Alamat KTP</label>
                                            <input type="text" class="form-control" name="alamat_ktp">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Alamat Domisili</label>
                                            <input type="text" class="form-control" name="alamat_domisili">
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Tanggal Bergabung</label>
                                            <input type="date" class="form-control" name="tanggal_bergabung">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Status Karyawan</label>
                                            <input type="text" class="form-control" name="status_karyawan" placeholder="Tetap/Kontrak/...">
                                            <div class="invalid-feedback"></div>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-end">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active">
                                                <label class="form-check-label" for="is_active">Active</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ================== TAB JABATAN ================== --}}
                                <div class="tab-pane fade" id="tab-jabatan">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Jabatan</label>
                                            <select name="m_jabatan_id" class="form-select select2-jabatan">
                                                <option value="">- pilih jabatan -</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Department</label>
                                            <select name="m_department_id" class="form-select select2-department">
                                                <option value="">- pilih department -</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Company</label>
                                            <select name="m_company_id" class="form-select select2-company">
                                                <option value="">- pilih company -</option>
                                            </select>
                                            <div class="invalid-feedback"></div>
                                        </div>
                                    </div>

                                    <div class="alert alert-light mt-3 mb-0">
                                        <small class="text-muted">
                                            Saat submit: sistem akan mengisi <b>nama_jabatan / nama_departement / nama_company</b> di `m_karyawans` dan membuat 1 row job history di `m_karyawan_jabatans`.
                                        </small>
                                    </div>
                                </div>

                                {{-- ================== TAB PENDIDIKAN ================== --}}
                                <div class="tab-pane fade" id="tab-pendidikan">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-semibold">Daftar Pendidikan</div>
                                        <button type="button" class="btn btn-sm btn-light" id="btnAddPendidikan">
                                            <i class="bi bi-plus-lg me-1"></i> Add
                                        </button>
                                    </div>
                                    <div id="listPendidikan"></div>
                                </div>

                                {{-- ================== TAB PENGALAMAN ================== --}}
                                <div class="tab-pane fade" id="tab-pengalaman">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-semibold">Pengalaman Kerja</div>
                                        <button type="button" class="btn btn-sm btn-light" id="btnAddPengalaman">
                                            <i class="bi bi-plus-lg me-1"></i> Add
                                        </button>
                                    </div>
                                    <div id="listPengalaman"></div>
                                </div>

                                {{-- ================== TAB ORGANISASI ================== --}}
                                <div class="tab-pane fade" id="tab-organisasi">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-semibold">Organisasi</div>
                                        <button type="button" class="btn btn-sm btn-light" id="btnAddOrganisasi">
                                            <i class="bi bi-plus-lg me-1"></i> Add
                                        </button>
                                    </div>
                                    <div id="listOrganisasi"></div>
                                </div>

                                {{-- ================== TAB BAHASA ================== --}}
                                <div class="tab-pane fade" id="tab-bahasa">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="fw-semibold">Bahasa Asing</div>
                                        <button type="button" class="btn btn-sm btn-light" id="btnAddBahasa">
                                            <i class="bi bi-plus-lg me-1"></i> Add
                                        </button>
                                    </div>
                                    <div id="listBahasa"></div>
                                </div>

                                {{-- ================== TAB KELUARGA (anak & saudara) ================== --}}
                                <div class="tab-pane fade" id="tab-keluarga">
                                    <div class="row g-4">

                                        <div class="col-12">
                                            <div class="fw-semibold mb-2">Orang Tua</div>
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Nama Ayah</label>
                                                    <input class="form-control" name="nama_ayah">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Tanggal Lahir Ayah</label>
                                                    <input type="date" class="form-control" name="tanggal_lahir_ayah">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Pekerjaan Ayah</label>
                                                    <input class="form-control" name="pekerjaan_ayah">
                                                    <div class="invalid-feedback"></div>
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label">Nama Ibu</label>
                                                    <input class="form-control" name="nama_ibu">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Tanggal Lahir Ibu</label>
                                                    <input type="date" class="form-control" name="tanggal_lahir_ibu">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Pekerjaan Ibu</label>
                                                    <input class="form-control" name="pekerjaan_ibu">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>

                                            <div class="alert alert-light mt-3 mb-0">
                                                <small class="text-muted">
                                                    Saat create: sistem otomatis membuat user di <b>p_users</b> dengan password default = <b>NIK</b>.
                                                </small>
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="fw-semibold">Anak</div>
                                                <button type="button" class="btn btn-sm btn-light" id="btnAddAnak">
                                                    <i class="bi bi-plus-lg me-1"></i> Add
                                                </button>
                                            </div>
                                            <div id="listAnak"></div>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="fw-semibold">Saudara</div>
                                                <button type="button" class="btn btn-sm btn-light" id="btnAddSaudara">
                                                    <i class="bi bi-plus-lg me-1"></i> Add
                                                </button>
                                            </div>
                                            <div id="listSaudara"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-3">
                                <a href="{{ route('admin.karyawan.index') }}" class="btn btn-light">Cancel</a>
                                <button type="submit" class="btn btn-primary" id="btnSubmit">
                                    <i class="bi bi-save me-1"></i> Save Karyawan
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
            const storeUrl = @json(route('admin.karyawan.store'));
            const indexUrl = @json(route('admin.karyawan.index'));

            const jabatanUrl    = @json(route('admin.karyawan.options.jabatan'));
            const departmentUrl = @json(route('admin.karyawan.options.department'));
            const companyUrl    = @json(route('admin.karyawan.options.company'));

            // ===== Select2 init =====
            function initSelect2(selector, url, placeholder) {
                $(selector).select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder,
                    allowClear: true,
                    minimumInputLength: 0,
                    dropdownAutoWidth: true,
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

            initSelect2('.select2-jabatan', jabatanUrl, 'Pilih Jabatan');
            initSelect2('.select2-department', departmentUrl, 'Pilih Department');
            initSelect2('.select2-company', companyUrl, 'Pilih Company');

            // ===== Helpers: error handling =====
            function resetFieldErrors() {
                $('#formCreate .is-invalid').removeClass('is-invalid');
                $('#formCreate .invalid-feedback').text('');
                // select2 invalid
                $('#formCreate .select2-selection').removeClass('is-invalid');
            }

            // Laravel error key: pendidikan.0.nama -> pendidikan[0][nama]
            function dotKeyToName(dotKey) {
                return dotKey
                    .replace(/\.(\d+)\./g, '[$1][')
                    .replace(/\./g, '][') + (dotKey.includes('.') ? ']' : '');
            }

            function setFieldError(dotKey, message) {
                const name = dotKeyToName(dotKey);
                const $input = $('#formCreate [name="' + name + '"]');

                if (!$input.length) return;

                $input.addClass('is-invalid');

                // select2
                if ($input.hasClass('select2-hidden-accessible')) {
                    $input.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                }

                const $fb = $input.closest('.col-md-4,.col-md-6,.col-md-12,.col-12').find('.invalid-feedback').first();
                if ($fb.length) $fb.text(message);
                else $input.next('.invalid-feedback').text(message);
            }

            // ===== Repeater Templates =====
            let idxPendidikan = 0, idxPengalaman = 0, idxOrganisasi = 0, idxBahasa = 0, idxAnak = 0, idxSaudara = 0;

            function rowCard(html) {
                return `<div class="card border mb-2">
            <div class="card-body py-3">${html}</div>
        </div>`;
            }

            function pendidikanRow(i) {
                return rowCard(`
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">Pendidikan #${i+1}</div>
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">Remove</button>
            </div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Jenjang</label>
                    <input class="form-control" name="pendidikan[${i}][jenjang_pendidikan]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nama</label>
                    <input class="form-control" name="pendidikan[${i}][nama]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Program Studi</label>
                    <input class="form-control" name="pendidikan[${i}][program_studi]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun Masuk</label>
                    <input class="form-control" name="pendidikan[${i}][tahun_masuk]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun Lulus</label>
                    <input class="form-control" name="pendidikan[${i}][tahun_lulus]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Urutan</label>
                    <input type="number" class="form-control" name="pendidikan[${i}][urutan]">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        `);
            }

            function pengalamanRow(i) {
                return rowCard(`
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">Pengalaman #${i+1}</div>
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">Remove</button>
            </div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Perusahaan</label>
                    <input class="form-control" name="pengalaman_kerja[${i}][nama_perusahaan]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jabatan</label>
                    <input class="form-control" name="pengalaman_kerja[${i}][jabatan]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Mulai</label>
                    <input class="form-control" name="pengalaman_kerja[${i}][tahun_mulai]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Selesai</label>
                    <input class="form-control" name="pengalaman_kerja[${i}][tahun_selesai]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tugas</label>
                    <input class="form-control" name="pengalaman_kerja[${i}][riwayat_tugas]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Alamat Perusahaan</label>
                    <input class="form-control" name="pengalaman_kerja[${i}][riwayat_alamat_perusahaan]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Alasan Berhenti</label>
                    <input class="form-control" name="pengalaman_kerja[${i}][riwayat_berhenti]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gaji</label>
                    <input class="form-control" name="pengalaman_kerja[${i}][gaji]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Urutan</label>
                    <input type="number" class="form-control" name="pengalaman_kerja[${i}][urutan]">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        `);
            }

            function organisasiRow(i) {
                return rowCard(`
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">Organisasi #${i+1}</div>
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">Remove</button>
            </div>
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Nama</label>
                    <input class="form-control" name="organisasi[${i}][nama]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Posisi</label>
                    <input class="form-control" name="organisasi[${i}][posisi]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Active</label>
                    <select class="form-select" name="organisasi[${i}][is_active]">
                        <option value="1" selected>Yes</option>
                        <option value="0">No</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Urutan</label>
                    <input type="number" class="form-control" name="organisasi[${i}][urutan]">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        `);
            }

            function bahasaRow(i) {
                return rowCard(`
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">Bahasa #${i+1}</div>
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">Remove</button>
            </div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Bahasa</label>
                    <input class="form-control" name="bahasa[${i}][bahasa_asing]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Berbicara</label>
                    <input class="form-control" name="bahasa[${i}][kemampuan_berbicara]" placeholder="Baik/Cukup/...">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Menulis</label>
                    <input class="form-control" name="bahasa[${i}][kemampuan_menulis]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Membaca</label>
                    <input class="form-control" name="bahasa[${i}][kemampuan_membaca]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Urutan</label>
                    <input type="number" class="form-control" name="bahasa[${i}][urutan]">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        `);
            }

            function anakRow(i) {
                return rowCard(`
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">Anak #${i+1}</div>
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">Remove</button>
            </div>
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Anak ke-</label>
                    <input type="number" class="form-control" name="anak[${i}][anak_ke]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama</label>
                    <input class="form-control" name="anak[${i}][nama]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <input class="form-control" name="anak[${i}][jenis_kelamin]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="anak[${i}][tanggal_lahir]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tempat Lahir</label>
                    <input class="form-control" name="anak[${i}][tempat_lahir]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pendidikan Terakhir</label>
                    <input class="form-control" name="anak[${i}][pendidikan_terakhir]">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        `);
            }

            function saudaraRow(i) {
                return rowCard(`
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">Saudara #${i+1}</div>
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">Remove</button>
            </div>
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label">Anak ke-</label>
                    <input type="number" class="form-control" name="saudara[${i}][anak_ke]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama</label>
                    <input class="form-control" name="saudara[${i}][nama]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <input class="form-control" name="saudara[${i}][jenis_kelamin]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="saudara[${i}][tanggal_lahir]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pendidikan Terakhir</label>
                    <input class="form-control" name="saudara[${i}][pendidikan_terakhir]">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pekerjaan</label>
                    <input class="form-control" name="saudara[${i}][pekerjaan]">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        `);
            }

            // init minimal 1 row each section (opsional)
            $('#listPendidikan').append(pendidikanRow(idxPendidikan++));
            $('#listPengalaman').append(pengalamanRow(idxPengalaman++));
            $('#listOrganisasi').append(organisasiRow(idxOrganisasi++));
            $('#listBahasa').append(bahasaRow(idxBahasa++));
            $('#listAnak').append(anakRow(idxAnak++));
            $('#listSaudara').append(saudaraRow(idxSaudara++));

            // add row handlers
            $('#btnAddPendidikan').on('click', () => $('#listPendidikan').append(pendidikanRow(idxPendidikan++)));
            $('#btnAddPengalaman').on('click', () => $('#listPengalaman').append(pengalamanRow(idxPengalaman++)));
            $('#btnAddOrganisasi').on('click', () => $('#listOrganisasi').append(organisasiRow(idxOrganisasi++)));
            $('#btnAddBahasa').on('click', () => $('#listBahasa').append(bahasaRow(idxBahasa++)));
            $('#btnAddAnak').on('click', () => $('#listAnak').append(anakRow(idxAnak++)));
            $('#btnAddSaudara').on('click', () => $('#listSaudara').append(saudaraRow(idxSaudara++)));

            // remove row
            $(document).on('click', '.btn-remove-row', function () {
                $(this).closest('.card').remove();
            });

            // ===== Submit AJAX =====
            $('#formCreate').on('submit', function (e) {
                e.preventDefault();
                resetFieldErrors();

                const formData = $(this).serialize();
                $('#btnSubmit').prop('disabled', true);

                $.ajax({
                    url: storeUrl,
                    method: 'POST',
                    data: formData,
                    headers: { 'X-CSRF-TOKEN': $('input[name="_token"]').val() },
                    success: function (res) {
                        $('#btnSubmit').prop('disabled', false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: res?.message || 'Karyawan created',
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
                            text: xhr.responseJSON?.message || 'Failed to create karyawan',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });
    </script>
@endpush
