@extends('layouts.master')

@section('title', 'Master Data - Hari Libur')
@section('subtitle', 'Master Data Hari Libur')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h4 class="mb-0">Hari Libur</h4>
                        <p class="text-muted mb-0">
                            Total: <span class="fw-bold">{{ $hariLiburs->total() }}</span> hari libur
                        </p>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                        <form method="GET" action="{{ url()->current() }}" class="d-flex flex-column flex-md-row gap-2">

                            <!-- Search Nama -->
                            <div class="input-group input-group-sm" style="min-width: 220px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <input type="text" class="form-control" name="searchName" placeholder="Search Nama..."
                                       value="{{ request('searchName') }}" autocomplete="off" />
                            </div>

                            <!-- Search Tahun -->
                            <div class="input-group input-group-sm" style="min-width: 140px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="number" class="form-control" name="searchTahun" placeholder="Tahun..."
                                       value="{{ request('searchTahun') }}" min="1900" max="2100" autocomplete="off" />
                            </div>

                            <!-- Cuti Bersama -->
                            <div class="input-group input-group-sm" style="min-width: 190px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-people"></i></span>
                                <select name="searchIsBersama" class="form-select">
                                    <option value="">Cuti Bersama: All</option>
                                    <option value="1" @selected(request('searchIsBersama') === '1')>Ya</option>
                                    <option value="0" @selected(request('searchIsBersama') === '0')>Tidak</option>
                                </select>
                            </div>

                            <!-- Hari Libur Umum -->
                            <div class="input-group input-group-sm" style="min-width: 180px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-megaphone"></i></span>
                                <select name="searchIsUmum" class="form-select">
                                    <option value="">Libur Umum: All</option>
                                    <option value="1" @selected(request('searchIsUmum') === '1')>Ya</option>
                                    <option value="0" @selected(request('searchIsUmum') === '0')>Tidak</option>
                                </select>
                            </div>

                            @if(
                                request()->filled('searchName') ||
                                request()->filled('searchTahun') ||
                                request()->filled('searchIsBersama') ||
                                request()->filled('searchIsUmum') ||
                                request()->filled('perPage')
                            )
                                <a href="{{ url()->current() }}" class="btn btn-light btn-sm" style="max-height: 40px;">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif

                            <!-- Per page -->
                            <select name="perPage" class="form-select form-select-sm" style="min-width: 110px; max-height: 40px;" onchange="this.form.submit()">
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

                        <a href="{{ route('admin.master-data.hari-libur.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Hari Libur
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($hariLiburs->count() === 0)
                    <div class="alert alert-light-primary mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        No hari libur found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                            <tr>
                                <th style="width: 70px;">No</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Cuti Bersama</th>
                                <th>Libur Umum</th>
                                <th>Repeat</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($hariLiburs as $i => $h)
                                <tr>
                                    <td class="text-muted">{{ $hariLiburs->firstItem() + $i }}</td>
                                    <td>{{ $h->hari_libur ?? '-' }}</td>
                                    <td>
                                        <div class="small">
                                            <div><i class="bi bi-calendar-event me-1"></i> {{ $h->tanggal_mulai }}</div>
                                            <div class="text-muted"><i class="bi bi-calendar-check me-1"></i> {{ $h->tanggal_selesai }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if((int)$h->is_cuti_bersama === 1)
                                            <span class="badge bg-light-success text-success">Ya</span>
                                        @else
                                            <span class="badge bg-light-secondary text-dark">Tidak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if((int)$h->is_umum === 1)
                                            <span class="badge bg-light-success text-success">Ya</span>
                                        @else
                                            <span class="badge bg-light-secondary text-dark">Tidak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if((int)$h->is_repeat === 1)
                                            <span class="badge bg-light-warning text-warning">Repeat</span>
                                        @else
                                            <span class="badge bg-light-secondary text-dark">No</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ optional($h->created_at)->format('Y-m-d H:i') }}</td>
                                    <td class="text-muted small">{{ optional($h->updated_at)->format('Y-m-d H:i') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.master-data.hari-libur.edit', $h->id) }}">
                                                        <i class="bi bi-pencil-square me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger btn-delete-hari-libur"
                                                            data-url="{{ route('admin.master-data.hari-libur.destroy', $h->id) }}"
                                                            data-name="{{ $h->hari_libur ?? 'hari libur ini' }}">
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
                            Showing <b>{{ $hariLiburs->firstItem() }}</b> to <b>{{ $hariLiburs->lastItem() }}</b>
                            of <b>{{ $hariLiburs->total() }}</b> results
                        </div>

                        <div>{{ $hariLiburs->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const indexUrl = @json(route('admin.master-data.hari-libur.index'));
            const csrf = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

            $(document).on('click', '.btn-delete-hari-libur', function () {
                const url  = $(this).data('url');
                const name = $(this).data('name') || 'this hari libur';

                Swal.fire({
                    title: 'Delete hari libur?',
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
                                text: res?.message || 'Hari libur deleted successfully',
                                confirmButtonText: 'OK'
                            }).then(() => window.location.href = indexUrl);
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Failed to delete hari libur';
                            Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonText: 'OK' });
                        }
                    });
                });
            });
        });
    </script>
@endpush
