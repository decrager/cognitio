@extends('layouts.app')

@section('title', 'Dashboard Unit Kerja')

@section('content')

<div class="row">
    <div class="col-xl-8 col-lg-12 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-building-o"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Unit Kerja</span>
                <span class="text-bold">{{$pegawai->unit->nama_unit}}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-dollar"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Anggaran</span>
                <h4 class="text-bold">Rp {{ number_format($pegawai->unit->anggaran, 0, ',', '.') }}</h4>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-orange"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Total Pegawai</span>
                <h4 class="text-bold">{{$total_pegawai_unit_kerja}}</h4>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-warning text-white"><i class="ion ion-location"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Lokasi</span>
                <h4 class="text-bold">{{$pegawai->unit->lokasi}}</h4>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-info text-white"><i class="fa fa-cog"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Status</span>
                <h4 class="text-bold">{{ucfirst($pegawai->unit->status)}}</h4>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>

<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-orange">
            <div class="inner">
                <h3>{{ $jumlah_assignment_usulan }}</h3>

                <p>Pegawai Diusulkan Pelatihan</p>
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

                <p>Pegawai Terkonfirmasi Pelatihan</p>
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

                <p>Pegawai Tidak Terkonfirmasi Pelatihan</p>
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

                <p>Pegawai Ditetapkan Pelatihan</p>
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
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-bold">
                    <i class="fa fa-info mr-2"></i> Usulan Pelatihan
                </h4>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if(count($assignment) > 0)
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pegawai</th>
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
                                    <td>
                                        <b>{{ $item2->pegawai->nama ?? '-' }}</b>
                                        <span class="badge badge-sm badge-info">
                                                   {{ $item2->pegawai->tipe ?? '-' }}</span>
                                        <br>
                                        <span class="text-muted">NIP: {{ $item2->pegawai->nip ?? '-' }}</span>
                                        <br>
                                        <span class="text-info">
                                                    NIK: {{ $item2->pegawai->nik ?? '-' }}
                                                </span>
                                    </td>
                                    <td><b>{{ $item2->Program->nama_pelatihan ?? '-' }}</b>
                                    </td>
                                    <td style="{{ $warna_status }}">{{ $status_text }}</td>
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
