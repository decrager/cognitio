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
                            <table id="datatable_unit_kerja_1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th width="15%">Nama Pegawai</th>
                                        <th>Nama Pelatihan</th>
                                        <th>Status</th>
                                        <th>Deskripsi</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Lokasi</th>
                                        <th>Penyelenggara</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>

                                    <tbody>
                                    @foreach($assignment_usulan as $item2)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{-- <b>{{ $item2->pegawai->nama ?? '-' }}</b>
                                               <span class="badge badge-sm badge-info">
                                                   {{ $item2->pegawai->tipe ?? '-' }}</span>
                                                <br>
                                                <span class="text-muted">NIP: {{ $item2->pegawai->nip ?? '-' }}</span>
                                                <br>
                                                <span class="text-info">
                                                    NIK: {{ $item2->pegawai->nik ?? '-' }}
                                                </span> --}}
                                                <x-employee-name :id="$item2->pegawai->id" :name="$item2->pegawai->nama" />
                                            </td>
                                            <td>
                                                <b>
                                                    {{ $item2->Program->nama_pelatihan ?? '-' }}
                                                </b>
                                                <br>
                                                <button class="btn btn-info btn-sm mt-1" style="width: 35px !important;" onclick="showDetail({{ $item2->id_program }})">
                                                    <i class="fa fa-eye"></i>
                                                </button></td>
                                            <td>
                                                <x-status-badge :status="$item2->status" />
                                            </td>
                                            <td><b>{{ $item2->Program->nama_pelatihan ?? '-' }}</b></td>
                                            <td>{{ \Carbon\Carbon::parse($item2->Program->tanggal_mulai)->translatedFormat('j F Y') ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item2->Program->tanggal_selesai)->translatedFormat('j F Y') ?? '-' }}</td>
                                            <td>{{ $item2->Program->lokasi ?? '-' }}</td>
                                            <td>
                                                <strong>{{ $item2->Program->penyelenggara ?? '-' }}</strong><br>
                                                <span class="text-muted text-sm">Assigned by: <span class="text-info">{{ $item2->assigned_by_name ?? '-' }}</span></span>
                                            </td>
                                            <td>
                                                <div>
                                                    <?php $isDisabled = $item2->program->status != 'aktif' ? 'disabled' : '' ?>
                                                    <form id="status-form-{{ $item2->id }}" action="{{ route('update_status_assignment.unit-kerja', $item2->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="status" id="status-input-{{ $item2->id }}">
                                                        <button style="width: 35px !important;" type="button" class="btn btn-success btn-sm {{ $isDisabled }}" {{ $isDisabled }} onclick="submitForm({{ $item2->id }}, 2)">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                        <button style="width: 35px !important;" type="button" class="btn btn-danger btn-sm {{ $isDisabled }}" {{ $isDisabled }} onclick="submitForm({{ $item2->id }}, 3)">
                                                            <i class="fa fa-close"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                            </table>
                        @else
                            <p>Belum ada data Assignment Program / Pelatihan.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
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
                            <table id="datatable_unit_kerja_2" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th width="15%">Nama Pegawai</th>
                                            <th>Nama Pelatihan</th>
                                            <th>Status</th>
                                            <th>Deskripsi</th>
                                            <th>Tanggal Mulai</th>
                                            <th>Tanggal Selesai</th>
                                            <th>Lokasi</th>
                                            <th>Penyelenggara</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($assignment_non_usulan as $item3)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{-- <b>{{ $item3->pegawai->nama ?? '-' }}</b>
                                                <span class="badge badge-sm badge-info">
                                                   {{ $item3->pegawai->tipe ?? '-' }}
                                                </span>
                                                <br />
                                                <span class="text-muted">NIP: {{ $item3->pegawai->nip ?? '-' }}
                                                </span>
                                                <br />
                                                <span class="text-info">
                                                    NIK: {{ $item3->pegawai->nik ?? '-' }}
                                                </span> --}}
                                                <x-employee-name :id="$item3->pegawai->id" :name="$item3->pegawai->nama" />
                                            </td>
                                            <td>
                                                <b>{{ $item3->Program->nama_pelatihan ?? '-' }}</b>
                                                <br>
                                                <button class="btn btn-info btn-sm mt-1" style="width: 35px !important;" onclick="showDetail({{ $item3->id_program }})">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                            </td>
                                            <td><x-status-badge :status="$item3->status" /></td>
                                            <td>{{ $item3->Program->deskripsi ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item3->Program->tanggal_mulai)->translatedFormat('j F Y') ?? '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item3->Program->tanggal_selesai)->translatedFormat('j F Y') ?? '-' }}</td>
                                            <td>{{ $item3->Program->lokasi ?? '-' }}</td>
                                            <td>
                                                <strong>{{ $item3->Program->penyelenggara ?? '-' }}</strong><br>
                                                <span class="text-muted text-sm">Assigned by: <span class="text-info">{{ $item3->assigned_by_name ?? '-' }}</span></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                            </table>
                        @else
                            <p>Belum ada data Assignment Program / Pelatihan.</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div id="modal-loader" class="d-flex justify-content-center">
                        <div  class="spinner-border text-primary" role="status" >
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div id="section-content"></div>
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

        function loadDetailModal(id) {
            var modal = $('#detailModal');
            modal.modal('show');
            $('#modal-loader').show(); // Show the loader
            console.log($('#modal-loader'));

            $.ajax({
                url: '{{ route('biro-sdm.pegawai.show', '') }}/' + id,
                method: 'GET',
                success: function (data) {
                    $('#modal-loader').hide(); // Hide the loader
                    modal.find('#section-content').html(data);
                },
                error: function () {
                    $('#modal-loader').hide(); // Hide the loader
                    alert('Error');
                }
            });
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

<script>
        function submitForm(id, status) {
            let action = status === 2 ? 'Konfirmasi' : 'Tolak';
            let confirmation = confirm(`Apakah Anda yakin ingin ${action} pelatihan ini?`);
            if (confirmation) {
                document.getElementById(`status-input-${id}`).value = status;
                document.getElementById(`status-form-${id}`).submit();
            }
        }

        // Initiate Datatable
        $(document).ready(function() {
            $('#datatable_unit_kerja_1').DataTable();
            $('#datatable_unit_kerja_2').DataTable();
        });
    </script>
@endsection
