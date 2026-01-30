@extends('layouts.master')

@section('title', 'Transaksi - Lembur Karyawan')
@section('subtitle', 'Transaksi')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h4 class="mb-0">Lembur Karyawan</h4>
                        <p class="text-muted mb-0">
                            Total: <span class="fw-bold">{{ $lemburs->total() }}</span> lembur
                        </p>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                        <form id="filterForm" method="GET" action="{{ url()->current() }}"
                              class="d-flex flex-column flex-md-row gap-2">

                            @if(request()->filled('page'))
                                <input type="hidden" name="page" value="{{ request('page') }}">
                            @endif

                            {{-- bulan-tahun: input month (YYYY-MM) -> hidden searchBulanTahun (MM-YYYY) --}}
                            <input type="hidden" name="searchBulanTahun" id="searchBulanTahun"
                                   value="{{ request('searchBulanTahun') }}">

                            <div class="input-group input-group-sm" style="min-width: 170px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input id="bulanTahunPicker" type="month" class="form-control" value=""
                                       title="Bulan-Tahun">
                            </div>

                            {{-- Karyawan (select2) --}}
                            <select id="searchKaryawan" name="searchKaryawan" class="form-select form-select-sm"
                                    style="min-width: 220px;">
                                @if(request()->filled('searchKaryawan'))
                                    <option value="{{ request('searchKaryawan') }}" selected>Selected Karyawan</option>
                                @endif
                            </select>

                            {{-- Company (select2) --}}
                            <select id="searchCompany" name="searchCompany" class="form-select form-select-sm"
                                    style="min-width: 220px;">
                                @if(request()->filled('searchCompany'))
                                    <option value="{{ request('searchCompany') }}" selected>Selected Company</option>
                                @endif
                            </select>

                            {{-- Status (text biar sama kyk izin) --}}
                            <div class="input-group input-group-sm" style="min-width: 180px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-flag"></i></span>
                                <input id="searchStatus" type="text" class="form-control"
                                       name="searchStatus" placeholder="Status..."
                                       value="{{ request('searchStatus') }}" autocomplete="off">
                            </div>

                            @php
                                $hasFilter =
                                    request()->filled('searchBulanTahun') ||
                                    request()->filled('searchKaryawan') ||
                                    request()->filled('searchCompany') ||
                                    request()->filled('searchStatus') ||
                                    request()->filled('perPage');
                            @endphp

                            @if($hasFilter)
                                <a href="{{ url()->current() }}" class="btn btn-light btn-sm" style="max-height: 40px;">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif

                            <select id="perPage" name="perPage" class="form-select form-select-sm"
                                    style="min-width: 110px; max-height: 40px;">
                                @foreach([10, 20, 50, 100] as $n)
                                    <option value="{{ $n }}" @selected((int)request('perPage', 10) === $n)>{{ $n }}/page</option>
                                @endforeach
                            </select>

                            <button class="btn btn-primary btn-sm" type="submit" style="max-height: 40px;">
                                <i class="bi bi-funnel me-1"></i>
                            </button>
                        </form>

                        <a href="{{ route('admin.transaksi.lembur-karyawan.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Lembur
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($lemburs->count() === 0)
                    <div class="alert alert-light-primary mb-0">
                        <i class="bi bi-info-circle me-1"></i> Tidak ada data lembur.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                            <tr>
                                <th style="width:70px;">No</th>
                                <th>Karyawan</th>
                                <th>Company</th>
                                <th>Tanggal</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th class="text-end" style="width:120px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lemburs as $i => $l)
                                <tr>
                                    <td class="text-muted">{{ $lemburs->firstItem() + $i }}</td>

                                    <td class="fw-semibold">
                                        {{ $l->nama_karyawan ?? '-' }}
                                        <div class="text-muted small">
                                            ID: <span class="font-monospace">{{ $l->m_karyawan_id }}</span>
                                        </div>
                                    </td>

                                    <td>{{ $l->nama_company ?? '-' }}</td>

                                    <td class="text-muted small">{{ $l->date ?? '-' }}</td>

                                    <td>
                                        @php($m = (int)($l->durasi_diajukan_menit ?? 0))
                                        <span class="fw-semibold">{{ $m }}</span>
                                        <span class="text-muted small">menit</span>
                                    </td>

                                    <td>
                                        <span class="badge bg-light-primary text-dark">{{ $l->status ?? '-' }}</span>
                                    </td>

                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ route('admin.transaksi.lembur-karyawan.show', $l->id) }}">
                                                        <i class="bi bi-eye me-2"></i> Show
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button"
                                                            class="dropdown-item text-danger btn-delete-lembur"
                                                            data-url="{{ route('admin.transaksi.lembur-karyawan.destroy', $l->id) }}"
                                                            data-name="{{ $l->nama_karyawan ?? 'lembur ini' }}">
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
                            Showing <b>{{ $lemburs->firstItem() }}</b> to <b>{{ $lemburs->lastItem() }}</b>
                            of <b>{{ $lemburs->total() }}</b> results
                        </div>
                        <div>{{ $lemburs->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const indexUrl = @json(route('admin.transaksi.lembur-karyawan.index'));
            const csrf = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

            const $form = $('#filterForm');

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

            // ===== Bulan-Tahun (month -> MM-YYYY) =====
            const $hiddenBulanTahun = $('#searchBulanTahun');
            const $picker = $('#bulanTahunPicker');

            const raw = String($hiddenBulanTahun.val() || '').trim(); // MM-YYYY
            if (raw) {
                const [mm, yyyy] = raw.split('-');
                if (mm && yyyy) $picker.val(`${yyyy}-${String(mm).padStart(2,'0')}`);
            }

            $picker.on('change', function () {
                const v = $(this).val(); // YYYY-MM
                if (!v) {
                    $hiddenBulanTahun.val('');
                } else {
                    const [yyyy, mm] = v.split('-');
                    $hiddenBulanTahun.val(`${mm}-${yyyy}`); // MM-YYYY (sesuai controller)
                }
                debouncedSubmit();
            });

            // ===== Select2 AJAX setup =====
            function initSelect2Ajax($el, url, placeholder) {
                $el.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: placeholder,
                    allowClear: true,
                    ajax: {
                        url,
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term || '',
                                page: params.page || 1,
                                perPage: 20
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.results || [],
                                pagination: { more: data.pagination?.more === true }
                            };
                        },
                        cache: true
                    }
                });

                $el.on('select2:select select2:unselect', debouncedSubmit);
            }

            initSelect2Ajax($('#searchKaryawan'), @json(route('admin.transaksi.lembur-karyawan.karyawan-options')), 'Karyawan...');
            initSelect2Ajax($('#searchCompany'),  @json(route('admin.transaksi.lembur-karyawan.company-options')),  'Company...');

            $('#searchStatus').on('input', debouncedSubmit);
            $('#perPage').on('change', () => submitFilter(true));

            // ===== Delete =====
            $(document).on('click', '.btn-delete-lembur', function () {
                const url  = $(this).data('url');
                const name = $(this).data('name') || 'lembur ini';

                Swal.fire({
                    title: 'Delete lembur?',
                    html: `Yakin hapus lembur <b>${name}</b>?<br><small class="text-muted">Tidak bisa dikembalikan.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#d33'
                }).then((r) => {
                    if (!r.isConfirmed) return;

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: { _token: csrf, _method: 'DELETE' },
                        success: function (res) {
                            Swal.fire({ icon: 'success', title: 'Deleted', text: res?.message || 'Deleted' })
                                .then(() => window.location.href = indexUrl);
                        },
                        error: function (xhr) {
                            Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Failed' });
                        }
                    });
                });
            });
        });
    </script>
@endpush
