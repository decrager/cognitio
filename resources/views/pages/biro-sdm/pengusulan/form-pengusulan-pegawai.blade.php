<div class="box-header with-border text-center">
    <h3 class="box-title">Pilih Pegawai</h3>
    <br>
    Kuota Pengusulan : <span class="badge badge-primary">{{ $kuota }}</span>
    {{-- Alert Max Kuota --}}
    <div class="w-100 mt-4 d-flex justify-content-center" id="alertDiv">

    </div>
</div>
<form action="{{ route('biro-sdm.pengusulan.updateOrCreate') }}" method="POST">
    @csrf
    <input id="checked_ids" type="text" name="checked_ids" value="{{$checked_ids}}">
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
                        <td>
                            <p class="p-0 m-0 text-decoration-none text-primary text-bold" style="cursor: pointer;" onmouseover="this.classList.add('text-info', 'text-decoration-underline');" onmouseout="this.classList.remove('text-info', 'text-decoration-underline');" data-id="{{ $employee->id }}" id="detailPegawai">{{ $employee->nama }}</p>
                            <span class="badge @if($employee->tipe == 'PNS') badge-success @else badge-warning @endif">
                                {{$employee->tipe}}
                            </span>
                        </td>
                        <td>{{ $employee->nip }}</td>
                        <td>{{ $employee->jabatan->nama_jabatan }} - {{ $employee->jabatan->golongan }}</td>
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
        let kuota = parseInt('{{ $kuota }}');
        let checkedIdsInput = $('#checked_ids');
        let checkedIds = checkedIdsInput.val().split(',').filter(id => id);

        $('input[name="id_pegawai[]"]').on('change', function() {
            let value = $(this).val();
            if ($(this).is(':checked')) {
                if (checkedIds.length < kuota) {
                    checkedIds.push(value);
                } else {
                    // Show id AlertKuota
                    $('#alertDiv').html('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><span><i class="icon fa fa-ban"></i> Jumlah usulan tidak boleh melebihi kuota !</span></div>');
                    $(this).prop('checked', false);
                    // Make the page scroll to the alertDiv
                    $('html, body').animate({
                        scrollTop: $('#formPegawai').offset().top
                    }, 300);
                }
            } else {
                checkedIds = checkedIds.filter(id => id !== value);
            }
            checkedIdsInput.val(checkedIds.join(','));
        });

        $('#employeeTable').DataTable();

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
