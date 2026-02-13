@extends('layouts.master')

@section('title','Interview Link')
@section('subtitle','Recruitment')

@section('content')
    <section class="section">
        <div class="card">

            {{-- HEADER --}}
            <div class="card-header d-flex justify-content-between align-items-center">

                <div>
                    <h4 class="mb-0">Interview Link</h4>
                    <small class="text-muted">
                        Calon Karyawan :
                        <span class="font-monospace">{{ $interview->calonKaryawan->nama_lengkap }}</span>
                    </small>
                </div>

                <div class="d-flex gap-2">

                    <button type="button"
                            id="btnShareWa"
                            class="btn btn-success btn-sm"
                            data-phone="{{ $interview->calonKaryawan->no_telp ?? '' }}"
                            data-name="{{ $interview->calonKaryawan->nama_lengkap ?? '' }}">
                        <i class="bi bi-whatsapp"></i> Share WA
                    </button>

                    <button id="btnEdit" class="btn btn-warning btn-sm">
                        Edit
                    </button>

                    <button id="btnSave" class="btn btn-success btn-sm d-none">
                        Save
                    </button>

                    <a href="{{ route('admin.calon-karyawan.interview.index') }}"
                       class="btn btn-light btn-sm">
                        Back
                    </a>

                </div>

            </div>

            <div class="card-body">

                <input type="hidden" id="interviewId" value="{{ $interview->id }}">

                {{-- ================= HR INTERVIEW ================= --}}
                <div class="border rounded p-3 mb-4">
                    <h5>Interview HR</h5>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label>Tanggal</label>
                            <input type="date"
                                   name="interview_date_hr"
                                   class="form-control editable"
                                   value="{{ $interview->interview_date_hr }}"
                                   readonly>
                        </div>

                        <div class="col-md-6">
                            <label>Jam</label>
                            <input type="time"
                                   name="interview_time_hr"
                                   class="form-control editable"
                                   value="{{ $interview->interview_time_hr }}"
                                   readonly>
                        </div>

                        <div class="col-md-12">
                            <label>Location / Link</label>

                            <div class="input-group">

                                <input id="hrLink"
                                       name="interview_hr_location"
                                       class="form-control editable"
                                       value="{{ $interview->interview_hr_location }}"
                                       readonly>

                                <button class="btn btn-outline-secondary btn-copy"
                                        data-target="#hrLink">
                                    📋
                                </button>

                            </div>

                        </div>

                        <div class="col-md-12">
                            <label>Status</label>
                            <span class="badge bg-primary">
                            {{ $interview->interview_hr_status }}
                            </span>
                        </div>

                        <div class="col-md-12">
                            <label>Notes</label>
                            <textarea
                                name="interview_hr_notes"
                                class="form-control editable"
                                rows="2"
                                readonly>{{ $interview->interview_hr_notes }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- ================= USER INTERVIEW ================= --}}
                <div class="border rounded p-3">
                    <h5>Interview User</h5>

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label>Tanggal</label>
                            <input type="date"
                                   name="interview_date_user"
                                   class="form-control editable"
                                   value="{{ $interview->interview_date_user }}"
                                   readonly>
                        </div>

                        <div class="col-md-6">
                            <label>Jam</label>
                            <input type="time"
                                   name="interview_time_user"
                                   class="form-control editable"
                                   value="{{ $interview->interview_time_user }}"
                                   readonly>
                        </div>

                        <div class="col-md-12">
                            <label>Location / Link</label>

                            <div class="input-group">

                                <input id="userLink"
                                       name="interview_user_location"
                                       class="form-control editable"
                                       value="{{ $interview->interview_user_location }}"
                                       readonly>

                                <button class="btn btn-outline-secondary btn-copy"
                                        data-target="#userLink">
                                    📋
                                </button>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <label>Status</label>
                            <span class="badge bg-success">
                            {{ $interview->interview_user_status }}
                            </span>
                        </div>

                        <div class="col-md-12">
                            <label>Notes</label>
                            <textarea
                                name="interview_user_notes"
                                class="form-control editable"
                                rows="2"
                                readonly>{{ $interview->interview_user_notes }}</textarea>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>

        function normalizeWaNumber(input) {
            if (!input) return '';
            let p = String(input).trim().replace(/[^\d+]/g, '');
            if (p.startsWith('+')) p = p.substring(1);
            if (p.startsWith('0')) p = '62' + p.substring(1);
            return p;
        }

        $('#btnShareWa').click(function(){

            let name  = $(this).data('name') || 'Kandidat';
            let phone = normalizeWaNumber($(this).data('phone'));

            if(!phone){
                Swal.fire({
                    icon:'info',
                    title:'Nomor kosong',
                    text:'No WhatsApp kandidat belum tersedia'
                });
                return;
            }

            let hrDate = $('input[name="interview_date_hr"]').val();
            let hrTime = $('input[name="interview_time_hr"]').val();
            let hrLink = $('#hrLink').val();

            let userDate = $('input[name="interview_date_user"]').val();
            let userTime = $('input[name="interview_time_user"]').val();
            let userLink = $('#userLink').val();

            let msg = `Halo ${name},

            Selamat! Anda dijadwalkan untuk interview.

            📌 *Interview HR*
            Tanggal: ${hrDate || '-'}
            Jam: ${hrTime || '-'}
            Link: ${hrLink || '-'}

            📌 *Interview User*
            Tanggal: ${userDate || '-'}
            Jam: ${userTime || '-'}
            Link: ${userLink || '-'}

            Mohon hadir tepat waktu.
            Terima kasih.

            HR Recruitment`;

            let waUrl = `https://wa.me/${phone}?text=${encodeURIComponent(msg)}`;

            Swal.fire({
                title:'Buka WhatsApp?',
                icon:'question',
                showCancelButton:true,
                confirmButtonText:'Buka WA'
            }).then(r=>{
                if(r.isConfirmed){
                    window.open(waUrl,'_blank');
                }
            });

        });

        $.ajaxSetup({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click','.btn-copy',function(){

            let text = $($(this).data('target')).val();

            navigator.clipboard.writeText(text);

            Swal.fire({
                icon:'success',
                title:'Copied!',
                text:'Link berhasil disalin',
                timer:1200,
                showConfirmButton:false
            });

        });


        $('#btnEdit').click(function(){

            $('.editable').prop('readonly',false);

            $('#btnEdit').addClass('d-none');
            $('#btnSave').removeClass('d-none');

        });


        $('#btnSave').click(function(){

            let id = $('#interviewId').val();

            let data = {};

            $('.editable').each(function(){
                data[$(this).attr('name')] = $(this).val();
            });

            data._token = $('meta[name="csrf-token"]').attr('content');

            Swal.fire({
                title:'Saving...',
                allowOutsideClick:false,
                didOpen:()=>Swal.showLoading()
            });

            $.ajax({

                url:`/calon-karyawan/interview/${id}/generate-link-zoom`,
                method:'POST',
                data:data,

                success:function(){

                    Swal.fire({
                        icon:'success',
                        title:'Saved!',
                        text:'Interview berhasil diupdate'
                    });

                    $('.editable').prop('readonly',true);

                    $('#btnEdit').removeClass('d-none');
                    $('#btnSave').addClass('d-none');

                },

                error:function(){

                    Swal.fire({
                        icon:'error',
                        title:'Oops...',
                        text:'Gagal update interview'
                    });

                }

            });

        });

    </script>
@endpush
