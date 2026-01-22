@extends('layouts.master')

@section('title', 'Master Data - Department')
@section('subtitle', 'Master Data Department')

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
    </style>
@endpush


@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h4 class="mb-0">Department</h4>
                        <p class="text-muted mb-0">
                            Total: <span class="fw-bold">{{ $departments->total() }}</span> departments
                        </p>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                        <form method="GET" action="{{ url()->current() }}" class="d-flex flex-column flex-md-row gap-2">
                            <!-- Search Name -->
                            <div class="input-group input-group-sm" style="min-width: 200px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-diagram-2"></i></span>
                                <input type="text" class="form-control" name="searchName" placeholder="Search Department..."
                                       value="{{ request('searchName') }}" autocomplete="off" />
                            </div>

                            <!-- Search Is HR -->
                            <div class="input-group input-group-sm" style="min-width: 150px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-person-check"></i></span>
                                <select name="searchIsHr" class="form-select">
                                    <option value="">All</option>
                                    <option value="1" @selected(request('searchIsHr') === '1')>HR</option>
                                    <option value="0" @selected(request('searchIsHr') === '0')>Non HR</option>
                                </select>
                            </div>

                            <!-- Search Company (Select2) -->
                            <div class="input-group input-group-sm" style="min-width: 260px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-buildings"></i></span>
                                <select name="searchCompanyId" class="form-select select2-company-filter">
                                    <option value="">All Company</option>
                                    @foreach($companies as $c)
                                        <option value="{{ $c->id }}" @selected((string)request('searchCompanyId') === (string)$c->id)>
                                            {{ $c->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if(request()->filled('searchName') || request()->has('searchIsHr') || request()->filled('searchCompanyId') || request()->filled('perPage'))
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

                        <a href="{{ route('admin.master-data.department.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Department
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($departments->count() === 0)
                    <div class="alert alert-light-primary mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        No department found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                            <tr>
                                <th style="width: 70px;">No</th>
                                <th>Department</th>
                                <th>Company</th>
                                <th>Is HR</th>
                                <th>Created At</th>
                                <th>Updated At</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($departments as $i => $dept)
                                <tr>
                                    <td class="text-muted">{{ $departments->firstItem() + $i }}</td>
                                    <td>{{ $dept->name }}</td>
                                    <td>{{ $dept->company?->company_name ?? '-' }}</td>
                                    <td>
                                        @if((int)$dept->is_hr === 1)
                                            <span class="badge bg-light-success text-success">HR</span>
                                        @else
                                            <span class="badge bg-light-secondary text-dark">Non HR</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">{{ optional($dept->created_at)->format('Y-m-d H:i') }}</td>
                                    <td class="text-muted small">{{ optional($dept->updated_at)->format('Y-m-d H:i') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.master-data.department.edit', $dept->id) }}">
                                                        <i class="bi bi-pencil-square me-2"></i> Edit
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger btn-delete-department"
                                                            data-url="{{ route('admin.master-data.department.destroy', $dept->id) }}"
                                                            data-name="{{ $dept->name }}">
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
                            Showing <b>{{ $departments->firstItem() }}</b> to <b>{{ $departments->lastItem() }}</b>
                            of <b>{{ $departments->total() }}</b> results
                        </div>

                        <div>{{ $departments->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {

            $('.select2-company-filter').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'All Company',
                allowClear: true,
                dropdownParent: $('.select2-company-filter').closest('.input-group')
            });


            const indexUrl = @json(route('admin.master-data.department.index'));
            const csrf = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

            $(document).on('click', '.btn-delete-department', function () {
                const url  = $(this).data('url');
                const name = $(this).data('name') || 'this department';

                Swal.fire({
                    title: 'Delete department?',
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
                                text: res?.message || 'Department deleted successfully',
                                confirmButtonText: 'OK'
                            }).then(() => window.location.href = indexUrl);
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Failed to delete department';
                            Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonText: 'OK' });
                        }
                    });
                });
            });
        });
    </script>
@endpush
