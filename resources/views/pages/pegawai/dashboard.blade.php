@extends('layouts.app')

@section('title', 'Dashboard Pegawai')

@section('content')
<div class="row">
    <div class="col-lg-4 col-md-12 col-xs-12 d-flex align-item-stretch">
        <div class="info-box flex-fill">
            <span class="info-box-icon bg-yellow mr-4 h-100 d-flex align-items-center justify-content-center" style="width: 150px"><i class="ion ion-ios-person-outline"></i></span>

            <div class="info-box-content ml-4">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <h3 class="text-bold">{{$pegawai->nama}}</h3>
                    <span class="info-box-text text-bold">{{$pegawai->jabatan->nama_jabatan}}</span>
                    <span class="info-box-text">NIP {{$pegawai->nip}}</span>
                    <span class="info-box-text">NIK {{$pegawai->nik}}</span>
                </div>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-xs-12 d-flex align-items-stretch">
        <!-- small box -->
        <div class="small-box bg-blue flex-fill">
            <div class="inner">
                <h3>{{ $jumlah_kpi_cukup }} / {{$jumlah_kpi_kurang}}</h3>

                <h5>Kompetensi Pegawai</h5>

                <div class="progress">
                    <div class="progress-bar bg-success" style="width: {{($jumlah_kpi_cukup  / $jumlah_kpi_kurang + $jumlah_kpi_cukup) * 100}}%"></div>
                </div>
            </div>
            <div class="icon">
                <i class="ion ion-laptop"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-xs-12 d-flex align-items-stretch">
        <!-- small box -->
        <div class="small-box bg-warning flex-fill">
            <div class="inner">
                <h3>{{ $jumlah_assignment_ditetapkan + $jumlah_assignment_konfirmasi + $jumlah_assignment_usulan + $jumlah_assignment_tidak_dikonfirmasi}}</h3>
                <h5>Total Pelatihan</h5>
            </div>
            <div class="icon">
                <i class="ion ion-folder"></i>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-orange">
            <div class="inner">
                <h3>{{ $jumlah_assignment_usulan }}</h3>

                <p>Pelatihan yang Di Usulkan</p>
            </div>
            <div class="icon">
                <i class="ion ion-information-circled"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-blue">
            <div class="inner">
                <h3>{{ $jumlah_assignment_konfirmasi }}<sup style="font-size: 20px"></sup></h3>

                <p>Pelatihan Pending Konfirmasi</p>
            </div>
            <div class="icon">
                <i class="ion ion-help-circled"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $jumlah_assignment_tidak_dikonfirmasi }}</h3>

                <p>Pelatihan Tidak Terkonfirmasi</p>
            </div>
            <div class="icon">
                <i class="ion ion-thumbsdown"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $jumlah_assignment_ditetapkan }}</h3>

                <p>Pelatihan Terkonfirmasi</p>
            </div>
            <div class="icon">
                <i class="ion ion-thumbsup"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->
</div>
<!-- /.row -->

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-bold">
                   <i class="fa fa-info mr-2"></i> Kompetensi Pegawai
                </h4>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                @if(count($kompetensi) > 0)
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
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

                            $class_bg = $kpi_pegawai < $kpi_standar  ? 'bg-warning' : '';

                        @endphp
                            <tbody>
                                <tr class="{{$class_bg}}">
                                    <td>{{ $loop->iteration }}</td>
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


    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-bold">
                    <i class="fa fa-info mr-2"></i> Jadwal Pelatihan
                </h4>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if(count($kompetensi) > 0)
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pelatihan</th>
                                <th>Status</th>
                                <th>Deskripsi</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Lokasi</th>
                                <th>Penyelenggara</th>
                                <th>Di Usulkan Oleh</th>
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
                                    <td>{{ $item2->Program->lokasi ?? '-' }}</td>
                                    <td>{{ $item2->Program->penyelenggara ?? '-' }}</td>
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
