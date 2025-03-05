{{--Modal Pegawai Detail Body--}}
<div class="modal-header d-flex align-items-center">
    <h5 class="modal-title" id="detailModalLabel">Detail Program</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<form id="formUpdate" action="{{ route('biro-sdm.program.update', $data->id) }}" method="POST">
    @csrf
    <div class="modal-body" id="modal-body-content">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-bordered">
                    <tbody>
                    <tr>
                        <th scope="row">Nama Program Pelatihan</th>
                        <td id="input_1" style="display: none;">
                            <input value="{{ $data->nama_pelatihan }}" type="text" name="nama_pelatihan" class="form-control" placeholder="Nama Program Pelatihan..." required><br>
                        </td>
                        <td id="read_1">{{ $data->nama_pelatihan }}<br></td>
                    </tr>
                    <tr>
                        <th scope="row">Deskripsi</th>
                        <td id="input_2" style="display: none;">
                            <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi..." required>{!! $data->deskripsi !!}</textarea><br>
                        </td>
                        <td id="read_2">{{ $data->deskripsi }}<br></td>
                    </tr>
                    <tr>
                        <th scope="row">Tanggal Mulai</th>
                        <td id="input_3" style="display: none;">
                            <input type="date" name="tanggal_mulai" class="form-control" value="{{ \Carbon\Carbon::parse($data->tanggal_mulai)->format('Y-m-d') }}" required>
                        </td>
                        <td id="read_3">{{ \Carbon\Carbon::parse($data->tanggal_mulai)->translatedFormat('j F Y') }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Tanggal Selesai</th>
                        <td id="input_4" style="display: none;">
                            <input type="date" name="tanggal_selesai" class="form-control" value="{{ \Carbon\Carbon::parse($data->tanggal_selesai)->format('Y-m-d') }}" required>
                        </td>
                        <td id="read_4">{{ \Carbon\Carbon::parse($data->tanggal_selesai)->translatedFormat('j F Y') }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Lokasi</th>
                        <td id="input_5" style="display: none;">
                            <textarea name="lokasi" class="form-control" rows="3" placeholder="Lokasi..." required>{!! $data->lokasi !!}</textarea><br>
                        </td>
                        <td id="read_5">{{ $data->lokasi }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Kuota</th>
                        <td id="input_6" style="display: none;">
                            <input type="number" name="kuota" class="form-control" placeholder="Kuota..." min="1" value="{{ $data->kuota }}" required>
                        </td>
                        <td id="read_6"><span class="badge badge-info" style="font-size: 14px;">{{ $data->kuota }}</span></td>
                    </tr>
                    <tr>
                        <th scope="row">Penyelenggara</th>
                        <td id="input_7" style="display: none;">
                            <input type="penyelenggara" name="penyelenggara" class="form-control" placeholder="Penyelenggara" value="{{ $data->penyelenggara }}" required><br>
                        </td>
                        <td id="read_7">{{ $data->penyelenggara }}</td>
                    </tr>
                    <tr>
                        <th scope="row">Kriteria Jabatan</th>
                        <td scope="row" id="input_8" style="display: none;">
                            <select class="form-control select2" name="jabatan[]" multiple="multiple" data-placeholder="Pilih Jabatan" style="width: 100%; background: blue;" required>
                                @foreach ($jabatan as $row)
                                    <?php $selected_jabatan = $kriteria->pluck('nama_jabatan')->toArray(); ?>
                                    <option value="{{ $row->id }}" @if(in_array($row->nama_jabatan, $selected_jabatan)) selected @endif>{{ $row->nama_jabatan }}</option>
                                @endforeach
                            </select>
                        </td>
                        <th scope="row" id="read_8">
                            <ol style="padding-left: 0; list-style-position: inside;">
                                @foreach ($kriteria as $jabatan)
                                    <li style="font-weight: normal;">{{ $jabatan->nama_jabatan }} - {{ $jabatan->golongan }}</li>
                                @endforeach
                            </ol>
                        </th>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger mr-auto" onclick="deleteProgram({{ $data->id }})">Hapus</button>
        <button id="input_9" type="submit" class="btn btn-success" style="display: none;">Simpan</button>
        <button id="read_9" type="button" class="btn btn-warning bold text-white" onclick="editProgram()">Ubah</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
    </div>
</form>

<form id="destroyProgram" action="{{ route('biro-sdm.program.delete', $data->id) }}" method="POST" style="display: none;">
    @csrf
</form>

<script>
    function deleteProgram(id) {
        if (confirm('Apakah Anda yakin ingin menghapus ini?')) {
            $('#destroyProgram').submit();
        }
    }

    function editProgram() {
        for (let i = 1; i <= 9; i++) {
            $('#read_' + i).hide();
            $('#input_' + i).show();
        }

        $('.select2').select2();
        console.log('Edit Program');
    }

    function updateProgram() {
        $('#formUpdate').submit();
    }
</script>