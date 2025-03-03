@extends('layouts.app')

@section('title', 'Dashboard Unit Kerja')

@section('content')

Nama Unit Kerja : {{$pegawai->unit->nama_unit}} <br>
Lokasi Unit Kerja : {{$pegawai->unit->lokasi}} <br>
Status Unit Kerja : {{$pegawai->unit->status}} <br>
Anggaran Unit Kerja : {{$pegawai->unit->anggaran}} <br> <br>

<!-- Small boxes (Stat box) -->


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

                <p>Pelatihan Terkonfirmasi</p>
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
    <!-- ./col -->
</div>
<!-- /.row -->

<div class="row">
    <div class="col-12 mt-4">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title text-bold">
                    <i class="fa fa-info mr-2"></i> Jadwal Pelatihan
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
                                        {{ $item2->pegawai->tipe ?? '-' }}
                                        {{ $item2->pegawai->nama ?? '-' }} 
                                        {{ $item2->pegawai->nip ?? '-' }} 
                                        {{ $item2->pegawai->telepon ?? '-' }}
                                    </td>
                                    <!-- <td>{{ $item2->id_program ?? '-' }}</td> -->
                                    <td><b>{{ $item2->Program->nama_pelatihan ?? '-' }}</b></td>
                                    <th style="{{ $warna_status }}">{{ $status_text }}</td>
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
