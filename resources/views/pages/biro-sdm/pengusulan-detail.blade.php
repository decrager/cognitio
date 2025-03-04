<div class="box-header with-border text-center">
    <h3 class="box-title">Detail Pegawai yang Diusulkan</h3>
</div>
<form action="{{ route('biro-sdm.pengusulan.edit') }}" method="GET">
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
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="box-footer">
        <input type="text" hidden name="id_program" value="{{ $id_program }}">
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-danger" id="btn-delete">Hapus Semua</button>
            <button type="submit" class="btn btn-success">Edit</button>
        </div>
    </div>
</form>

<form action="{{ route('biro-sdm.pengusulan.delete') }}" hidden method="POST" id="formDelete">
    @csrf
    <input type="text" hidden name="id_program" value="{{ $id_program }}">
</form>

<script>
    $(document).ready(function () {
        $('#employeeTable').DataTable();

        $('#btn-delete').click(function () {
            if (confirm('Apakah anda yakin ingin menghapus semua pegawai yang diusulkan?')) {
                $('#formDelete').submit();
            }
        });
    });
</script>
