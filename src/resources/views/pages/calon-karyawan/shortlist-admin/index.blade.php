@extends('layouts.master')

@section('title', 'Calon Karyawan - Shortlist Admin')
@section('subtitle', 'Recruitment')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h4 class="mb-0">Shortlist Admin</h4>
                        <p class="text-muted mb-0">
                            Total: <span class="fw-bold">{{ $calonKaryawans->total() }}</span> calon karyawan
                            <span class="ms-2 badge bg-light-success text-dark">SHORTLIST_ADMIN</span>
                        </p>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                        <form id="filterForm" method="GET" action="{{ url()->current() }}"
                              class="d-flex flex-column flex-md-row gap-2">
                            @if(request()->filled('page'))
                                <input type="hidden" name="page" value="{{ request('page') }}">
                            @endif

                            <!-- Search Nama -->
                            <div class="input-group input-group-sm" style="min-width: 220px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input
                                    id="searchName"
                                    type="text"
                                    class="form-control"
                                    name="searchName"
                                    placeholder="Search Nama..."
                                    value="{{ request('searchName') }}"
                                    autocomplete="off"
                                />
                            </div>

                            <!-- Search Department -->
                            <div class="input-group input-group-sm" style="min-width: 220px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-diagram-3"></i></span>
                                <input
                                    id="searchDepartment"
                                    type="text"
                                    class="form-control"
                                    name="searchDepartment"
                                    placeholder="Search Department..."
                                    value="{{ request('searchDepartment') }}"
                                    autocomplete="off"
                                />
                            </div>

                            <!-- Search Company -->
                            <div class="input-group input-group-sm" style="min-width: 220px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-building"></i></span>
                                <input
                                    id="searchCompany"
                                    type="text"
                                    class="form-control"
                                    name="searchCompany"
                                    placeholder="Search Company..."
                                    value="{{ request('searchCompany') }}"
                                    autocomplete="off"
                                />
                            </div>

                            @php
                                $hasFilter = request()->filled('searchName')
                                    || request()->filled('searchDepartment')
                                    || request()->filled('searchCompany')
                                    || request()->filled('perPage');
                            @endphp

                            @if($hasFilter)
                                <a href="{{ url()->current() }}" class="btn btn-light btn-sm" style="max-height: 40px;">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif

                            <!-- Per page -->
                            <select id="perPage" name="perPage" class="form-select form-select-sm"
                                    style="min-width: 100px; max-height: 40px;">
                                @foreach([10, 20, 50, 100] as $n)
                                    <option value="{{ $n }}" @selected((int)request('perPage', 10) === $n)>
                                        {{ $n }}/page
                                    </option>
                                @endforeach
                            </select>

                            <button class="btn btn-primary btn-sm" type="submit" style="max-height: 40px;">
                                <i class="bi bi-funnel me-1"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($calonKaryawans->count() === 0)
                    <div class="alert alert-light-primary mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        Tidak ada calon karyawan (SHORTLIST_ADMIN).
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                            <tr>
                                <th style="width: 70px;">No</th>
                                <th>Nama</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Submitted At</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($calonKaryawans as $i => $ck)
                                <tr>
                                    <td class="text-muted">{{ $calonKaryawans->firstItem() + $i }}</td>

                                    <td class="fw-semibold">
                                        {{ $ck->nama_lengkap }}
                                        <div class="text-muted small">
                                            NIK: <span class="font-monospace">{{ $ck->nik }}</span>
                                        </div>
                                    </td>

                                    <td>{{ $ck->company_name }}</td>
                                    <td>{{ $ck->department_name }}</td>

                                    <td>
                                        @php($st = optional($ck->latestStatusRecruitment)->status)
                                        <span class="badge bg-light-success text-dark">
                                            {{ $st ?: '-' }}
                                        </span>
                                    </td>

                                    <td class="text-muted small">{{ optional($ck->created_at)->format('Y-m-d H:i') }}</td>

                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ route('admin.calon-karyawan.shortlist-admin.show', $ck->id) }}">
                                                        <i class="bi bi-eye me-2"></i> Show
                                                    </a>
                                                </li>

                                                <li><hr class="dropdown-divider"></li>

                                                <li>
                                                    <button type="button"
                                                            class="dropdown-item btn-update-status"
                                                            data-url="{{ route('admin.calon-karyawan.update-status-recruitment', $ck->id) }}"
                                                            data-name="{{ $ck->nama_lengkap }}"
                                                            data-status="TES_TULIS">
                                                        <i class="bi bi-journal-check me-2"></i> Lanjut Tes Tulis
                                                    </button>
                                                </li>

                                                <li>
                                                    <button type="button"
                                                            class="dropdown-item text-warning btn-update-status"
                                                            data-url="{{ route('admin.calon-karyawan.update-status-recruitment', $ck->id) }}"
                                                            data-name="{{ $ck->nama_lengkap }}"
                                                            data-status="REJECTED">
                                                        <i class="bi bi-x-octagon me-2"></i> Reject
                                                    </button>
                                                </li>

                                                <li><hr class="dropdown-divider"></li>

                                                <li>
                                                    <button type="button"
                                                            class="dropdown-item text-danger btn-delete-calon"
                                                            data-url="{{ route('admin.calon-karyawan.shortlist-admin.destroy', $ck->id) }}"
                                                            data-name="{{ $ck->nama_lengkap }}"
                                                            data-nik="{{ $ck->nik }}">
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
                            Showing <b>{{ $calonKaryawans->firstItem() }}</b> to <b>{{ $calonKaryawans->lastItem() }}</b>
                            of <b>{{ $calonKaryawans->total() }}</b> results
                        </div>

                        <div>{{ $calonKaryawans->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const indexUrl = @json(route('admin.calon-karyawan.shortlist-admin.index'));
            const csrf = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

            const $form = $('#filterForm');
            const $searchName       = $('#searchName');
            const $searchDepartment = $('#searchDepartment');
            const $searchCompany    = $('#searchCompany');
            const $perPage          = $('#perPage');

            function debounce(fn, wait) {
                let t;
                return function (...args) {
                    clearTimeout(t);
                    t = setTimeout(() => fn.apply(this, args), wait);
                };
            }

            function submitFilter(resetPage = true) {
                if (!$form.length) return;
                if (resetPage) $form.find('input[name="page"]').remove();
                $form.trigger('submit');
            }

            const debouncedSubmit = debounce(() => submitFilter(true), 400);

            $searchName.on('input', debouncedSubmit);
            $searchDepartment.on('input', debouncedSubmit);
            $searchCompany.on('input', debouncedSubmit);
            $perPage.on('change', () => submitFilter(true));

            // ===== Update status =====
            $(document).on('click', '.btn-update-status', function () {
                const url = $(this).data('url');
                const name = $(this).data('name') || 'calon karyawan';
                const status = $(this).data('status');

                const labelMap = {
                    TES_TULIS: 'Tes Tulis',
                    REJECTED: 'Rejected'
                };

                const isReject = status === 'REJECTED';

                Swal.fire({
                    title: isReject ? 'Reject calon karyawan?' : 'Lanjutkan ke Tes Tulis?',
                    html: isReject
                        ? `Yakin reject <b>${name}</b>?<br><small class="text-muted">Aksi ini akan mengubah status recruitment.</small>`
                        : `Yakin lanjutkan <b>${name}</b> ke <b>${labelMap[status] || status}</b>?<br><small class="text-muted">Aksi ini akan mengubah status recruitment.</small>`,
                    icon: isReject ? 'warning' : 'question',
                    showCancelButton: true,
                    confirmButtonText: isReject ? 'Ya, Reject' : 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: isReject ? '#d33' : '#3085d6'
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

            // ===== Delete =====
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
