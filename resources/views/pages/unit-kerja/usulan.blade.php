@extends('layouts.app')

@section('title', 'Usulan Program Pelatihan')

@section('content')
    <div class="card">
        <div class="card-header">
                <h4 class="card-title text-bold">
                    <i class="fa fa-info mr-2"></i> Usulan Pelatihan
                </h4>
            </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        @if(count($assignment_usulan) > 0)
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Action</th>
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
                                @foreach($assignment_usulan as $item2)
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
                                                <form id="confirm-form-{{ $item2->id }}" action="{{ route('update_status_assignment.unit-kerja', $item2->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="status" value="2">
                                                    <button type="button" class="btn btn-success btn-sm" onclick="confirmAction({{ $item2->id }}, 'Konfirmasi', 2)">Konfirmasi</button>
                                                </form>

                                                <form id="reject-form-{{ $item2->id }}" action="{{ route('update_status_assignment.unit-kerja', $item2->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <input type="hidden" name="status" value="3">
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmAction({{ $item2->id }}, 'Tolak', 3)">Tolak</button>
                                                </form>
                                            </td>
                                            <td>
                                                {{ $item2->pegawai->tipe ?? '-' }}
                                                {{ $item2->pegawai->nama ?? '-' }}
                                                {{ $item2->pegawai->nip ?? '-' }}
                                                {{ $item2->pegawai->telepon ?? '-' }}
                                            </td>
                                            <!-- <td>{{ $item2->id_program ?? '-' }}</td> -->
                                            <td><b>{{ $item2->Program->nama_pelatihan ?? '-' }}</b></td>
                                            <td style="{{ $warna_status }}">
                                                {{ $status_text }}
                                                <br>
                                                <button class="btn btn-info btn-sm mt-1" onclick="showDetail({{ $item2->id_program }})">
                                                    Detail
                                                </button>
                                            </td>
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
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
                <h4 class="card-title text-bold">
                    <i class="fa fa-info mr-2"></i> Pelatihan
                </h4>
            </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        @if(count($assignment_non_usulan) > 0)
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <!-- <th>Action</th> -->
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
                                @foreach($assignment_non_usulan as $item3)
                                @php
                                    $status = (int) $item3->status; // Konversi ke integer

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
                                            <!-- <td>tes</td> -->
                                            <td>
                                                {{ $item3->pegawai->tipe ?? '-' }}
                                                {{ $item3->pegawai->nama ?? '-' }}
                                                {{ $item3->pegawai->nip ?? '-' }}
                                                {{ $item3->pegawai->telepon ?? '-' }}
                                            </td>
                                            <!-- <td>{{ $item3->id_program ?? '-' }}</td> -->
                                            <td><b>{{ $item3->Program->nama_pelatihan ?? '-' }}</b></td>
                                            <th style="{{ $warna_status }}">{{ $status_text }}</td>
                                            <td>{{ $item3->Program->deskripsi ?? '-' }}</td>
                                            <td>{{ $item3->Program->tanggal_mulai ?? '-' }}</td>
                                            <td>{{ $item3->Program->tanggal_selesai ?? '-' }}</td>
                                            <td>{{ $item3->Program->lokasi ?? '-' }}</td>
                                            <td>{{ $item3->Program->penyelenggara ?? '-' }}</td>
                                            <td>{{ $item3->assigned_by_name ?? '-' }}</td>
                                        </tr>
                                    </tbody>
                                @endforeach
                            </table>
                        @else
                            <p>Belum ada data Assignment Program / Pelatihan.</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDetailProgram" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailLabel">Detail Program</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Nama Pelatihan</th>
                            <td id="detail_nama_pelatihan"></td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td id="detail_deskripsi"></td>
                        </tr>
                        <tr>
                            <th>Tanggal Mulai</th>
                            <td id="detail_tanggal_mulai"></td>
                        </tr>
                        <tr>
                            <th>Tanggal Selesai</th>
                            <td id="detail_tanggal_selesai"></td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td id="detail_lokasi"></td>
                        </tr>
                        <tr>
                            <th>Penyelenggara</th>
                            <td id="detail_penyelenggara"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function confirmAction(id, action, status) {
            let confirmation = confirm(`Apakah Anda yakin ingin ${action} pelatihan ini?`);
            if (confirmation) {
                document.getElementById(`${status === 2 ? 'confirm-form' : 'reject-form'}-${id}`).submit();
            }
        }
    </script>

<script>
    function showDetail(id_program) {
    $.ajax({
        url: "{{ url('unit-kerja/getProgramDetail') }}/" + id_program,
        type: "GET",
        success: function(response) {
            if(response) {
                $("#detail_nama_pelatihan").text(response.nama_pelatihan || '-');
                $("#detail_deskripsi").text(response.deskripsi || '-');
                $("#detail_tanggal_mulai").text(response.tanggal_mulai || '-');
                $("#detail_tanggal_selesai").text(response.tanggal_selesai || '-');
                $("#detail_lokasi").text(response.lokasi || '-');
                $("#detail_penyelenggara").text(response.penyelenggara || '-');

                $("#modalDetailProgram").modal("show"); // Menampilkan modal
            } else {
                alert("Data tidak ditemukan!");
            }
        },
        error: function() {
            alert("Terjadi kesalahan dalam mengambil data.");
        }
    });
}

    </script>
@endsection
