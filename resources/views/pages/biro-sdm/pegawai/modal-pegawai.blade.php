{{--Modal Pegawai Detail Body--}}
<div class="modal-header d-flex align-items-center">
    <h5 class="modal-title" id="detailModalLabel">Detail Pegawai</h5>
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
                    <th scope="row">Nama</th>
                    <td>{{$data->nama}}<br>
                        <span class="badge
                            @if($data->tipe == 'PNS')
                                badge-success
                            @else
                                badge-warning
                            @endif">
                            {{$data->tipe}}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Jabatan</th>
                    <td>{{ $data->jabatan->nama_jabatan }}<br><span class="text-info">{{ $data->jabatan->tipe_jabatan }} - {{ $data->jabatan->golongan }}</span></td>
                </tr>
                <tr>
                    <th scope="row">NIP</th>
                    <td>{{$data->nip}}</td>
                </tr>
                <tr>
                    <th scope="row">NIK</th>
                    <td>{{$data->nik}}</td>
                </tr>
                <tr>
                    <th scope="row">Email</th>
                    <td>{{$data->user->email}}</td>
                </tr>
                <tr>
                    <th scope="row">Telepon</th>
                    <td>{{$data->telepon}}</td>
                </tr>
                <tr>
                    <th scope="row">Tanggal Lahir</th>
                    <td>{{ Carbon\Carbon::parse($data->tanggal_lahir)->translatedFormat('j F Y') }}</td>
                </tr>
                <tr>
                    <th scope="row">Jenis Kelamin</th>
                    <td>{{$data->jenis_kelamin}}</td>
                </tr>
                <tr>
                    <th scope="row">Alamat</th>
                    <td>{{$data->alamat}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <h5><i class="fa fa-info mr-2"></i>Standar Kompetensi</h5>
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Kompetensi</th>
                    <th scope="col">KPI</th>
                    <th scope="col" class="text-primary">Batas KPI</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data->kompetensi_pegawai as $key => $val)
                    <tr class="{{ $val->kpi < $val->kpi_standar ? 'bg-warning' : '' }}">
                        <td>{{$key + 1}}</td>
                        <td>{{$val->nama_kompetensi}}</td>
                        <td>{{$val->kpi}}</td>
                        <td>{{$val->kpi_standar}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
</div>
