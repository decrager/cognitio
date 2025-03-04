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
        <input type="text" hidden name="id_program" value="{{ $id_program }}">
        <button type="submit" class="btn btn-success">Ajukan</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#employeeTable').DataTable();
    });
</script>
