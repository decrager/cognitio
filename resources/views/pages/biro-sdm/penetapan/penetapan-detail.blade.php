<div class="box-header with-border text-center">
    <h3 class="box-title">Detail Pegawai yang Diusulkan</h3>
</div>
<form action="{{ route('biro-sdm.penetapan.update') }}" method="POST">
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
                    <th>Status</th>
                    <th>Pilih</th>
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
                        <td>
                            @if ($employee->status == 1)
                                <span data-status="{{$employee->status}}" class="badge badge-warning">Pending</span>
                            @elseif ($employee->status == 2)
                                <span  data-status="{{$employee->status}}"  class="badge badge-primary">Terkonfirmasi</span>
                            @elseif ($employee->status == 3)
                                <span  data-status="{{$employee->status}}"  class="badge badge-danger">Ditolak</span>
                            @elseif ($employee->status == 4)
                                <span  data-status="{{$employee->status}}"  class="badge badge-success">Diterima</span>
                            @endif
                        </td>
                        <td>
                            <input type="checkbox" name="id_pegawai[]" value="{{ $employee->id }}" style="transform: scale(1.5);"
                            @if ($employee->status != 2)
                                disabled
                            @endif>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="box-footer">
        <input type="text" hidden name="id_program" value="{{ $id_program }}">
        <div class="d-flex justify-content-between">
            <button type="button" class="btn btn-danger" id="btn-delete">Hapus Pengusulan yang Ditolak</button>
            <button type="submit" id="btnTetapkan" disabled class="btn btn-success">Tetapkan</button>
        </div>
    </div>
</form>

<form action="{{ route('biro-sdm.penetapan.delete') }}" hidden method="POST" id="formDelete">
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
            if (confirm('Apakah anda yakin ingin menghapus semua pegawai yang ditolak?')) {
                $('#formDelete').submit();
            }
        });

        // Disabled id btnTetapkan if There Is No Status 2
        let status = 0;
        $('#employeeTable tbody tr').each(function () {
            status = $(this).find('td').eq(5).find('span').data('status');
            if (status == 2) {
                $('#btnTetapkan').prop('disabled', false);
                return false;
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
