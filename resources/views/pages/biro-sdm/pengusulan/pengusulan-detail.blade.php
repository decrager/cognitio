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
                        <td>
                            <p class="p-0 m-0 text-decoration-none text-primary text-bold" style="cursor: pointer;" onmouseover="this.classList.add('text-info', 'text-decoration-underline');" onmouseout="this.classList.remove('text-info', 'text-decoration-underline');" data-id="{{ $employee->id }}" id="detailPegawai">{{ $employee->nama }}</p>
                            <span class="badge @if($employee->tipe == 'PNS') badge-success @else badge-warning @endif">
                                {{$employee->tipe}}
                            </span>
                        </td>
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
            <button type="button" class="btn btn-danger" id="btn-delete" {{ $program->status != 'aktif' ? 'disabled' : '' }}>Hapus Semua</button>
            <button type="submit" class="btn btn-success" {{ $program->status != 'aktif' ? 'disabled' : '' }}>Edit</button>
        </div>
    </div>
</form>

<form action="{{ route('biro-sdm.pengusulan.delete') }}" hidden method="POST" id="formDelete">
    @csrf
    <input type="text" hidden name="id_program" value="{{ $id_program }}">
</form>

<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div id="modal-loader" class="d-flex justify-content-center">
                    <div  class="spinner-border text-primary" role="status" >
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#employeeTable').DataTable();

        $('#btn-delete').click(function () {
            if (confirm('Apakah anda yakin ingin menghapus semua pegawai yang diusulkan?')) {
                $('#formDelete').submit();
            }
        });

        $('#employeeTable').on('click', '#detailPegawai', function () {
            let id = $(this).data('id');
            var modal = $('#detailModal');
            modal.modal('show');
            $('#modal-loader').show(); // Show the loader

            $.ajax({
                url: '{{ route('biro-sdm.pegawai.show', '') }}/' + id,
                method: 'GET',
                success: function (data) {
                    $('#modal-loader').hide(); // Hide the loader
                    modal.find('.modal-content').html(data);
                },
                error: function () {
                    $('#modal-loader').hide(); // Hide the loader
                    alert('Error');
                }
            });
        });
    });
</script>
