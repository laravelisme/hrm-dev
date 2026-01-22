@extends('layouts.master')

@section('title', 'Master Data - Saldo Cuti')
@section('subtitle', 'Master Data Saldo Cuti')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h4 class="mb-0">Saldo Cuti</h4>
                        <p class="text-muted mb-0">
                            Total: <span class="fw-bold">{{ $saldoCutis->total() }}</span> saldo cuti
                        </p>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                        <form method="GET" action="{{ url()->current() }}" class="d-flex flex-column flex-md-row gap-2">
                            <!-- Search Jenis -->
                            <div class="input-group input-group-sm" style="min-width: 200px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-tags"></i></span>
                                <input type="text" class="form-control" name="searchJenis" placeholder="Search Jenis..."
                                       value="{{ request('searchJenis') }}" autocomplete="off" />
                            </div>

                            <!-- Search Jumlah -->
                            <div class="input-group input-group-sm" style="min-width: 140px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-123"></i></span>
                                <input type="number" class="form-control" name="searchJumlah" placeholder="Search Jumlah..."
                                       value="{{ request('searchJumlah') }}" autocomplete="off" />
                            </div>

                            @if(request()->filled('searchJenis') || request()->filled('searchJumlah') || request()->filled('perPage'))
                                <a href="{{ url()->current() }}" class="btn btn-light btn-sm">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif

                            <!-- Per page -->
                            <select name="perPage" class="form-select form-select-sm" style="min-width: 100px; max-height: 40px;" onchange="this.form.submit()">
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

                        <a href="{{ route('admin.master-data.saldo-cuti.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Saldo Cuti
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($saldoCutis->count() === 0)
                    <div class="alert alert-light-primary mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        No saldo cuti found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                            <tr>
                                <th style="width: 70px;">No</th>
                                <th>Jenis Cuti</th>
                                <th>Jumlah</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($saldoCutis as $i => $saldoCuti)
                                <tr>
                                    <td class="text-muted">{{ $saldoCutis->firstItem() + $i }}</td>
                                    <td>{{ $saldoCuti->jenis }}</td>
                                    <td>{{ $saldoCuti->jumlah }}</td>
                                    <td class="text-muted small">{{ optional($saldoCuti->created_at)->format('Y-m-d H:i') }}</td>
                                    <td class="text-muted small">{{ optional($saldoCuti->updated_at)->format('Y-m-d H:i') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.master-data.saldo-cuti.edit', $saldoCuti->id) }}">
                                                        <i class="bi bi-pencil-square me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger btn-delete-saldo-cuti"
                                                            data-url="{{ route('admin.master-data.saldo-cuti.destroy', $saldoCuti->id) }}"
                                                            data-name="{{ $saldoCuti->jenis }}">
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
                            Showing <b>{{ $saldoCutis->firstItem() }}</b> to <b>{{ $saldoCutis->lastItem() }}</b>
                            of <b>{{ $saldoCutis->total() }}</b> results
                        </div>

                        <div>{{ $saldoCutis->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const indexUrl = @json(route('admin.master-data.saldo-cuti.index'));
            const csrf = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

            $(document).on('click', '.btn-delete-saldo-cuti', function () {
                const url  = $(this).data('url');
                const name = $(this).data('name') || 'this saldo cuti';

                Swal.fire({
                    title: 'Delete saldo cuti?',
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
                                text: res?.message || 'Saldo cuti deleted successfully',
                                confirmButtonText: 'OK'
                            }).then(() => window.location.href = indexUrl);
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Failed to delete saldo cuti';
                            Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonText: 'OK' });
                        }
                    });
                });
            });
        });
    </script>
@endpush
