@extends('layouts.master')

@section('title', 'Transaksi - Lembur Karyawan')
@section('subtitle', 'Transaksi Lembur Karyawan')

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

                            {{-- Bulan Tahun --}}
                            <input type="hidden" name="searchBulanTahun" id="searchBulanTahun" value="{{ request('searchBulanTahun') }}">

                            <div class="input-group input-group-sm" style="min-width: 170px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input id="bulanTahunPicker" type="month" class="form-control" title="Bulan-Tahun">
                            </div>

                            {{-- Karyawan --}}
                            <select id="searchKaryawan" name="searchKaryawan" class="form-select form-select-sm" style="min-width: 220px;">
                                @if(request()->filled('searchKaryawan'))
                                    <option value="{{ request('searchKaryawan') }}" selected>Selected Karyawan</option>
                                @endif
                            </select>

                            {{-- Company --}}
                            <select id="searchCompany" name="searchCompany" class="form-select form-select-sm" style="min-width: 220px;">
                                @if(request()->filled('searchCompany'))
                                    <option value="{{ request('searchCompany') }}" selected>Selected Company</option>
                                @endif
                            </select>

                            {{-- Status --}}
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

                        <a href="{{ route('admin.transaksi.lembur-karyawan.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Tambah Lembur
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($lemburs->count() === 0)
                    <div class="alert alert-light-primary mb-0">
                        <i class="bi bi-info-circle me-1"></i> No lembur found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                            <tr>
                                <th style="width: 70px;">No</th>
                                <th>Karyawan</th>
                                <th>Company</th>
                                <th>Tanggal</th>
                                <th>Durasi</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th class="text-end" style="width: 140px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lemburs as $i => $lembur)
                                <tr>
                                    <td class="text-muted">{{ $lemburs->firstItem() + $i }}</td>
                                    <td class="fw-semibold">
                                        {{ $lembur->nama_karyawan ?? '-' }}
                                        <div class="text-muted small">ID: <span class="font-monospace">{{ $lembur->m_karyawan_id }}</span></div>
                                    </td>
                                    <td>{{ $lembur->nama_perusahaan ?? '-' }}</td>
                                    <td class="text-muted small">{{ $lembur->date }}</td>
                                    <td class="fw-semibold">{{ $lembur->durasi_diajukan_menit }} menit</td>
                                    <td>
                                        <span class="badge bg-light-primary text-dark">{{ $lembur->status }}</span>
                                    </td>
                                    <td class="text-muted small">{{ optional($lembur->created_at)->format('Y-m-d H:i') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.transaksi.lembur-karyawan.show', $lembur->id) }}" class="btn btn-sm btn-light">
                                            <i class="bi bi-eye"></i>
                                        </a>
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
            const $form = $('#filterForm');

            function debounce(fn, wait) {
                let t; return function (...args) {
                    clearTimeout(t);
                    t = setTimeout(() => fn.apply(this, args), wait);
                };
            }

            function submitFilter(resetPage = true) {
                if (resetPage) $form.find('input[name="page"]').remove();
                $form.trigger('submit');
            }

            const debouncedSubmit = debounce(() => submitFilter(true), 400);

            // Month picker
            const $hiddenBulanTahun = $('#searchBulanTahun');
            const $picker = $('#bulanTahunPicker');

            const raw = String($hiddenBulanTahun.val() || '').trim();
            if (raw) {
                const [mm, yyyy] = raw.split('-');
                if (mm && yyyy) $picker.val(`${yyyy}-${String(mm).padStart(2,'0')}`);
            }

            $picker.on('change', function () {
                const v = $(this).val();
                if (!v) $hiddenBulanTahun.val('');
                else {
                    const [yyyy, mm] = v.split('-');
                    $hiddenBulanTahun.val(`${mm}-${yyyy}`);
                }
                debouncedSubmit();
            });

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
                        data: params => ({ q: params.term || '', page: params.page || 1 }),
                        processResults: (data, params) => ({
                            results: data.results || [],
                            pagination: { more: data.pagination?.more === true }
                        })
                    }
                });

                $el.on('select2:select select2:unselect', debouncedSubmit);
            }

            initSelect2Ajax($('#searchKaryawan'), @json(route('admin.transaksi.lembur-karyawan.karyawan-options')), 'Karyawan...');
            initSelect2Ajax($('#searchCompany'),  @json(route('admin.transaksi.lembur-karyawan.company-options')), 'Company...');

            $('#searchStatus').on('change', debouncedSubmit);
            $('#perPage').on('change', () => submitFilter(true));
        });
    </script>
@endpush
