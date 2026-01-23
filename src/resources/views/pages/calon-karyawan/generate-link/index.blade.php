@extends('layouts.master')

@section('title', 'Calon Karyawan - Generate Link')
@section('subtitle', 'Generate Link Calon Karyawan')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h4 class="mb-0">Generate Link</h4>
                        <p class="text-muted mb-0">
                            Total: <span class="fw-bold">{{ $tokens->total() }}</span> links
                        </p>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                        <form id="filterForm" method="GET" action="{{ url()->current() }}" class="d-flex flex-column flex-md-row gap-2">
                            {{-- Keep current page if exists (akan dihapus via JS saat filter berubah) --}}
                            @if(request()->filled('page'))
                                <input type="hidden" name="page" value="{{ request('page') }}">
                            @endif

                            <!-- Search Token -->
                            <div class="input-group input-group-sm" style="min-width: 220px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input
                                    id="searchToken"
                                    type="text"
                                    class="form-control"
                                    name="searchToken"
                                    placeholder="Search Token..."
                                    value="{{ request('searchToken') }}"
                                    autocomplete="off"
                                />
                            </div>

                            <!-- Search Link -->
                            <div class="input-group input-group-sm" style="min-width: 260px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                                <input
                                    id="searchLink"
                                    type="text"
                                    class="form-control"
                                    name="searchLink"
                                    placeholder="Search Link..."
                                    value="{{ request('searchLink') }}"
                                    autocomplete="off"
                                />
                            </div>

                            <!-- Search Is Used -->
                            <div class="input-group input-group-sm" style="min-width: 160px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-check2-circle"></i></span>
                                <select id="searchIsUsed" name="searchIsUsed" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="0" @selected(request('searchIsUsed') === '0')>Unused</option>
                                    <option value="1" @selected(request('searchIsUsed') === '1')>Used</option>
                                </select>
                            </div>

                            @php
                                $hasFilter = request()->filled('searchToken')
                                    || request()->filled('searchLink')
                                    || request()->filled('searchIsUsed')
                                    || request()->filled('perPage');
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

                            <button class="btn btn-primary btn-sm" type="submit" style="max-height: 40px;">
                                <i class="bi bi-funnel me-1"></i>
                            </button>
                        </form>

                        <button id="btnGenerateLink" type="button" class="btn btn-primary btn-sm">
                            <i class="bi bi-magic me-1"></i> Generate Link
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($tokens->count() === 0)
                    <div class="alert alert-light-primary mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        No link found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                            <tr>
                                <th style="width: 70px;">No</th>
                                <th style="min-width: 220px;">Token</th>
                                <th style="min-width: 360px;">Link</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tokens as $i => $t)
                                <tr>
                                    <td class="text-muted">{{ $tokens->firstItem() + $i }}</td>

                                    <td class="font-monospace">
                                        <span class="text-muted small">{{ $t->token }}</span>
                                    </td>

                                    <td>
                                        @if($t->link)
                                            <div class="d-flex align-items-center gap-2">
                                                <a href="{{ $t->link }}" target="_blank" class="text-decoration-none">
                                                    {{ $t->link }}
                                                </a>
                                                <button type="button"
                                                        class="btn btn-sm btn-light btn-copy-link"
                                                        data-link="{{ $t->link }}"
                                                        title="Copy link">
                                                    <i class="bi bi-clipboard"></i>
                                                </button>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if($t->is_used)
                                            <span class="badge bg-light-success text-success">
                                                <i class="bi bi-check2-circle me-1"></i> Used
                                            </span>
                                        @else
                                            <span class="badge bg-light-secondary text-dark">
                                                <i class="bi bi-circle me-1"></i> Unused
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-muted small">{{ optional($t->created_at)->format('Y-m-d H:i') }}</td>

                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button type="button"
                                                            class="dropdown-item btn-copy-link"
                                                            data-link="{{ $t->link ?? '' }}"
                                                        {{ $t->link ? '' : 'disabled' }}>
                                                        <i class="bi bi-clipboard me-2"></i> Copy Link
                                                    </button>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button"
                                                            class="dropdown-item text-danger btn-delete-token"
                                                            data-url="{{ route('admin.calon-karyawan.generate-link.destroy', $t->id) }}"
                                                            data-token="{{ $t->token }}"
                                                            data-used="{{ (int)$t->is_used }}">
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
                            Showing <b>{{ $tokens->firstItem() }}</b> to <b>{{ $tokens->lastItem() }}</b>
                            of <b>{{ $tokens->total() }}</b> results
                        </div>

                        <div>{{ $tokens->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const indexUrl  = @json(route('admin.calon-karyawan.generate-link.index'));
            const storeUrl  = @json(route('admin.calon-karyawan.generate-link.store'));
            const csrf      = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

            const $form         = $('#filterForm');
            const $searchToken  = $('#searchToken');
            const $searchLink   = $('#searchLink');
            const $searchIsUsed = $('#searchIsUsed');
            const $perPage      = $('#perPage');

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

            $searchToken.on('input', debouncedSubmit);
            $searchLink.on('input', debouncedSubmit);

            $searchIsUsed.on('change', function () {
                submitFilter(true);
            });

            $perPage.on('change', function () {
                submitFilter(true);
            });

            // Generate Link (langsung create)
            $('#btnGenerateLink').on('click', function () {
                Swal.fire({
                    title: 'Generate new link?',
                    html: `A new registration link will be created.<br><small class="text-muted">You can copy it after created.</small>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, generate',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (!result.isConfirmed) return;

                    $('#btnGenerateLink').prop('disabled', true);

                    $.ajax({
                        url: storeUrl,
                        method: 'POST',
                        data: { _token: csrf },
                        success: function (res) {
                            $('#btnGenerateLink').prop('disabled', false);

                            const link = res?.data?.link || '';
                            const token = res?.data?.token || '';

                            Swal.fire({
                                icon: 'success',
                                title: 'Generated',
                                html: link
                                    ? `Link created:<br><a href="${link}" target="_blank">${link}</a><br><small class="text-muted">Token: ${token}</small>`
                                    : (res?.message || 'Link generated successfully'),
                                confirmButtonText: 'OK'
                            }).then(() => window.location.href = indexUrl);
                        },
                        error: function (xhr) {
                            $('#btnGenerateLink').prop('disabled', false);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Failed to generate link',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                });
            });

            // Copy link
            $(document).on('click', '.btn-copy-link', async function () {
                const link = $(this).data('link');
                if (!link) return;

                try {
                    await navigator.clipboard.writeText(link);
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Copied!',
                        showConfirmButton: false,
                        timer: 1200
                    });
                } catch (e) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Failed to copy link' });
                }
            });

            // Delete (dengan penegasan)
            $(document).on('click', '.btn-delete-token', function () {
                const url   = $(this).data('url');
                const token = $(this).data('token') || 'this token';
                const used  = parseInt($(this).data('used') || '0', 10);

                const extraWarn = used
                    ? `<div class="alert alert-light-warning mt-2 mb-0">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            This link is already <b>USED</b>. Deleting it may affect audit/history.
                       </div>`
                    : `<small class="text-muted">This action cannot be undone.</small>`;

                Swal.fire({
                    title: 'Delete link?',
                    html: `Are you sure you want to delete token:<br><b>${token}</b>?<br>${extraWarn}`,
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
                                text: res?.message || 'Link deleted successfully',
                                confirmButtonText: 'OK'
                            }).then(() => window.location.href = indexUrl);
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Failed to delete link';
                            Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonText: 'OK' });
                        }
                    });
                });
            });
        });
    </script>
@endpush
