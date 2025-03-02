@extends('layouts.app')

@section('title', 'Dashboard Pegawai')

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $jumlah_kpi_cukup }}</h3>

                <p>Kompentensi Pegawai</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">Cukup <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $jumlah_kpi_kurang }}<sup style="font-size: 20px"></sup></h3>

                <p>Kompetensi Pegawai</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">Kurang <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <!-- <div class="col-lg-3 col-xs-6"> -->
        <!-- small box -->
        <!-- div class="small-box bg-yellow">
            <div class="inner">
                <h3>44</h3>

                <p>User Registrations</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div> -->
    <!-- ./col -->
    <!-- <div class="col-lg-3 col-xs-6"> -->
        <!-- small box -->
        <!-- <div class="small-box bg-red">
            <div class="inner">
                <h3>65</h3>

                <p>Unique Visitors</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div> -->
    <!-- ./col -->
</div>
<!-- /.row -->

<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-orange">
            <div class="inner">
                <h3>{{ $jumlah_assignment_usulan }}</h3>

                <p>Usulan Pelatihan</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">Cukup <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-blue">
            <div class="inner">
                <h3>{{ $jumlah_assignment_konfirmasi }}<sup style="font-size: 20px"></sup></h3>

                <p>Konfirmasi Pelatihan</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">Kurang <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $jumlah_assignment_tidak_dikonfirmasi }}</h3>

                <p>Tidak dikonfirmasi Pelatihan</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $jumlah_assignment_ditetapkan }}</h3>

                <p>Ditetapkan Pelatihan</p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row -->

<div class="row">
    
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Profil Pegawai</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <td>ID User</td>
                            <td>{{ $pegawai->id ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>ID Jabatan</td>
                            <td>{{ $pegawai->jabatan->nama_jabatan ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>ID Unit</td>
                            <td>{{ $pegawai->unit->nama_unit ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>{{ $pegawai->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>NIP</td>
                            <td>{{ $pegawai->nip ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>{{ $pegawai->nik ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td>{{ $pegawai->telepon ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Lahir</td>
                            <td>{{ $pegawai->tanggal_lahir ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jenis Kelamin</td>
                            <td>{{ $pegawai->jenis_kelamin ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>{{ $pegawai->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Tipe</td>
                            <td>{{ $pegawai->tipe ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <div class="col-md-9">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kompetensi Pegawai</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                @if(count($kompetensi) > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Pegawai</th>
                                <th>ID Jabatan</th>
                                <th>Nama Kompetensi</th>
                                <th>KPI Standar</th>
                                <th>KPI Pegawai</th>
                            </tr>
                        </thead>
                        @foreach($kompetensi as $item)
                        @php
                            $kpi_pegawai = $item->kpi ?? 0;
                            $kpi_standar = $item->standarKompetensi->kpi_standar ?? 0;
                            $warna_kpi = $kpi_pegawai >= $kpi_standar ? 'color:green;font-weight:bold;' : 'color:red;font-weight:bold;';
                        @endphp
                            <tbody>
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->id_pegawai ?? '-' }}</td>
                                    <td>{{ $item->standarKompetensi->jabatan->nama_jabatan ?? '-' }}</td>
                                    <td>{{ $item->standarKompetensi->nama_kompetensi ?? 'Tidak Ada' }}</td>
                                    <td>{{ $item->standarKompetensi->kpi_standar ?? '-' }}</td>
                                    <td style="{{ $warna_kpi }}">{{ $kpi_pegawai }}</td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                @else
                    <p>Belum ada data kompetensi.</p>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>


    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Jadwal Pelatihan</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if(count($kompetensi) > 0)
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Pegawai</th>
                                <th>status</th>
                                <th>ID Program</th>
                                <th>Nama Pelatihan</th>
                                <th>Deskripsi</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Kuota</th>
                                <th>Lokasi</th>
                                <th>Penyelenggara</th>
                                <th>Assigned By ID</th>
                                <th>Assigned By Name</th>
                            </tr>
                        </thead>
                        @foreach($assignment as $item2)
                        @php
                            $status = (int) $item2->status; // Konversi ke integer

                            $warna_status = match($status) {
                                1 => 'color:orange;font-weight:bold;', // Usulan (kuning)
                                2 => 'color:blue;font-weight:bold;',   // Konfirmasi (biru)
                                3 => 'color:red;font-weight:bold;',    // Tidak Dikonfirmasi (merah)
                                4 => 'color:green;font-weight:bold;',  // Ditetapkan (hijau)
                                default => 'color:black;',             // Default (hitam)
                            };

                            $status_text = match($status) {
                                1 => 'Usulan',
                                2 => 'Konfirmasi',
                                3 => 'Tidak Dikonfirmasi',
                                4 => 'Ditetapkan',
                                default => 'Tidak Diketahui',
                            };
                        @endphp
                            <tbody>
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item2->id_pegawai ?? '-' }}</td>
                                    <th style="{{ $warna_status }}">{{ $status_text }}</td>
                                    <td>{{ $item2->id_program ?? '-' }}</td>
                                    <td>{{ $item2->Program->nama_pelatihan ?? '-' }}</td>
                                    <td>{{ $item2->Program->deskripsi ?? '-' }}</td>
                                    <td>{{ $item2->Program->tanggal_mulai ?? '-' }}</td>
                                    <td>{{ $item2->Program->tanggal_selesai ?? '-' }}</td>
                                    <td>{{ $item2->Program->kuota ?? '-' }}</td>
                                    <td>{{ $item2->Program->lokasi ?? '-' }}</td>
                                    <td>{{ $item2->Program->penyelenggara ?? '-' }}</td>
                                    <td>{{ $item2->assigned_by_id ?? '-' }}</td>
                                    <td>{{ $item2->assigned_by_name ?? '-' }}</td>
                                </tr>
                            </tbody>
                        @endforeach
                    </table>
                @else
                    <p>Belum ada data Assignment Program / Pelatihan.</p>
                @endif
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
@endsection
