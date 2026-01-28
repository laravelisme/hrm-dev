@extends('layouts.master')

@section('title', 'Tes Tulis - Show Test')
@section('subtitle', 'Recruitment')

@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header d-flex flex-column flex-lg-row justify-content-between gap-2">
                <div>
                    <h4 class="mb-0">Show Test - Tes Tulis</h4>
                    <p class="text-muted mb-0">
                        {{ $calonKaryawan->nama_lengkap }}
                        <span class="ms-2 text-muted">
                            NIK: <span class="font-monospace">{{ $calonKaryawan->nik }}</span>
                        </span>
                    </p>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.calon-karyawan.test-tulis.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>

                    <button type="button"
                            id="btnGenerateLink"
                            class="btn btn-primary btn-sm"
                            data-url="{{ route('admin.calon-karyawan.test-tulis.generateTest', $calonKaryawan->id) }}">
                        <i class="bi bi-magic me-1"></i> Generate Link
                    </button>
                </div>
            </div>

            <div class="card-body">
                @php
                    $deadlinePs = optional($testTulis->deadline_psikologi)->format('Y-m-d\TH:i');
                    $deadlineTk = optional($testTulis->deadline_teknikal)->format('Y-m-d\TH:i');
                @endphp

                {{-- Info singkat --}}
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="border rounded p-3 h-100">
                            <div class="text-muted small">Company</div>
                            <div class="fw-semibold">{{ $calonKaryawan->company_name ?? '-' }}</div>

                            <div class="text-muted small mt-2">Department</div>
                            <div class="fw-semibold">{{ $calonKaryawan->department_name ?? '-' }}</div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-light-primary text-dark">
                                    Status: {{ optional($calonKaryawan->latestStatusRecruitment)->status ?? '-' }}
                                </span>

                                <span id="badgePsikologi" class="badge bg-light-secondary text-dark">
                                    Psikologi: {{ $testTulis->status_psikologi ?? '-' }}
                                </span>

                                <span id="badgeTeknikal" class="badge bg-light-secondary text-dark">
                                    Teknikal: {{ $testTulis->status_teknikal ?? '-' }}
                                </span>
                            </div>

                            <div class="mt-3 text-muted small">
                                Token: <span id="txtToken" class="font-monospace">{{ $testTulis->token ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Link Test --}}
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
                        <h5 class="mb-0">Link Tes</h5>
                        <div class="text-muted small">
                            Generate link untuk menentukan test teknikal dan tipe psikologi (DISC/PAPI/CFIT).
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- Link Psikologi (1 link) --}}
                        <div class="col-md-6">
                            <label class="form-label">Link Tes Psikologi</label>
                            <div class="input-group">
                                <input id="linkPsikologi" type="text" class="form-control"
                                       value="{{ $testTulis->test_psikologi ?? '' }}" readonly>
                                <button class="btn btn-outline-secondary btn-copy" type="button"
                                        data-target="#linkPsikologi">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>

                            {{-- badges tipe psikologi --}}
                            <div class="mt-2" id="psikologiTypesWrap" style="display:none;">
                                <div class="text-muted small mb-1">Psikologi dipilih:</div>
                                <div id="psikologiTypesBadges" class="d-flex flex-wrap gap-2"></div>
                            </div>

                            <div class="form-text">Klik generate untuk membuat/update link.</div>
                        </div>

                        {{-- Link Teknikal --}}
                        <div class="col-md-6">
                            <label class="form-label">Link Tes Teknikal</label>
                            <div class="input-group">
                                <input id="linkTeknikal" type="text" class="form-control"
                                       value="{{ $testTulis->test_teknikal ?? '' }}" readonly>
                                <button class="btn btn-outline-secondary btn-copy" type="button"
                                        data-target="#linkTeknikal">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                            <div class="form-text">Jika teknikal OFF, link akan kosong.</div>
                        </div>
                    </div>
                </div>

                {{-- Hasil Test --}}
                <div class="border rounded p-3 mt-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-2">
                        <h5 class="mb-0">Hasil Test</h5>
                        <div class="text-muted small">
                            Link hasil akan terisi setelah test selesai (atau setelah di-set oleh sistem).
                        </div>
                    </div>

                    <div class="row g-3">
                        {{-- Hasil Psikologi --}}
                        <div class="col-md-6">
                            <label class="form-label">Link Hasil Psikologi</label>
                            <div class="input-group">
                                <input id="resultPsikologi" type="text" class="form-control"
                                       value="{{ $testTulis->result_psikologi ?? '' }}" readonly>
                                <button class="btn btn-outline-secondary btn-copy" type="button"
                                        data-target="#resultPsikologi">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                            <div class="form-text">Klik copy untuk menyalin link hasil psikologi.</div>
                        </div>

                        {{-- Hasil Teknikal --}}
                        <div class="col-md-6">
                            <label class="form-label">Link Hasil Teknikal</label>
                            <div class="input-group">
                                <input id="resultTeknikal" type="text" class="form-control"
                                       value="{{ $testTulis->result_teknikal ?? '' }}" readonly>
                                <button class="btn btn-outline-secondary btn-copy" type="button"
                                        data-target="#resultTeknikal">
                                    <i class="bi bi-clipboard"></i>
                                </button>
                            </div>
                            <div class="form-text">Klik copy untuk menyalin link hasil teknikal.</div>
                        </div>
                    </div>
                </div>

                {{-- Deadline --}}
                <div class="border rounded p-3">
                    <h5 class="mb-3">Deadline</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Deadline Psikologi</label>
                            <input id="deadlinePsikologi" type="datetime-local" class="form-control"
                                   value="{{ $deadlinePs }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Deadline Teknikal</label>
                            <input id="deadlineTeknikal" type="datetime-local" class="form-control"
                                   value="{{ $deadlineTk }}">
                        </div>

                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="button"
                                    id="btnSaveDeadline"
                                    class="btn btn-success"
                                    data-url="{{ route('admin.calon-karyawan.test-tulis.updateDeadline', $calonKaryawan->id) }}">
                                <i class="bi bi-save me-1"></i> Save Deadline
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(function () {
            const csrf = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').val();

            // ===== Copy helper =====
            async function copyText(text) {
                try {
                    await navigator.clipboard.writeText(text);
                    return true;
                } catch (e) {
                    const $tmp = $('<input>');
                    $('body').append($tmp);
                    $tmp.val(text).select();
                    document.execCommand('copy');
                    $tmp.remove();
                    return true;
                }
            }

            $(document).on('click', '.btn-copy', async function () {
                const target = $(this).data('target');
                const val = ($(target).val() || '').trim();
                if (!val) {
                    Swal.fire({ icon: 'info', title: 'Kosong', text: 'Link masih kosong. Klik Generate dulu.' });
                    return;
                }
                await copyText(val);
                Swal.fire({ icon: 'success', title: 'Copied', text: 'Link berhasil dicopy.' });
            });

            // ===== helper: render badges psikologi =====
            function renderPsikologiTypes(types) {
                const $wrap = $('#psikologiTypesWrap');
                const $badges = $('#psikologiTypesBadges');

                $badges.empty();

                const clean = (types || [])
                    .map(x => String(x || '').trim().toUpperCase())
                    .filter(Boolean);

                if (!clean.length) {
                    $wrap.hide();
                    return;
                }

                clean.forEach(t => {
                    $badges.append(`<span class="badge bg-light-primary text-dark">${t}</span>`);
                });

                $wrap.show();
            }

            // ===== helper: parse types dari URL link psikologi (fallback) =====
            function parseTypesFromPsikologiLink(link) {
                try {
                    if (!link) return [];
                    const u = new URL(link);
                    const t = u.searchParams.get('types'); // kalau backend encode ?types=DISC,PAPI
                    if (!t) return [];
                    return t.split(',').map(x => x.trim().toUpperCase()).filter(Boolean);
                } catch (e) {
                    return [];
                }
            }

            // initial render dari link (kalau ada ?types=)
            renderPsikologiTypes(parseTypesFromPsikologiLink($('#linkPsikologi').val()));

            // ===== Generate link (modal sesuai GenerateTestFormRequest) =====
            $('#btnGenerateLink').on('click', function () {
                const url = $(this).data('url');

                // prefill teknikal dari link existing
                const hasTeknikal = ($('#linkTeknikal').val() || '').trim().length > 0;

                // prefill psikologi dari badges existing (kalau sebelumnya sudah parse dari URL)
                const currentFromLink = parseTypesFromPsikologiLink($('#linkPsikologi').val());
                const isDISC = currentFromLink.includes('DISC');
                const isPAPI = currentFromLink.includes('PAPI');
                const isCFIT = currentFromLink.includes('CFIT');

                Swal.fire({
                    title: 'Generate link test',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Generate',
                    cancelButtonText: 'Batal',
                    focusConfirm: false,
                    html: `
                <div class="text-start">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="swTeknikal" ${hasTeknikal ? 'checked' : ''}>
                        <label class="form-check-label" for="swTeknikal">Test Teknikal</label>
                    </div>

                    <div class="fw-semibold mb-2">Test Psikologi (pilih minimal 1)</div>

                    <div class="form-check">
                        <input class="form-check-input psy-check" type="checkbox" id="cbDISC" value="DISC" ${isDISC ? 'checked' : ''}>
                        <label class="form-check-label" for="cbDISC">DISC</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input psy-check" type="checkbox" id="cbPAPI" value="PAPI" ${isPAPI ? 'checked' : ''}>
                        <label class="form-check-label" for="cbPAPI">PAPI</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input psy-check" type="checkbox" id="cbCFIT" value="CFIT" ${isCFIT ? 'checked' : ''}>
                        <label class="form-check-label" for="cbCFIT">CFIT</label>
                    </div>

                    <div class="text-muted small mt-2">
                        Ini hanya menentukan opsi generate (dipakai service lain). Link psikologi tetap 1.
                    </div>
                </div>
            `,
                    preConfirm: () => {
                        const teknikal = $('#swTeknikal').is(':checked');
                        const psikologi = $('.psy-check:checked').map((_, el) => $(el).val()).get();

                        // sesuai rules GenerateTestFormRequest: test_psikologi required|array
                        if (!psikologi.length) {
                            Swal.showValidationMessage('Pilih minimal 1 test psikologi (DISC/PAPI/CFIT).');
                            return false;
                        }

                        return { teknikal, psikologi };
                    }
                }).then((r) => {
                    if (!r.isConfirmed) return;

                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: csrf,
                            test_teknikal: r.value.teknikal ? 1 : 0,
                            test_psikologi: r.value.psikologi
                        },
                        success: function (res) {
                            const data = res?.data || res;

                            // update fields
                            $('#linkPsikologi').val(data?.test_psikologi || '');
                            $('#linkTeknikal').val(data?.test_teknikal || '');

                            // update token + status badges (kalau ada)
                            if (data?.token) $('#txtToken').text(data.token);
                            if (data?.status_psikologi) $('#badgePsikologi').text(`Psikologi: ${data.status_psikologi}`);
                            if (data?.status_teknikal) $('#badgeTeknikal').text(`Teknikal: ${data.status_teknikal}`);

                            // render badges psikologi:
                            // prioritas: dari response `psikologi_types`, fallback dari URL link psikologi
                            const types = Array.isArray(data?.psikologi_types)
                                ? data.psikologi_types
                                : parseTypesFromPsikologiLink(data?.test_psikologi);

                            renderPsikologiTypes(types);

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res?.message || 'Link test berhasil digenerate'
                            });
                        },
                        error: function (xhr) {
                            const msg = xhr.responseJSON?.message || 'Gagal generate link';
                            Swal.fire({ icon: 'error', title: 'Error', text: msg });
                        }
                    });
                });
            });

            // ===== Save deadline =====
            $('#btnSaveDeadline').on('click', function () {
                const url = $(this).data('url');
                const deadline_psikologi = $('#deadlinePsikologi').val();
                const deadline_teknikal  = $('#deadlineTeknikal').val();

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        _token: csrf,
                        _method: 'PUT',
                        deadline_psikologi,
                        deadline_teknikal
                    },
                    success: function (res) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Saved',
                            text: res?.message || 'Deadline berhasil disimpan'
                        });
                    },
                    error: function (xhr) {
                        const msg = xhr.responseJSON?.message || 'Gagal menyimpan deadline';
                        Swal.fire({ icon: 'error', title: 'Error', text: msg });
                    }
                });
            });
        });
    </script>
@endpush
