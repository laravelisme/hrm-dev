@extends('layouts.master')

@section('title', 'Tenancy - Domains')
@section('subtitle', 'Manage Domains')

@push('styles')
    <style>
        /* keep layout consistent with other pages */
        .filter-form { flex-wrap: wrap; }
        .filter-form .input-group { flex: 1 1 220px; min-width: 0; }
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

                    <div class="d-flex flex-column flex-md-row flex-wrap align-items-stretch align-items-md-center gap-2">
                        <form id="filterForm" method="GET" action="{{ url()->current() }}"
                              class="d-flex flex-wrap align-items-center gap-2 filter-form">

                            @if(request()->filled('page'))
                                <input type="hidden" name="page" value="{{ request('page') }}">
                            @endif

                            <div class="input-group input-group-sm" style="min-width:220px;">
                                <span class="input-group-text"><i class="bi bi-globe"></i></span>
                                <input type="text" id="searchDomain" name="domain" class="form-control"
                                       placeholder="Search domain..." value="{{ request('domain') }}">
                            </div>

                            <select id="perPage" name="perPage" class="form-select form-select-sm" style="min-width:110px;">
                                @foreach([10,20,50,100] as $n)
                                    <option value="{{ $n }}" @selected((int)request('perPage',10)===$n)>
                                        {{ $n }}/page
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAddDomain">
                            <i class="bi bi-plus-lg me-1"></i> Add Domain
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

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
                                <th>Created</th>
                                <th class="text-end" style="width:140px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($domains as $i => $d)
                                <tr>
                                    <td class="text-muted">{{ $domains->firstItem() + $i }}</td>
                                    <td class="fw-semibold">
                                        {{ $d->domain }}
                                        <div class="small text-muted">Tenant: {{ $d->tenant_id ?? '-' }}</div>
                                    </td>
                                    <td class="small text-muted">{{ optional($d->created_at)->format('Y-m-d H:i') ?? '-' }}</td>
                                    <td class="text-end">
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-light" href="{{ (strpos($d->domain, 'http') === 0 ? $d->domain : ('http://'.$d->domain)) }}" target="_blank" title="Jump to domain">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-light text-danger btn-delete-domain"
                                                    data-url="{{ url()->current().'/'.$d->id }}"
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

    <!-- Add Domain Modal -->
    <div class="modal fade" id="modalAddDomain" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formAddDomain" method="POST" action="{{ url()->current() }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Domain</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="domainInput" class="form-label">Domain</label>
                            <input type="text" name="domain" id="domainInput" class="form-control" placeholder="example.yourdomain.com" required>
                            <div class="form-text">Enter the full domain (without http). This will be used to jump to the tenant site.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Domain</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            const baseUrl = @json(url()->current());
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

            $('#searchDomain').on('input', debounce(() => submitFilter(true), 400));
            $('#perPage').on('change', () => submitFilter(true));

            // Delete domain (AJAX)
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

                    $.post(url, { _token: csrf, _method: 'DELETE' })
                        .done(res => {
                            Swal.fire('Deleted', res.message || 'Domain deleted', 'success')
                                .then(() => window.location.href = baseUrl);
                        })
                        .fail(xhr => {
                            let msg = 'Failed to delete domain';
                            if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                            Swal.fire('Error', msg, 'error');
                        });
                });
            });

            // Optional: handle add domain form submit via AJAX to avoid full reload
            $('#formAddDomain').on('submit', function (e) {
                // let default submit happen; if you prefer AJAX, uncomment below and prevent default.
                // e.preventDefault();
                // const data = $(this).serialize();
                // $.post(baseUrl, data)
                //   .done(res => { $('#modalAddDomain').modal('hide'); Swal.fire('Added', res.message, 'success').then(()=>location.href = baseUrl); })
                //   .fail(err => Swal.fire('Error', 'Failed to add domain', 'error'));
            });
        });
    </script>
@endpush
