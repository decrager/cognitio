<div class="modal-header d-flex align-items-center">
    <h5 class="modal-title" id="detailModalLabel">Detail Program</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body" id="modal-body-content">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered">
                <tbody>
                <tr>
                    <th scope="row">Nama Program Pelatihan</th>
                    <td>{{ $data->nama_pelatihan }}<br></td>
                </tr>
                <tr>
                    <th scope="row">Deskripsi</th>
                    <td>{{ $data->deskripsi }}<br></td>
                </tr>
                <tr>
                    <th scope="row">Tanggal Mulai</th>
                    <td>{{ \Carbon\Carbon::parse($data->tanggal_mulai)->translatedFormat('j F Y') }}</td>
                </tr>
                <tr>
                    <th scope="row">Tanggal Selesai</th>
                    <td>{{ \Carbon\Carbon::parse($data->tanggal_selesai)->translatedFormat('j F Y') }}</td>
                </tr>
                <tr>
                    <th scope="row">Lokasi</th>
                    <td>{{ $data->lokasi }}</td>
                </tr>
                <tr>
                    <th scope="row">Jumlah Usulan</th>
                    <td><span class="badge badge-info" style="font-size: 14px;">{{ count($data->assignment) }}</span></td>
                </tr>
                <tr>
                    <th scope="row">Kuota</th>
                    <td><span class="badge badge-secondary" style="font-size: 14px;">{{ $data->kuota }}</span></td>
                </tr>
                <tr>
                    <th scope="row">Penyelenggara</th>
                    <td>{{ $data->penyelenggara }}</td>
                </tr>
                <tr>
                    <th scope="row">Status</th>
                    <td>
                        @if ($data->status == 'aktif')
                            <span class="badge badge-success">
                                Aktif
                            </span>
                        @else
                            <span class="badge badge-danger">
                                Non Aktif
                            </span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th scope="row">Kriteria Jabatan</th>
                    <th scope="row">
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
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
</div>