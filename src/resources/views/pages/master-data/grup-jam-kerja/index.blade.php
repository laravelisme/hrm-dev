@extends('layouts.master')

@section('title', 'Master Data - Grup Jam Kerja')
@section('subtitle', 'Master Data Grup Jam Kerja')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h4 class="mb-0">Grup Jam Kerja</h4>
                        <p class="text-muted mb-0">
                            Total: <span class="fw-bold">{{ $grupJamKerjas->total() }}</span> shift groups
                        </p>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                        <form method="GET" action="{{ url()->current() }}" class="d-flex flex-column flex-md-row gap-2">
                            <div class="input-group input-group-sm" style="min-width: 200px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control" name="searchName" placeholder="Search name..."
                                       value="{{ request('searchName') }}" autocomplete="off" />
                            </div>

                            <select name="perPage" class="form-select form-select-sm" style="min-width: 100px; max-height: 40px;" onchange="this.form.submit()">
                                @foreach([10, 20, 50, 100] as $n)
                                    <option value="{{ $n }}" @selected((int)request('perPage', 10) === $n)>{{ $n }}/page</option>
                                @endforeach
                            </select>

                            <button class="btn btn-primary btn-sm" type="submit" style="max-height: 40px;">
                                <i class="bi bi-funnel me-1"></i>
                            </button>

                            @if(request()->filled('searchName') || request()->filled('perPage'))
                                <a href="{{ url()->current() }}" class="btn btn-light btn-sm">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif
                        </form>

                        <a href="{{ route('admin.master-data.grup-jam-kerja.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Grup Jam Kerja
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($grupJamKerjas->count() === 0)
                    <div class="alert alert-light-primary mb-0">
                        <i class="bi bi-info-circle me-1"></i> No shift groups found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                            <tr>
                                <th style="width: 70px;">No</th>
                                <th>Name</th>
                                <th>Default Schedule</th>
                                <th>Check-in Range</th>
                                <th>Check-out Range</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($grupJamKerjas as $i => $grup)
                                <tr>
                                    <td class="text-muted">{{ $grupJamKerjas->firstItem() + $i }}</td>
                                    <td><strong>{{ $grup->name }}</strong></td>
                                    <td>
                                        @if($grup->start && $grup->end)
                                            <span class="badge bg-light-info">{{ $grup->start }} - {{ $grup->end }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($grup->min_check_in && $grup->max_check_in)
                                            <small>{{ $grup->min_check_in }} - {{ $grup->max_check_in }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($grup->min_check_out && $grup->max_check_out)
                                            <small>{{ $grup->min_check_out }} - {{ $grup->max_check_out }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.master-data.grup-jam-kerja.edit', $grup->id) }}">
                                                        <i class="bi bi-pencil-square me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger btn-delete"
                                                            data-url="{{ route('admin.master-data.grup-jam-kerja.destroy', $grup->id) }}"
                                                            data-name="{{ $grup->name }}">
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
                            Showing <b>{{ $grupJamKerjas->firstItem() }}</b> to <b>{{ $grupJamKerjas->lastItem() }}</b>
                            of <b>{{ $grupJamKerjas->total() }}</b> results
                        </div>
                        <div>{{ $grupJamKerjas->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const indexUrl = @json(route('admin.master-data.grup-jam-kerja.index'));
            const csrf = $('meta[name="csrf-token"]').attr('content');

            $(document).on('click', '.btn-delete', function () {
                const url  = $(this).data('url');
                const name = $(this).data('name') || 'this shift group';

                Swal.fire({
                    title: 'Delete shift group?',
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
                                text: res?.message || 'Grup jam kerja deleted successfully',
                                confirmButtonText: 'OK'
                            }).then(() => window.location.href = indexUrl);
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Failed to delete grup jam kerja';
                            Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonText: 'OK' });
                        }
                    });
                });
            });
        });
    </script>
@endpush
