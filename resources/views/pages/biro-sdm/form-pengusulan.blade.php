@extends('layouts.app')

@section('title', 'Tambah Pengusulan Pegawai')

@section('content')
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <span><i class="icon fa fa-ban"></i>{{ session('error') }}</span>
        </div>
    @elseif (session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <span><i class="icon fa fa-check"></i>{{ session('success') }}</span>
        </div>
    @endif

    <div class="box box-primary">
        <div class="box-header with-border text-center">
            <h3 class="box-title">Pilih Program Pelatihan</h3>
        </div>
        <form role="form">
        <div class="box-body" style="display: none;" id="formProgram">
            <div class="form-group">
                <label><strong>Nama Program Pelatihan</strong></label><br>
                <select id="listProgram" name="nama_pelatihan" class="form-control select2" style="width: 50%;">
                    <option value="pilih" selected>Pilih</option>
                </select>
            </div>
            <div class="form-group">
                <label><strong>Lokasi Pelatihan</strong></label><br>
                <select id="listLocation" name="lokasi" class="form-control select2" style="width: 50%;" disabled>
                    <option value="pilih" selected>Pilih</option>
                </select>
            </div>
            <div class="form-group">
                <label><strong>Tanggal Pelaksanaan</strong></label><br>
                <select id="listSchedule" name="tanggal_mulai" class="form-control select2" style="width: 50%;" disabled>
                    <option value="pilih" selected>Pilih</option>
                </select>
            </div>
        </div>
        <div class="box-body" style="height: 100px;" id="loadingProgram" style="display: none;">
            <div class="overlay">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>

        <div class="box-footer">
            <button type="button" class="btn btn-primary" id="proceed">Lanjut</button>
        </div>
        </form>
    </div>

    <div class="box box-info" id="formPegawai" style="display: none;">
        {{-- form-pegawai-usulan --}}
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#loadingProgram').show();
            $.ajax({
                url: '{{ route('biro-sdm.pengusulan.listProgram') }}',
                type: 'GET',
                data: {
                    listProgram: true,
                },
                success: function (data) {
                    let programList = '';
                    data.forEach(function (program) {
                        programList += `<option value="${program.nama_pelatihan}">${program.nama_pelatihan}</option>`;
                    });
                    $('#listProgram').append(programList);
                    $('.select2').select2();
                    
                    $('#loadingProgram').hide();
                    $('#formProgram').show();
                }
            });

            $('#listProgram').on('change', function () {
                $('#loadingProgram').show();
                $('#formProgram').hide();
                
                $('#listLocation').empty();
                $('#listLocation').append('<option value="pilih" selected>Pilih</option>');
                $('#listLocation').val('pilih');

                $('#listSchedule').empty();
                $('#listSchedule').append('<option value="pilih" selected>Pilih</option>');
                $('#listSchedule').val('pilih');
                $('#listSchedule').prop('disabled', true);

                const selectedProgram = $(this).val();
                $.ajax({
                    url: '{{ route('biro-sdm.pengusulan.listProgram') }}',
                    type: 'GET',
                    data: {
                        listLocation: true,
                        nama_pelatihan: selectedProgram,
                    },
                    success: function (data) {
                        let locationList = '';
                        data.forEach(function (location) {
                            locationList += `<option value="${location.lokasi}">${location.lokasi}</option>`;
                        });
                        $('#listLocation').append(locationList);
                        $('#listLocation').prop('disabled', false);
                        $('.select2').select2();

                        $('#loadingProgram').hide();
                        $('#formProgram').show();
                    }
                });
            });

            $('#listLocation').on('change', function () {
                $('#loadingProgram').show();
                $('#formProgram').hide();

                $('#listSchedule').empty();
                $('#listSchedule').append('<option value="pilih" selected>Pilih</option>');
                $('#listSchedule').val('pilih');
                $('#listSchedule').prop('disabled', true);

                const selectedLocation = $(this).val();
                $.ajax({
                    url: '{{ route('biro-sdm.pengusulan.listProgram') }}',
                    type: 'GET',
                    data: {
                        listSchedule: true,
                        nama_pelatihan: $('#listProgram').val(),
                        lokasi: selectedLocation,
                    },
                    success: function (data) {
                        let scheduleList = '';
                        data.forEach(function (schedule) {
                            scheduleList += `<option value="${schedule.id}">${schedule.tanggal_mulai} - ${schedule.tanggal_selesai}</option>`;
                        });
                        $('#listSchedule').append(scheduleList);
                        $('#listSchedule').prop('disabled', false);
                        $('.select2').select2();

                        $('#loadingProgram').hide();
                        $('#formProgram').show();
                    }
                });
            });

            $('#proceed').on('click', function () {
                let id_program = $('#listSchedule').val();
                if (id_program == 'pilih') {
                    alert('Pilih program, lokasi, dan jadwal terlebih dahulu');
                    return;
                }

                $('#formPegawai').empty();
                $('#formPegawai').append(`<div class="box-body" style="height: 100px;" id="loadingPegawai"><div class="overlay"><i class="fa fa-refresh fa-spin"></i></div></div>`);
                $('#formPegawai').show();
                                            
    
                $.ajax({
                    url: '{{ route('biro-sdm.pengusulan.listPegawai') }}',
                    type: 'GET',
                    data: {
                        id_program: id_program,
                    },
                    success: function (data) {
                        $('#formPegawai').empty();
                        $('#formPegawai').html(data);
                    }
                });
            });
        });
    </script>
@endpush