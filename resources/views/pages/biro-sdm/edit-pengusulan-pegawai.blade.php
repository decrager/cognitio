@extends('layouts.app')

@section('title', 'Edit Pengusulan Pegawai')

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
        <div class="box-body" id="formProgram">
            <div class="form-group">
                <label><strong>Nama Program Pelatihan</strong></label><br>
                <select id="listProgram" name="nama_pelatihan" class="form-control select2" style="width: 50%;" disabled>
                    <option selected>{{ $program->nama_pelatihan }}</option>
                </select>
            </div>
            <div class="form-group">
                <label><strong>Lokasi Pelatihan</strong></label><br>
                <select id="listLocation" name="lokasi" class="form-control select2" style="width: 50%;" disabled>
                    <option selected>{{ $program->lokasi }}</option>
                </select>
            </div>
            <div class="form-group">
                <label><strong>Tanggal Pelaksanaan</strong></label><br>
                <select id="listSchedule" name="tanggal_mulai" class="form-control select2" style="width: 50%;" disabled>
                    <option selected>{{ $program->tanggal_mulai }} - {{ $program->tanggal_selesai }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="box box-info" id="formPegawai">
        <div class="box-header with-border text-center">
            <h3 class="box-title">Pilih Pegawai</h3>
        </div>
        <form action="{{ route('biro-sdm.pengusulan.updateOrCreate') }}" method="POST">
            @csrf
            <div class="box-body">
                <table id="employeeTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Jabatan</th>
                            <th>Unit Kerja</th>
                            <th>Pilih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pegawai as $employee)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $employee->nama }}</td>
                                <td>{{ $employee->nip }}</td>
                                <td>{{ $employee->jabatan->nama_jabatan }}</td>
                                <td>{{ $employee->unit->nama_unit }}</td>
                                <td>
                                    <input type="checkbox" name="id_pegawai[]" value="{{ $employee->id }}" style="transform: scale(1.5);" @if ($employee->id_assignment != null) checked @endif>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="box-footer">
                <input type="text" hidden name="update" value="true">
                <input type="text" hidden name="id_program" value="{{ $program->id }}">
                <button type="submit" class="btn btn-success">Ajukan</button>
            </div>
        </form>
        
        <script>
            $(document).ready(function () {
                $('#employeeTable').DataTable();
            });
        </script>        
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#employeeTable').DataTable();
        });
    </script>
@endpush