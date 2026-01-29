@extends('layouts.master')

@section('title', 'Transaksi - Saldo Cuti Tahunan')
@section('subtitle', 'Transaksi Saldo Cuti Tahunan')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h4 class="mb-0">Saldo Cuti Tahunan</h4>
                        <p class="text-muted mb-0">
                            Total: <span class="fw-bold">{{ $saldoCutis->total() }}</span> data saldo cuti tahunan
                        </p>
                    </div>

                    <div class="d-flex flex-column flex-md-row align-items-stretch align-items-md-center gap-2">
                        <form id="filterForm" method="GET" action="{{ url()->current() }}"
                              class="d-flex flex-column flex-md-row gap-2">
                            @if(request()->filled('page'))
                                <input type="hidden" name="page" value="{{ request('page') }}">
                            @endif

                            <!-- Search Karyawan -->
                            <div class="input-group input-group-sm" style="min-width: 220px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input
                                    id="searchKaryawan"
                                    type="text"
                                    class="form-control"
                                    name="searchKaryawan"
                                    placeholder="Search Karyawan..."
                                    value="{{ request('searchKaryawan') }}"
                                    autocomplete="off"
                                />
                            </div>

                            <!-- Search Tahun -->
                            <div class="input-group input-group-sm" style="min-width: 140px; max-height: 40px;">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input
                                    id="searchTahun"
                                    type="number"
                                    class="form-control"
                                    name="searchTahun"
                                    placeholder="Tahun..."
                                    value="{{ request('searchTahun') }}"
                                    autocomplete="off"
                                />
                            </div>

                            @php
                                $hasFilter =
                                    request()->filled('searchKaryawan') ||
                                    request()->filled('searchTahun') ||
                                    request()->filled('perPage');
                            @endphp

                            @if($hasFilter)
                                <a href="{{ url()->current() }}" class="btn btn-light btn-sm" style="max-height: 40px;">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            @endif

                            <!-- Per page -->
                            <select id="perPage" name="perPage" class="form-select form-select-sm"
                                    style="min-width: 110px; max-height: 40px;">
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

                        {{-- Generate saldo --}}
                        <button type="button"
                                class="btn btn-success btn-sm"
                                id="btnGenerateSaldo"
                                data-url="{{ route('admin.transaksi.saldo-cuti-tahunan.generateNewSaldo') }}">
                            <i class="bi bi-arrow-repeat me-1"></i> Generate Tahun Ini
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($saldoCutis->count() === 0)
                    <div class="alert alert-light-primary mb-0">
                        <i class="bi bi-info-circle me-1"></i>
                        No saldo cuti tahunan found.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                            <tr>
                                <th style="width: 70px;">No</th>
                                <th>Karyawan</th>
                                <th>Tahun</th>
                                <th>Saldo</th>
                                <th>Sisa Saldo</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th>Jabatan</th>
                                <th>Updated At</th>
                                <th class="text-end" style="width: 120px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($saldoCutis as $i => $sc)
                                <tr>
                                    <td class="text-muted">{{ $saldoCutis->firstItem() + $i }}</td>

                                    <td class="fw-semibold">
                                        {{ $sc->nama_karyawan }}
                                        <div class="text-muted small">
                                            ID: <span class="font-monospace">{{ $sc->m_karyawan_id }}</span>
                                        </div>
                                    </td>

                                    <td>{{ $sc->tahun }}</td>
                                    <td class="fw-semibold">{{ $sc->saldo }}</td>
                                    <td class="fw-semibold">{{ $sc->sisa_saldo }}</td>

                                    <td>{{ $sc->nama_perusahaan ?? '-' }}</td>
                                    <td>{{ $sc->nama_department ?? '-' }}</td>
                                    <td>{{ $sc->nama_jabatan ?? '-' }}</td>

                                    <td class="text-muted small">{{ optional($sc->updated_at)->format('Y-m-d H:i') }}</td>

                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ route('admin.transaksi.saldo-cuti-tahunan.edit', $sc->id) }}">
                                                        <i class="bi bi-pencil-square me-2"></i> Edit
                                                    </a>
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
            const indexUrl = @json(route('admin.transaksi.saldo-cuti-tahunan.index'));
            const csrf = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

            const $form = $('#filterForm');
            const $searchKaryawan = $('#searchKaryawan');
            const $searchTahun = $('#searchTahun');
            const $perPage = $('#perPage');

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

            $searchKaryawan.on('input', debouncedSubmit);
            $searchTahun.on('input', debouncedSubmit);
            $perPage.on('change', () => submitFilter(true));

            // ===== Generate saldo tahunan =====
            $('#btnGenerateSaldo').on('click', function () {
                const url = $(this).data('url');

                Swal.fire({
                    title: 'Generate saldo cuti tahunan?',
                    html: `Generate saldo untuk <b>tahun {{ \Carbon\Carbon::now()->year }}</b> ke seluruh karyawan.<br>
                       <small class="text-muted">Proses ini bisa memakan waktu jika data banyak.</small>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, generate',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#198754'
                }).then((r) => {
                    if (!r.isConfirmed) return;

                    // loading
                    Swal.fire({
                        title: 'Processing...',
                        html: 'Sedang menjalankan generate saldo...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: { _token: csrf },
                        success: function (res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res?.message || 'Generate saldo berhasil dipicu',
                                confirmButtonText: 'OK'
                            }).then(() => window.location.href = indexUrl);
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Gagal generate saldo';
                            Swal.fire({ icon: 'error', title: 'Error', text: msg });
                        }
                    });
                });
            });
        });
    </script>
@endpush
