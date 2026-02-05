@extends('layouts.master')

@section('title', 'Tenancy - Domains')
@section('subtitle', 'Manage Domains')

@push('styles')
    <style>
        .filter-form { flex-wrap: wrap; }
        .filter-form .input-group { flex: 1 1 220px; min-width: 0; }
        .filter-form > .form-select,
        .filter-form > .btn,
        .filter-form > a.btn { flex: 0 0 auto; }

        .badge-password {
            font-family: monospace;
            font-size: 12px;
            letter-spacing: .5px;
        }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h4 class="mb-0">Domains</h4>
                        <p class="text-muted mb-0">
                            Total: <span class="fw-bold">{{ $domains->total() }}</span> domains
                        </p>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">

                        {{-- FILTER FORM --}}
                        <form id="filterForm"
                              method="GET"
                              action="{{ url()->current() }}"
                              class="d-flex flex-column flex-md-row align-items-md-center gap-2 flex-grow-1">

                            {{-- SEARCH --}}
                            <div class="input-group input-group-sm" style="max-width: 260px;">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input type="text"
                                       name="domain"
                                       id="searchDomain"
                                       class="form-control"
                                       placeholder="Search domain..."
                                       value="{{ request('domain') }}">
                            </div>

                            {{-- PER PAGE --}}
                            <select name="perPage"
                                    id="perPage"
                                    class="form-select form-select-sm"
                                    style="width:110px;">
                                @foreach([10,20,50,100] as $n)
                                    <option value="{{ $n }}" @selected((int)request('perPage',10)===$n)>
                                        {{ $n }}/page
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        {{-- ADD BUTTON --}}
                        <a href="{{ route('tenancy.domain.create') }}"
                           class="btn btn-primary btn-sm text-nowrap">
                            <i class="bi bi-plus-lg me-1"></i> Add Domain
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                @if($domains->count() === 0)
                    <div class="alert alert-light-primary mb-0">
                        <i class="bi bi-info-circle me-1"></i> No domains found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                            <tr>
                                <th style="width:70px;">No</th>
                                <th>Domain</th>
                                <th>Company</th>
                                <th>Admin Username</th>
                                <th>Admin Email</th>
                                <th>Admin Password</th>
                                <th class="text-end" style="width:150px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($domains as $i => $d)
                                <tr>
                                    <td class="text-muted">{{ $domains->firstItem() + $i }}</td>

                                    <td class="fw-semibold">
                                        {{ $d->domain }}
                                        <div class="small text-muted">Tenant ID: {{ $d->tenant_id }}</div>
                                    </td>

                                    <td>{{ $d->tenant->nama_company ?? '-' }}</td>

                                    <td>{{ $d->tenant->username ?? '-' }}</td>

                                    <td>{{ $d->tenant->email ?? '-' }}</td>

                                    <td>
                                    <span class="badge bg-light text-dark badge-password">
                                        {{ $d->tenant->password ?? '-' }}
                                    </span>
                                    </td>

                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-light"
                                               href="{{ str_starts_with($d->domain,'http') ? $d->domain : 'http://'.$d->domain }}"
                                               target="_blank" title="Open Domain">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>

                                            <button type="button"
                                                    class="btn btn-sm btn-light text-danger btn-delete-domain"
                                                    data-url="{{ route('tenancy.domain.destroy', $d->id) }}"
                                                    data-name="{{ $d->domain }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
                        <div class="text-muted small">
                            Showing <b>{{ $domains->firstItem() }}</b> to <b>{{ $domains->lastItem() }}</b>
                            of <b>{{ $domains->total() }}</b> results
                        </div>
                        <div>{{ $domains->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {

            const csrf = $('meta[name="csrf-token"]').attr('content');

            function debounce(fn, wait) {
                let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), wait); };
            }

            function submitFilter() {
                $('#filterForm').submit();
            }

            $('#searchDomain').on('input', debounce(submitFilter, 400));
            $('#perPage').on('change', submitFilter);

            // DELETE DOMAIN
            $(document).on('click', '.btn-delete-domain', function () {
                const url  = $(this).data('url');
                const name = $(this).data('name');

                Swal.fire({
                    title: 'Delete domain?',
                    html: `Hapus <b>${name}</b>?<br><small class="text-muted">This action cannot be undone.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete',
                    confirmButtonColor: '#d33'
                }).then(result => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: { _token: csrf, _method: 'DELETE' },
                        success: function (res) {
                            Swal.fire('Deleted', res.message, 'success')
                                .then(() => location.reload());
                        },
                        error: function () {
                            Swal.fire('Error', 'Failed to delete domain', 'error');
                        }
                    });
                });
            });

        });
    </script>
@endpush
