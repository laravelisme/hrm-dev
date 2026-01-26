@extends('layouts.master')

@section('title', 'Master Data - Karyawan')
@section('subtitle', 'Master Data Karyawan')

@push('styles')
    <style>
        .input-group-sm .select2-container--bootstrap-5 .select2-selection {
            min-height: calc(1.5em + .5rem + 2px) !important;
            height: calc(1.5em + .5rem + 2px) !important;
            padding: .25rem .5rem !important;
            font-size: .875rem !important;
            border-radius: 0 .2rem .2rem 0 !important;
        }

        .input-group-sm .select2-container--bootstrap-5 .select2-selection__rendered {
            line-height: calc(1.5em + .5rem) !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .input-group-sm .select2-container--bootstrap-5 .select2-selection__arrow {
            height: calc(1.5em + .5rem + 2px) !important;
        }

        .input-group-sm .select2-container {
            width: 1% !important;
            flex: 1 1 auto !important;
        }

        .input-group-sm .select2-container--bootstrap-5 .select2-selection {
            border-left: 0 !important;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h4 class="mb-0">Karyawan</h4>
                        <p class="text-muted mb-0">
                            Total: <span class="fw-bold">{{ $karyawans->total() }}</span> karyawan
                        </p>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                        <form id="filterForm" method="GET" action="{{ url()->current() }}" class="d-flex flex-column flex-md-row gap-2">

                            @if(request()->filled('page'))
                                <input type="hidden" name="page" value="{{ request('page') }}">
                            @endif

                            {{-- Search Nama --}}
                            <div class="input-group input-group-sm" style="min-width: 200px;">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" id="searchName" name="searchName" class="form-control"
                                       placeholder="Search Nama..." value="{{ request('searchName') }}">
                            </div>

                            {{-- Search NIK --}}
                            <div class="input-group input-group-sm" style="min-width: 160px;">
                                <span class="input-group-text"><i class="bi bi-credit-card"></i></span>
                                <input type="text" id="searchNik" name="searchNik" class="form-control"
                                       placeholder="Search NIK..." value="{{ request('searchNik') }}">
                            </div>

                                {{-- Jabatan --}}
                                <div class="input-group input-group-sm" style="min-width:220px;">
                                    <span class="input-group-text"><i class="bi bi-briefcase"></i></span>
                                    <select id="searchJabatan" name="searchJabatan" class="form-select select2-jabatan">
                                        <option value="">All Jabatan</option>
                                        @isset($selectedJabatan)
                                            <option value="{{ $selectedJabatan->id }}" selected>{{ $selectedJabatan->nama_jabatan }}</option>
                                        @endisset
                                    </select>
                                </div>

                                {{-- Department --}}
                                <div class="input-group input-group-sm" style="min-width:220px;">
                                    <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
                                    <select id="searchDepartment" name="searchDepartment" class="form-select select2-department">
                                        <option value="">All Department</option>
                                        @isset($selectedDepartment)
                                            <option value="{{ $selectedDepartment->id }}" selected>{{ $selectedDepartment->nama_departement }}</option>
                                        @endisset
                                    </select>
                                </div>

                                {{-- Company --}}
                                <div class="input-group input-group-sm" style="min-width:220px;">
                                    <span class="input-group-text"><i class="bi bi-building"></i></span>
                                    <select id="searchCompany" name="searchCompany" class="form-select select2-company">
                                        <option value="">All Company</option>
                                        @isset($selectedCompany)
                                            <option value="{{ $selectedCompany->id }}" selected>{{ $selectedCompany->nama_company }}</option>
                                        @endisset
                                    </select>
                                </div>

                                @php
                                $hasFilter = request()->filled('searchName')
                                    || request()->filled('searchNik')
                                    || request()->filled('searchJabatan')
                                    || request()->filled('searchDepartment')
                                    || request()->filled('searchCompany')
                                    || request()->filled('perPage');
                            @endphp

                            @if($hasFilter)
                                <a href="{{ url()->current() }}" class="btn btn-light btn-sm">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif

                            {{-- Per Page --}}
                            <select id="perPage" name="perPage" class="form-select form-select-sm" style="min-width:100px;">
                                @foreach([10,20,50,100] as $n)
                                    <option value="{{ $n }}" @selected((int)request('perPage',10)===$n)>
                                        {{ $n }}/page
                                    </option>
                                @endforeach
                            </select>

                            <button class="btn btn-primary btn-sm" type="submit">
                                <i class="bi bi-funnel me-1"></i>
                            </button>
                        </form>

                        <a href="{{ route('admin.karyawan.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Karyawan
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($karyawans->count() === 0)
                    <div class="alert alert-light-primary mb-0">
                        <i class="bi bi-info-circle me-1"></i> No karyawan found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                            <tr>
                                <th style="width:70px;">No</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Email</th>
                                <th>Jabatan</th>
                                <th>Department</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th class="text-end" style="width:120px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($karyawans as $i => $k)
                                <tr>
                                    <td class="text-muted">{{ $karyawans->firstItem() + $i }}</td>
                                    <td class="fw-semibold">{{ $k->nama_karyawan }}</td>
                                    <td>{{ $k->nik }}</td>
                                    <td class="text-muted small">{{ $k->email }}</td>
                                    <td>{{ $k->nama_jabatan ?? '-' }}</td>
                                    <td>{{ $k->nama_departement ?? '-' }}</td>
                                    <td>{{ $k->nama_company ?? '-' }}</td>
                                    <td>
                                    <span class="badge {{ $k->is_active ? 'bg-light-success text-success' : 'bg-light-secondary text-muted' }}">
                                        {{ $k->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.karyawan.edit', $k->id) }}">
                                                        <i class="bi bi-pencil-square me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button"
                                                            class="dropdown-item text-danger btn-delete-karyawan"
                                                            data-url="{{ route('admin.karyawan.destroy', $k->id) }}"
                                                            data-name="{{ $k->nama_karyawan }}">
                                                        <i class="bi bi-trash me-2"></i> Delete
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
                        <div class="text-muted small">
                            Showing <b>{{ $karyawans->firstItem() }}</b> to <b>{{ $karyawans->lastItem() }}</b>
                            of <b>{{ $karyawans->total() }}</b> results
                        </div>
                        <div>{{ $karyawans->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const indexUrl = @json(route('admin.karyawan.index'));
            const csrf = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

            const $form = $('#filterForm');

            function debounce(fn, wait) {
                let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), wait); };
            }

            function submitFilter(resetPage = true) {
                if (!$form.length) return;
                if (resetPage) $form.find('input[name="page"]').remove();
                $form.trigger('submit');
            }

            // input text
            $('#searchName, #searchNik').on('input', debounce(() => submitFilter(true), 400));

            // per page
            $('#perPage').on('change', () => submitFilter(true));

            const jabatanUrl    = @json(route('admin.karyawan.options.jabatan'));
            const departmentUrl = @json(route('admin.karyawan.options.department'));
            const companyUrl    = @json(route('admin.karyawan.options.company'));

            function initSelect2(selector, url, placeholder) {
                $(selector).each(function () {
                    const $el = $(this);

                    $el.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        placeholder,
                        allowClear: true,
                        minimumInputLength: 0,       // biar bisa langsung buka tanpa ngetik
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
                                    pagination: { more: !!data.pagination?.more }
                                };
                            },
                            cache: true
                        },
                        dropdownParent: $el.closest('.input-group'),
                        language: {
                            searching: () => 'Mencari...',
                            noResults: () => 'Data tidak ditemukan'
                        }
                    });

                    // penting: change select2 harus reset page
                    $el.on('change', () => submitFilter(true));
                });
            }

            initSelect2('.select2-jabatan', jabatanUrl, 'All Jabatan');
            initSelect2('.select2-department', departmentUrl, 'All Department');
            initSelect2('.select2-company', companyUrl, 'All Company');

            // Delete (punyamu udah oke)
            $(document).on('click', '.btn-delete-karyawan', function () {
                const url  = $(this).data('url');
                const name = $(this).data('name');

                Swal.fire({
                    title: 'Delete karyawan?',
                    html: `Hapus <b>${name}</b>?<br><small class="text-muted">Data tidak bisa dikembalikan.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete',
                    confirmButtonColor: '#d33'
                }).then(result => {
                    if (!result.isConfirmed) return;

                    $.post(url, { _token: csrf, _method: 'DELETE' })
                        .done(res => {
                            Swal.fire('Deleted', res.message, 'success')
                                .then(() => window.location.href = indexUrl);
                        })
                        .fail(() => {
                            Swal.fire('Error', 'Failed to delete karyawan', 'error');
                        });
                });
            });
        });
    </script>
@endpush
