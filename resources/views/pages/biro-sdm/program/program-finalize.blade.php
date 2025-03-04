@extends('layouts.app')

@section('title', 'Penetapan Program')

@section('content')
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {{-- Detail Program --}}
                    <h5 class="text-bold">
                        <i class="fa fa-book mr-2"></i> {{$program->nama_pelatihan}}
                    </h5>
                    <p class="text-muted" style="font-size: 14px;">
                        {{$program->deskripsi}}
                    </p>

                    <div class="row">
                        <div class="col-md-6">
                            <p class="m-0 text-secondary"><i class="fa fa-calendar mr-2"></i>Tanggal Pelaksanaan</p>
                            <p style="font-size: 18px;">
                                {{ \Carbon\Carbon::parse($program->tanggal_mulai)->locale('id')->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::parse($program->tanggal_selesai)->locale('id')->isoFormat('D MMMM YYYY') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="m-0 text-secondary"> <i class="fa fa-users mr-2"></i>Kuota</p>
                            <p  style="font-size: 18px;">
                                {{$program->kuota}}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="m-0 text-secondary"><i class="fa fa-building mr-2"></i>Penyelanggara</p>
                            <p style="font-size: 18px;">
                                {{$program->penyelenggara}}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="m-0 text-secondary"><i class="fa fa-map-marker mr-2"></i>Lokasi</p>
                            <p  style="font-size: 18px;">
                                {{$program->lokasi}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <x-alert />
                    {{-- Filter --}}
                    <form class="d-flex align-items-center mb-2" action="{{ route('biro-sdm.program.finalization', ['id_program' => $program->id]) }}" method="GET">
                        <input class="form-control w-25" name="search" value="{{ old('search', $request->search) }}" placeholder="Nama/Jabatan/Unit Kerja">
                        <button type="submit" class="btn btn-primary ml-2">
                            <i class="fa fa-search mr-1"></i> Cari
                        </button>
                        <button type="button" onclick="resetForm()" class="btn btn-danger ml-2">
                            <i class="fa fa-refresh mr-1"></i> Reset
                        </button>
                        <a href="{{ route('biro-sdm.program.print-finalization', ['id_program' => $program->id]) }}" class="btn btn-success ml-auto">
                            <i class="fa fa-print mr-2"></i> Cetak
                        </a>
                    </form>
                    {{-- End Filter --}}
                    <div class="table-responsive">
                        <table id="pegawaiTable" class="table table-bordered">
                            <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Pegawai</th>
                                <th scope="col">Unit Kerja</th>
                                <th scope="col">Jabatan</th>
                                <th width="10%" scope="col">Status</th>
                                <th width="15%" scope="col">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                                @if(count($pegawai) > 0)
                                    @foreach($pegawai as $key => $val)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <x-employee-name :id="$val->id" :name="$val->nama" />
                                            </td>
                                            <td>{{ $val->nama_unit }}</td>
                                            <td>{{ $val->nama_jabatan }}</td>
                                            <td>
                                                <x-status-badge :status="$val->status" />
                                            </td>
                                            <td>
                                                @if($val->status == 2)
                                                    <form id="status-form-{{ $val->id }}" action="{{ route('biro-sdm.program.update-assignment-final', $val->id_assignment) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" id="status-input-{{ $val->id }}">
                                                    </form>
                                                    <button class="btn btn-success btn-sm" onclick="submitForm({{ $val->id }}, 2)"><i class="fa fa-check mr-2"></i>Tetapkan</button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    {{ $pegawai->appends(request()->query())->links('pagination::bootstrap-4') }}
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
@endsection

@push('scripts')
    <script>
        function submitForm(id, status) {
            $('#status-input-' + id).val(status);
            $('#status-form-' + id).submit();
        }

        function resetForm() {
            const form = document.querySelector('form[action="{{ route('biro-sdm.program.finalization', ['id_program' => $program->id]) }}"]');
            form.querySelector('input[name="search"]').value = '';
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
    </script>
@endpush
