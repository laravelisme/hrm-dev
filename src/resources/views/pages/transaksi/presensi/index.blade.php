@extends('layouts.master')

@section('title', 'Transaksi - Presensi')
@section('subtitle', 'Transaksi')

@push('styles')
    {{-- Leaflet --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <style>
        #leafletMap { height: 420px; width: 100%; border-radius: 10px; }
        .mono { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
    </style>
@endpush

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-2">
                    <div>
                        <h4 class="mb-0">Presensi</h4>
                        <p class="text-muted mb-0">
                            Total: <span class="fw-bold">{{ $presensis->total() }}</span> presensi
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
                                <input id="bulanTahunPicker" type="month" class="form-control" value="" title="Bulan-Tahun">
                            </div>

                            {{-- Karyawan (select2 ajax infinite scroll) --}}
                            <select id="searchKaryawan" name="searchKaryawan"
                                    class="form-select form-select-sm" style="min-width: 240px; max-height: 40px;">
                                @if(request()->filled('searchKaryawan'))
                                    <option value="{{ request('searchKaryawan') }}" selected>Selected Karyawan</option>
                                @endif
                            </select>

                            @php
                                $hasFilter =
                                    request()->filled('searchBulanTahun') ||
                                    request()->filled('searchKaryawan') ||
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
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($presensis->count() === 0)
                    <div class="alert alert-light-primary mb-0">
                        <i class="bi bi-info-circle me-1"></i> Tidak ada data presensi.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-lg align-middle">
                            <thead>
                            <tr>
                                <th style="width: 70px;">No</th>
                                <th>Karyawan</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Info</th>
                                <th class="text-end" style="width: 140px;">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($presensis as $i => $p)
                                @php
                                    // fallback kalau relasi karyawan belum dipakai
                                    $nama = $p->nama_karyawan ?? optional($p->karyawan)->nama_karyawan ?? ('Karyawan #' . $p->m_karyawan_id);

                                    $ciTime = $p->check_in_time ? \Carbon\Carbon::parse($p->check_in_time)->format('Y-m-d H:i') : null;
                                    $coTime = $p->check_out_time ? \Carbon\Carbon::parse($p->check_out_time)->format('Y-m-d H:i') : null;

                                    $ciLat = $p->check_in_latitude;
                                    $ciLng = $p->check_in_longitude;
                                    $coLat = $p->check_out_latitude;
                                    $coLng = $p->check_out_longitude;

                                    $ciImg = $p->check_in_img;
                                    $coImg = $p->check_out_img;
                                @endphp

                                <tr>
                                    <td class="text-muted">{{ $presensis->firstItem() + $i }}</td>

                                    <td class="fw-semibold">
                                        {{ $nama }}
                                        <div class="text-muted small">
                                            ID: <span class="mono">{{ $p->m_karyawan_id }}</span>
                                        </div>
                                    </td>

                                    <td class="text-muted small">
                                        <div><b>{{ $ciTime ?? '-' }}</b></div>
                                        <div class="mt-1">
                                            <span class="mono">{{ $ciLat ?? '-' }}</span>,
                                            <span class="mono">{{ $ciLng ?? '-' }}</span>
                                        </div>
                                        @if(!empty($p->check_in_timezone))
                                            <div class="text-muted small">TZ: <span class="mono">{{ $p->check_in_timezone }}</span></div>
                                        @endif
                                    </td>

                                    <td class="text-muted small">
                                        <div><b>{{ $coTime ?? '-' }}</b></div>
                                        <div class="mt-1">
                                            <span class="mono">{{ $coLat ?? '-' }}</span>,
                                            <span class="mono">{{ $coLng ?? '-' }}</span>
                                        </div>
                                        @if(!empty($p->check_out_timezone))
                                            <div class="text-muted small">TZ: <span class="mono">{{ $p->check_out_timezone }}</span></div>
                                        @endif
                                    </td>

                                    <td class="text-muted small" style="max-width: 340px;">
                                        <div class="mb-1"><b>IN:</b> {{ $p->check_in_info ?? '-' }}</div>
                                        <div><b>OUT:</b> {{ $p->check_out_info ?? '-' }}</div>
                                    </td>

                                    <td class="text-end">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots"></i>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <button type="button"
                                                            class="dropdown-item btn-view-img"
                                                            data-title="Foto Check In"
                                                            data-img="{{ $ciImg ?? '' }}">
                                                        <i class="bi bi-image me-2"></i> Lihat Foto Check In
                                                    </button>
                                                </li>
                                                <li>
                                                    <button type="button"
                                                            class="dropdown-item btn-view-img"
                                                            data-title="Foto Check Out"
                                                            data-img="{{ $coImg ?? '' }}">
                                                        <i class="bi bi-image me-2"></i> Lihat Foto Check Out
                                                    </button>
                                                </li>

                                                <li><hr class="dropdown-divider"></li>

                                                <li>
                                                    <button type="button"
                                                            class="dropdown-item btn-view-map"
                                                            data-name="{{ $nama }}"
                                                            data-ci-time="{{ $ciTime ?? '' }}"
                                                            data-co-time="{{ $coTime ?? '' }}"
                                                            data-ci-lat="{{ $ciLat ?? '' }}"
                                                            data-ci-lng="{{ $ciLng ?? '' }}"
                                                            data-co-lat="{{ $coLat ?? '' }}"
                                                            data-co-lng="{{ $coLng ?? '' }}"
                                                            data-ci-info="{{ $p->check_in_info ?? '' }}"
                                                            data-co-info="{{ $p->check_out_info ?? '' }}">
                                                        <i class="bi bi-geo-alt me-2"></i> Lihat Peta
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
                            Showing <b>{{ $presensis->firstItem() }}</b> to <b>{{ $presensis->lastItem() }}</b>
                            of <b>{{ $presensis->total() }}</b> results
                        </div>
                        <div>{{ $presensis->links() }}</div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Modal Map Leaflet --}}
    <div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-0" id="mapModalTitle">Lokasi Presensi</h5>
                        <div class="text-muted small" id="mapModalSubtitle">-</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div id="leafletMap"></div>

                    <div class="row g-2 mt-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Check In</div>
                                <div class="fw-semibold" id="ciTextTime">-</div>
                                <div class="text-muted small mt-1">
                                    <span class="mono" id="ciTextCoord">-</span>
                                </div>
                                <div class="text-muted small mt-2">Info</div>
                                <div class="text-muted" id="ciTextInfo" style="white-space: pre-wrap;">-</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <div class="text-muted small">Check Out</div>
                                <div class="fw-semibold" id="coTextTime">-</div>
                                <div class="text-muted small mt-1">
                                    <span class="mono" id="coTextCoord">-</span>
                                </div>
                                <div class="text-muted small mt-2">Info</div>
                                <div class="text-muted" id="coTextInfo" style="white-space: pre-wrap;">-</div>
                            </div>
                        </div>
                    </div>

                    <div class="text-muted small mt-2">
                        *Jika salah satu koordinat kosong, map akan menampilkan titik yang tersedia.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Leaflet --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        $(function () {
            const indexUrl = @json(route('admin.transaksi.presensi.index'));
            const karyawanOptionsUrl = @json(route('admin.transaksi.presensi.karyawan-options'));

            const $form = $('#filterForm');
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

            // ===== Bulan-Tahun (month -> MM-YYYY) =====
            const $hiddenBulanTahun = $('#searchBulanTahun');
            const $picker = $('#bulanTahunPicker');

            // Prefill month picker dari request('searchBulanTahun') format MM-YYYY -> YYYY-MM
            const raw = String($hiddenBulanTahun.val() || '').trim();
            if (raw) {
                const parts = raw.split('-'); // MM-YYYY
                if (parts.length === 2) {
                    const mm = parts[0];
                    const yyyy = parts[1];
                    if (mm && yyyy) $picker.val(`${yyyy}-${String(mm).padStart(2,'0')}`);
                }
            }

            $picker.on('change', function () {
                const v = String($(this).val() || '').trim(); // YYYY-MM
                if (!v) {
                    $hiddenBulanTahun.val('');
                } else {
                    const [yyyy, mm] = v.split('-');
                    $hiddenBulanTahun.val(`${mm}-${yyyy}`); // MM-YYYY
                }
                debouncedSubmit();
            });

            // ===== Select2 AJAX (infinite scroll) =====
            function initSelect2Ajax($el, url, placeholder) {
                $el.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder,
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

                // auto submit saat berubah (debounce)
                $el.on('select2:select select2:unselect', debouncedSubmit);
            }

            initSelect2Ajax($('#searchKaryawan'), karyawanOptionsUrl, 'Karyawan...');

            $perPage.on('change', () => submitFilter(true));

            // ===== Helpers safe string =====
            function s(v) {
                if (v === null || v === undefined) return '';
                return String(v);
            }

            // ===== View Image (Check In / Check Out) =====
            const storageBase = @json(asset('storage'));

            function resolveImgUrl(raw) {
                const v = s(raw).trim();
                if (!v) return '';
                if (v.startsWith('http://') || v.startsWith('https://')) return v;

                // kalau sudah "storage/..." atau "/storage/..."
                if (v.startsWith('storage/')) return `${window.location.origin}/${v}`;
                if (v.startsWith('/storage/')) return `${window.location.origin}${v}`;

                // default anggap relative path di storage/app/public
                return `${storageBase}/${v.replace(/^\/+/, '')}`;
            }

            $(document).on('click', '.btn-view-img', function () {
                const title = s($(this).data('title')).trim() || 'Foto';
                const raw = $(this).data('img');
                const url = resolveImgUrl(raw);

                if (!url) {
                    Swal.fire({ icon:'info', title:'Kosong', text:'Foto belum tersedia.' });
                    return;
                }

                Swal.fire({
                    title: title,
                    imageUrl: url,
                    imageAlt: title,
                    showCloseButton: true,
                    confirmButtonText: 'Tutup'
                });
            });

            // ===== Map Leaflet =====
            let map, markerIn, markerOut, line;

            function parseCoord(v) {
                const n = parseFloat(s(v).trim());
                return Number.isFinite(n) ? n : null;
            }

            function ensureMap() {
                if (map) return;

                map = L.map('leafletMap', { scrollWheelZoom: true });
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(map);
            }

            function clearMapLayers() {
                if (markerIn) { map.removeLayer(markerIn); markerIn = null; }
                if (markerOut) { map.removeLayer(markerOut); markerOut = null; }
                if (line) { map.removeLayer(line); line = null; }
            }

            $(document).on('click', '.btn-view-map', function () {
                const name = s($(this).data('name')).trim() || 'Karyawan';
                const ciTime = s($(this).data('ci-time')).trim();
                const coTime = s($(this).data('co-time')).trim();

                const ciLat = parseCoord($(this).data('ci-lat'));
                const ciLng = parseCoord($(this).data('ci-lng'));
                const coLat = parseCoord($(this).data('co-lat'));
                const coLng = parseCoord($(this).data('co-lng'));

                const ciInfo = s($(this).data('ci-info')).trim();
                const coInfo = s($(this).data('co-info')).trim();

                // fill modal text
                $('#mapModalTitle').text('Lokasi Presensi');
                $('#mapModalSubtitle').text(name);

                $('#ciTextTime').text(ciTime || '-');
                $('#coTextTime').text(coTime || '-');

                $('#ciTextCoord').text((ciLat !== null && ciLng !== null) ? `${ciLat}, ${ciLng}` : '-');
                $('#coTextCoord').text((coLat !== null && coLng !== null) ? `${coLat}, ${coLng}` : '-');

                $('#ciTextInfo').text(ciInfo || '-');
                $('#coTextInfo').text(coInfo || '-');

                const modalEl = document.getElementById('mapModal');
                const bsModal = new bootstrap.Modal(modalEl);
                bsModal.show();

                modalEl.addEventListener('shown.bs.modal', function onShown() {
                    modalEl.removeEventListener('shown.bs.modal', onShown);

                    ensureMap();
                    map.invalidateSize();
                    clearMapLayers();

                    const points = [];

                    if (ciLat !== null && ciLng !== null) {
                        markerIn = L.marker([ciLat, ciLng]).addTo(map)
                            .bindPopup(`<b>Check In</b><br>${name}<br>${ciTime || ''}<br><small>${ciInfo || ''}</small>`);
                        points.push([ciLat, ciLng]);
                    }

                    if (coLat !== null && coLng !== null) {
                        markerOut = L.marker([coLat, coLng]).addTo(map)
                            .bindPopup(`<b>Check Out</b><br>${name}<br>${coTime || ''}<br><small>${coInfo || ''}</small>`);
                        points.push([coLat, coLng]);
                    }

                    if (points.length === 0) {
                        map.setView([-2.5489, 118.0149], 4);
                        return;
                    }

                    if (points.length === 2) {
                        line = L.polyline(points, { weight: 4 }).addTo(map);
                        const bounds = L.latLngBounds(points);
                        map.fitBounds(bounds.pad(0.25));
                    } else {
                        map.setView(points[0], 16);
                    }
                });
            });
        });
    </script>
@endpush
