@extends('layouts.master')

@section('title', 'Transaksi - Cuti Karyawan')
@section('subtitle', 'Transaksi Cuti Karyawan')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h4 class="mb-0">Cuti Karyawan</h4>
                        <p class="text-muted mb-0">
                            Total: <span class="fw-bold">{{ $cutis->total() }}</span> pengajuan cuti
                        </p>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                        <form id="filterForm" method="GET" action="{{ url()->current() }}"
                              class="d-flex flex-column flex-md-row gap-2">

                            @if(request()->filled('page'))
                                <input type="hidden" name="page" value="{{ request('page') }}">
                            @endif

                            {{-- bulan-tahun: input month (YYYY-MM) -> hidden searchBulanTahun (MM-YYYY) --}}
                            <input type="hidden" name="searchBulanTahun" id="searchBulanTahun" value="{{ request('searchBulanTahun') }}">

                            <div class="input-group input-group-sm" style="min-width: 170px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input id="bulanTahunPicker" type="month" class="form-control" value=""
                                       title="Bulan-Tahun">
                            </div>

                            <select id="searchKaryawan" name="searchKaryawan" class="form-select form-select-sm" style="min-width: 220px;">
                                @if(request()->filled('searchKaryawan'))
                                    <option value="{{ request('searchKaryawan') }}" selected>Selected Karyawan</option>
                                @endif
                            </select>

                            <select id="searchCompany" name="searchCompany" class="form-select form-select-sm" style="min-width: 220px;">
                                @if(request()->filled('searchCompany'))
                                    <option value="{{ request('searchCompany') }}" selected>Selected Company</option>
                                @endif
                            </select>

                            <select id="searchJenis" name="searchJenis" class="form-select form-select-sm" style="min-width: 220px;">
                                @if(request()->filled('searchJenis'))
                                    <option value="{{ request('searchJenis') }}" selected>Selected Jenis Cuti</option>
                                @endif
                            </select>

                            <select id="searchStatus" name="searchStatus" class="form-select form-select-sm" style="min-width: 170px;">
                                <option value="">All Status</option>
                                @foreach(['SUBMITED','PENDING_APPROVED','APPROVED','REJECTED'] as $st)
                                    <option value="{{ $st }}" @selected(request('searchStatus')===$st)>{{ $st }}</option>
                                @endforeach
                            </select>

                            @php
                                $hasFilter =
                                    request()->filled('searchBulanTahun') ||
                                    request()->filled('searchKaryawan') ||
                                    request()->filled('searchCompany') ||
                                    request()->filled('searchJenis') ||
                                    request()->filled('searchStatus') ||
                                    request()->filled('perPage');
                            @endphp

                            @if($hasFilter)
                                <a href="{{ url()->current() }}" class="btn btn-light btn-sm" style="max-height: 40px;">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif

                            <select id="perPage" name="perPage" class="form-select form-select-sm" style="min-width: 110px; max-height: 40px;">
                                @foreach([10,20,50,100] as $n)
                                    <option value="{{ $n }}" @selected((int)request('perPage',10)===$n)>{{ $n }}/page</option>
                                @endforeach
                            </select>

                            <button class="btn btn-primary btn-sm" type="submit" style="max-height: 40px;">
                                <i class="bi bi-funnel me-1"></i>
                            </button>
                        </form>

                        <a href="{{ route('admin.transaksi.cuti-karyawan.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Cuti
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($cutis->count() === 0)
                    <div class="alert alert-light-primary mb-0">
                        <i class="bi bi-info-circle me-1"></i> No cuti found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                            <tr>
                                <th style="width: 70px;">No</th>
                                <th>Karyawan</th>
                                <th>Company</th>
                                <th>Jenis</th>
                                <th>Periode</th>
                                <th>Hari</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th class="text-end" style="width: 140px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cutis as $i => $c)
                                <tr>
                                    <td class="text-muted">{{ $cutis->firstItem() + $i }}</td>
                                    <td class="fw-semibold">
                                        {{ $c->nama_karyawan ?? '-' }}
                                        <div class="text-muted small">ID: <span class="font-monospace">{{ $c->m_karyawan_id }}</span></div>
                                    </td>
                                    <td>{{ $c->nama_perusahaan ?? '-' }}</td>
                                    <td>{{ optional($c->jenisCuti)->name ?? $c->m_jenis_cuti_id }}</td>
                                    <td class="text-muted small">
                                        {{ $c->start_date }} → {{ $c->end_date }}
                                    </td>
                                    <td class="fw-semibold">{{ $c->jumlah_hari }}</td>
                                    <td>
                                        @php($st = $c->status)
                                        <span class="badge
                                        {{ $st==='APPROVED' ? 'bg-light-success text-dark' :
                                           ($st==='REJECTED' ? 'bg-light-danger text-dark' :
                                           ($st==='PENDING_APPROVED' ? 'bg-light-warning text-dark' : 'bg-light-primary text-dark')) }}">
                                        {{ $st }}
                                    </span>
                                    </td>
                                    <td class="text-muted small">{{ optional($c->created_at)->format('Y-m-d H:i') }}</td>
                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.transaksi.cuti-karyawan.show', $c->id) }}">
                                                        <i class="bi bi-eye me-2"></i> Show
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-danger btn-delete"
                                                            data-url="{{ route('admin.transaksi.cuti-karyawan.destroy', $c->id) }}"
                                                            data-name="{{ $c->nama_karyawan ?? 'this request' }}">
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
                            Showing <b>{{ $cutis->firstItem() }}</b> to <b>{{ $cutis->lastItem() }}</b>
                            of <b>{{ $cutis->total() }}</b> results
                        </div>
                        <div>{{ $cutis->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const indexUrl = @json(route('admin.transaksi.cuti-karyawan.index'));
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

            // Prefill month picker dari request('searchBulanTahun') format MM-YYYY -> YYYY-MM
            const raw = ($hiddenBulanTahun.val() || '').trim(); // MM-YYYY
            if (raw) {
                const [mm, yyyy] = raw.split('-');
                if (mm && yyyy) $picker.val(`${yyyy}-${mm.padStart(2,'0')}`);
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
                        url: url,
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

                // auto submit saat berubah (debounce)
                $el.on('select2:select select2:unselect', debouncedSubmit);
            }

            initSelect2Ajax($('#searchKaryawan'), @json(route('admin.transaksi.cuti-karyawan.karyawan-options')), 'Karyawan...');
            initSelect2Ajax($('#searchCompany'),  @json(route('admin.transaksi.cuti-karyawan.company-options')),  'Company...');
            initSelect2Ajax($('#searchJenis'),    @json(route('admin.transaksi.cuti-karyawan.jenis-cuti-options')), 'Jenis Cuti...');

            // status (select biasa) tetap debounce
            $('#searchStatus').on('change', debouncedSubmit);
            $('#perPage').on('change', () => submitFilter(true));

            // ===== Delete =====
            $(document).on('click', '.btn-delete', function () {
                const url = $(this).data('url');
                const name = $(this).data('name') || 'this request';

                Swal.fire({
                    title: 'Delete cuti?',
                    html: `Yakin hapus <b>${name}</b>?<br><small class="text-muted">Data tidak bisa dikembalikan.</small>`,
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
                            Swal.fire({ icon:'success', title:'Deleted', text: res?.message || 'Deleted' })
                                .then(() => window.location.href = indexUrl);
                        },
                        error: function (xhr) {
                            Swal.fire({ icon:'error', title:'Error', text: xhr.responseJSON?.message || 'Failed to delete' });
                        }
                    });
                });
            });
        });
    </script>
@endpush
