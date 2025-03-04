@extends('layouts.app')

@section('title', 'Daftar Pegawai BPKP')

@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div id="test" class="col-md-12 ">
                    {{-- Filter --}}
                    <form class="d-flex align-items-center" action="{{ route('biro-sdm.pegawai.index') }}" method="GET">
                        <input class="form-control w-25" name="search" value="{{ old('search', $request->search) }}" placeholder="Nama/Jabatan/NIP/NIK/Telepon/Alamat">
                        <select name="tipe" style="width: 10%" class="form-control ml-2">
                            <option value="">Tipe Pegawai</option>
                            <option value="pns" {{ old('tipe', $request->tipe) == 'pns' ? 'selected' : '' }}>PNS</option>
                            <option value="pppk" {{ old('tipe', $request->tipe) == 'pppk' ? 'selected' : '' }}>PPPK</option>
                        </select>
                        <button type="submit" class="btn btn-primary ml-2">
                           <i class="fa fa-search mr-1"></i> Cari
                        </button>
                        <button type="button" onclick="resetForm()" class="btn btn-danger ml-2">
                            <i class="fa fa-refresh mr-1"></i> Reset
                        </button>
                    </form>
                    {{-- End Filter --}}
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Jabatan</th>
                                <th scope="col">NIP</th>
                                <th scope="col">NIK</th>
                                <th scope="col">Telepon</th>
                                <th scope="col">Tanggal Lahir</th>
                                <th scope="col">Jenis Kelamin</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Usulkan Pelatihan</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results as $val)
                                <tr>
                                    <td>{{ ($results->currentPage() - 1) * $results->perPage() + $loop->iteration }}</td>
                                    <td>
                                        <x-employee-name :id="$val->id" :name="$val->nama" />
                                        <span class="badge
                                            @if($val->tipe == 'PNS')
                                            badge-success
                                            @else
                                            badge-warning
                                             @endif">
                                            {{$val->tipe}}
                                        </span>
                                    </td>
                                    <td>{{ $val->tipe_jabatan }}<br><span class="text-info">{{$val->nama_jabatan}}</span></td>
                                    <td>{{ $val->nip }}</td>
                                    <td>{{ $val->nik }}</td>
                                    <td>{{ $val->telepon }}</td>
                                    <td>{{ $val->tanggal_lahir }}</td>
                                    <td>{{ $val->jenis_kelamin }}</td>
                                    <td>{{ $val->alamat }}</td>
                                    <td>
                                        @if($val->status == 2)
                                            <button class="btn btn-success btn-sm" disabled>Konfirmasi</button>
                                        @elseif($val->status == 3)
                                            <button class="btn btn-danger btn-sm" disabled>Ditolak</button>
                                        @else
                                            <button class="btn btn-primary btn-sm" onclick="openModal({{ $val->id }})">Pilih</button>
                                        @endif
                                    </td>


                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    {{ $results->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </ul>
                            </nav>
                        </div>
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
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="pegawaiModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Pilih Program untuk Pelatihan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="pegawaiForm">
                        <div class="mb-3">
                            <label for="pegawaiSelect" class="form-label">Pilih Program Pelatihan</label>
                            <select class="form-select select2" id="pegawaiSelect" name="program_pelatihan" style="width: 100%">
                                <option value="">-- Pilih Program Pelatihan --</option>
                                @foreach($programList as $program)
                                    <option value="{{ $program->id }}"
                                            data-mulai="{{ $program->tanggal_mulai }}"
                                            data-selesai="{{ $program->tanggal_selesai }}"
                                            data-kuota="{{ $program->kuota }}"
                                            data-lokasi="{{ $program->lokasi }}"
                                            data-penyelenggara="{{ $program->penyelenggara }}"
                                            data-deskripsi="{{ $program->deskripsi }}">
                                        {{ $program->nama_pelatihan }} || Kuota: {{ $program->kuota }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="text" class="form-control" id="tanggal_mulai" name="tanggal_mulai" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="text" class="form-control" id="tanggal_selesai" name="tanggal_selesai" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kuota" class="form-label">Kuota</label>
                                <input type="text" class="form-control" id="kuota" name="kuota" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <input type="text" class="form-control" id="lokasi" name="lokasi" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="penyelenggara" class="form-label">Penyelenggara</label>
                                <input type="text" class="form-control" id="penyelenggara" name="penyelenggara" disabled>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" disabled></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success">Usulkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')

    <script>

        function resetForm() {
            const form = document.querySelector('form[action="{{ route('biro-sdm.pegawai.index') }}"]');
            form.querySelector('input[name="search"]').value = '';
            form.querySelector('select[name="tipe"]').value = '';
            form.submit();
        }

        function loadDetailModal(id) {
            var modal = $('#detailModal');
            modal.modal('show');
            $('#modal-loader').show(); // Show the loader

            $.ajax({
                url: '{{ route('biro-sdm.pegawai.show', '') }}/' + id,
                method: 'GET',
                success: function (data) {
                    $('#modal-loader').hide(); // Hide the loader
                    modal.find('.modal-content').html(data);
                },
                error: function () {
                    $('#modal-loader').hide(); // Hide the loader
                    alert('Error');
                }
            });
        }

        $(document).ready(function() {

            $('#pegawaiSelect').select2({
                theme: 'bootstrap4',
                width: '100%',
                dropdownParent: $('#pegawaiModal')
            });


            $('#pegawaiSelect').on('change', function() {
                var selectedOption = $(this).find(':selected');

                if (selectedOption.val() !== "") {
                    $('#tanggal_mulai').val(selectedOption.data('mulai')).prop('disabled', true);
                    $('#tanggal_selesai').val(selectedOption.data('selesai')).prop('disabled', true);
                    $('#kuota').val(selectedOption.data('kuota')).prop('disabled', true);
                    $('#lokasi').val(selectedOption.data('lokasi')).prop('disabled', true);
                    $('#penyelenggara').val(selectedOption.data('penyelenggara')).prop('disabled', true);
                    $('#deskripsi').val(selectedOption.data('deskripsi')).prop('disabled', true);
                } else {

                    $('#pegawaiForm input, #pegawaiForm textarea').val('').prop('disabled', true);
                }
            });

            $('#pegawaiModal').on('hidden.bs.modal', function() {
                $('#pegawaiSelect').val('').trigger('change');
                $('#pegawaiForm input, #pegawaiForm textarea').val('').prop('disabled', true);
            });
        });

    </script>
     <script>
        $(document).ready(function() {

            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            $('#pegawaiModal').on('shown.bs.modal', function() {
                $('#pegawaiSelect').select2({
                    theme: 'bootstrap4',
                    width: '100%',
                    dropdownParent: $('#pegawaiModal')
                });
            });
        });

        function openModal(id) {
            $('#pegawaiModal').modal('show');
        }
    </script>
@endsection
