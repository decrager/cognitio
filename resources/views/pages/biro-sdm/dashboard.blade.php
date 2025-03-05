@extends('layouts.app')

@section('title', 'Dashboard Biro SDM')

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-4 col-md-12 col-xs-12 d-flex align-items-stretch">
        <!-- small box -->
        <div class="small-box bg-blue flex-fill">
            <div class="inner">
                <h3>{{$jumlah_program}}</h3>

                <h5>Total Program / Pelatihan</h5>
            </div>
            <div class="icon">
                <i class="ion ion-laptop"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-xs-12 d-flex align-items-stretch">
        <!-- small box -->
        <div class="small-box bg-info flex-fill">
            <div class="inner text-white">
                <h3>{{ $jumlah_pegawai }}</h3>
                <h5>Total Pegawai</h5>
            </div>
            <div class="icon">
                <i class="ion ion-ios-people"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-12 col-xs-12 d-flex align-items-stretch">
        <!-- small box -->
        <div class="small-box bg-success flex-fill">
            <div class="inner text-white">
                <h3>{{$total_unit_kerja }}</h3>
                <h5>Total Unit Kerja</h5>
            </div>
            <div class="icon">
                <i class="fa fa-building"></i>
            </div>
        </div>
    </div>
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

                <p>Pelatihan TerKonfirmasi</p>
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

                <p>Pelatihan Ditetapkan</p>
            </div>
            <div class="icon">
                <i class="ion ion-thumbsup"></i>
            </div>
        </div>
    </div>
    <div class="col-md-5 col-sm-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-bold mb-0">Program Pelatihan</h4>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Program</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($program as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->nama_pelatihan }}</td>
                            <td>{{ $item->tanggal_mulai }}</td>
                            <td>{{ $item->tanggal_selesai }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer d-flex justify-content-center">
                <a href="{{ route('biro-sdm.program.index') }}" class="btn btn-primary">Lihat Semua Program</a>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <div class="col-md-7 col-sm-12">
        <div class="card ">
            <div class="card-header">
                <h4 class="card-title mb-0 text-bold">Data Pegawai</h4>
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pegawai</th>
                        <th>NIP</th>
                        <th>NIK</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($pegawai as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->nama }} |
                                <span class="text-secondary text-sm">{{$item->nama_jabatan}}</span>
                            </td>
                            <td>{{ $item->nip }}</td>
                            <td>{{ $item->nik }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex justify-content-center">
                <a href="{{ route('biro-sdm.pegawai.index') }}" class="btn btn-primary">Lihat Semua Pegawai</a>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->
@endsection
