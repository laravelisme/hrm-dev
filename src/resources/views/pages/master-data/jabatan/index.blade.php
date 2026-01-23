@extends('layouts.master')

@section('title', 'Master Data - Jabatan')
@section('subtitle', 'Master Data Jabatan')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h4 class="mb-0">Jabatan</h4>
                        <p class="text-muted mb-0">
                            Total: <span class="fw-bold">{{ $jabatans->total() }}</span> jabatans
                        </p>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                        <form id="filterForm" method="GET" action="{{ url()->current() }}" class="d-flex flex-column flex-md-row gap-2">
                            {{-- Keep current page if exists (akan dihapus via JS saat filter berubah) --}}
                            @if(request()->filled('page'))
                                <input type="hidden" name="page" value="{{ request('page') }}">
                            @endif

                            <!-- Search Name -->
                            <div class="input-group input-group-sm" style="min-width: 180px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                <input
                                    id="searchName"
                                    type="text"
                                    class="form-control"
                                    name="searchName"
                                    placeholder="Search Name..."
                                    value="{{ request('searchName') }}"
                                    autocomplete="off"
                                />
                            </div>

                            <!-- Search Kode -->
                            <div class="input-group input-group-sm" style="min-width: 120px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input
                                    id="searchKode"
                                    type="text"
                                    class="form-control"
                                    name="searchKode"
                                    placeholder="Search Kode..."
                                    value="{{ request('searchKode') }}"
                                    autocomplete="off"
                                />
                            </div>

                            <!-- Search Level -->
                            <div class="input-group input-group-sm" style="min-width: 120px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-bar-chart"></i></span>
                                <input
                                    id="searchLevel"
                                    type="text"
                                    class="form-control"
                                    name="searchLevel"
                                    placeholder="Search Level..."
                                    value="{{ request('searchLevel') }}"
                                    autocomplete="off"
                                />
                            </div>

                            @php
                                $hasFilter =
                                    request()->filled('searchName') ||
                                    request()->filled('searchKode') ||
                                    request()->filled('searchLevel') ||
                                    request()->filled('perPage');
                            @endphp

                            @if($hasFilter)
                                <a href="{{ url()->current() }}" class="btn btn-light btn-sm" style="max-height: 40px;">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif

                            <!-- Per page -->
                            <select id="perPage" name="perPage" class="form-select form-select-sm" style="min-width: 100px; max-height: 40px;">
                                @foreach([10, 20, 50, 100] as $n)
                                    <option value="{{ $n }}" @selected((int)request('perPage', 10) === $n)>
                                        {{ $n }}/page
                                    </option>
                                @endforeach
                            </select>

                            {{-- tombol filter masih boleh, tapi sekarang optional --}}
                            <button class="btn btn-primary btn-sm" type="submit" style="max-height: 40px;">
                                <i class="bi bi-funnel me-1"></i>
                            </button>
                        </form>

                        <a href="{{ route('admin.master-data.jabatan.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Jabatan
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($jabatans->count() === 0)
                    <div class="alert alert-light-primary mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        No jabatan found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                            <tr>
                                <th style="width: 70px;">No</th>
                                <th>Kode</th>
                                <th>Name</th>
                                <th>Level</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($jabatans as $i => $jabatan)
                                <tr>
                                    <td class="text-muted">{{ $jabatans->firstItem() + $i }}</td>
                                    <td>{{ $jabatan->kode }}</td>
                                    <td>{{ $jabatan->name }}</td>
                                    <td>{{ $jabatan->level }}</td>
                                    <td class="text-muted small">{{ optional($jabatan->created_at)->format('Y-m-d H:i') }}</td>
                                    <td class="text-muted small">{{ optional($jabatan->updated_at)->format('Y-m-d H:i') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.master-data.jabatan.edit', $jabatan->id) }}">
                                                        <i class="bi bi-pencil-square me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger btn-delete-jabatan"
                                                            data-url="{{ route('admin.master-data.jabatan.destroy', $jabatan->id) }}"
                                                            data-name="{{ $jabatan->name }}">
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
                            Showing <b>{{ $jabatans->firstItem() }}</b> to <b>{{ $jabatans->lastItem() }}</b>
                            of <b>{{ $jabatans->total() }}</b> results
                        </div>

                        <div>{{ $jabatans->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const indexUrl = @json(route('admin.master-data.jabatan.index'));
            const csrf = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

            const $form = $('#filterForm');
            const $searchName = $('#searchName');
            const $searchKode = $('#searchKode');
            const $searchLevel = $('#searchLevel');
            const $perPage = $('#perPage');

            function debounce(fn, wait) {
                let t;
                return function (...args) {
                    clearTimeout(t);
                    t = setTimeout(() => fn.apply(this, args), wait);
                };
            }

            function submitFilter(resetPage = true) {
                if (!$form.length) return;

                if (resetPage) {
                    $form.find('input[name="page"]').remove();
                }

                $form.trigger('submit');
            }

            const debouncedSubmit = debounce(() => submitFilter(true), 400);

            // debounce untuk input
            $searchName.on('input', function () {
                debouncedSubmit();
            });

            $searchKode.on('input', function () {
                debouncedSubmit();
            });

            $searchLevel.on('input', function () {
                debouncedSubmit();
            });

            // perPage auto submit
            $perPage.on('change', function () {
                submitFilter(true);
            });

            // delete handler tetap
            $(document).on('click', '.btn-delete-jabatan', function () {
                const url  = $(this).data('url');
                const name = $(this).data('name') || 'this jabatan';

                Swal.fire({
                    title: 'Delete jabatan?',
                    html: `Are you sure you want to delete <b>${name}</b>?<br><small class="text-muted">This action cannot be undone.</small>`,
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
                                text: res?.message || 'Jabatan deleted successfully',
                                confirmButtonText: 'OK'
                            }).then(() => window.location.href = indexUrl);
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Failed to delete jabatan';
                            Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonText: 'OK' });
                        }
                    });
                });
            });
        });
    </script>
@endpush
