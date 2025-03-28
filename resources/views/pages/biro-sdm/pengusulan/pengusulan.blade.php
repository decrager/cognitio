@extends('layouts.app')

@section('title', 'Pengusulan Pegawai')

@section('content')
    <div class="card">
        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <span><i class="icon fa fa-ban"></i>{{ session('error') }}</span>
                </div>
            @elseif (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <span><i class="icon fa fa-check"></i>{{ session('success') }}</span>
                </div>
            @endif

            <div class="row mb-4">
                <div id="test" class="col-md-12 ">
                    {{-- Filter --}}
                    <form class="d-flex align-items-center" action="{{ route('biro-sdm.program.index') }}" method="GET">
                        <input class="form-control w-auto" name="search" value="{{ old('search', $request->search) }}"
                            placeholder="Cari...">
                        <input type="date" name="tanggal_mulai"
                            value="{{ old('tanggal_mulai', $request->tanggal_mulai) }}" class="form-control ml-2"
                            style="width: 10%">
                        <input type="date" name="tanggal_selesai"
                            value="{{ old('tanggal_selesai', $request->tanggal_selesai) }}" class="form-control ml-2"
                            style="width: 10%">
                        <button type="submit" class="btn btn-primary ml-2">
                            <i class="fa fa-search mr-1"></i> Cari
                        </button>
                        <button type="button" onclick="resetForm()" class="btn btn-danger ml-2">
                            <i class="fa fa-refresh mr-1"></i> Reset
                        </button>
                        <a type="button" href="{{ route('biro-sdm.pengusulan.form') }}" class="btn btn-success ml-auto">
                            <i class="fa fa-plus mr-1"></i> Tambah
                        </a>
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
                                    <th scope="col">Program Pelatihan</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Tanggal Pelaksanaan</th>
                                    <th scope="col">Jumlah Usulan</th>
                                    <th scope="col">Kuota</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $val)
                                    <tr>
                                        <td>{{ ($results->currentPage() - 1) * $results->perPage() + $loop->iteration }}</td>
                                        <td>
                                            <p class="p-0 m-0 text-decoration-none text-primary text-bold"
                                                style="cursor: pointer;"
                                                onmouseover="this.classList.add('text-info', 'text-decoration-underline');"
                                                onmouseout="this.classList.remove('text-info', 'text-decoration-underline');"
                                                onclick="loadDetailModal({{ $val->id }})">{{ $val->nama_pelatihan }}</p>
                                        </td>
                                        <td>{{ $val->deskripsi }}<br></td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($val->tanggal_mulai)->translatedFormat('j F Y') }} -
                                            {{ \Carbon\Carbon::parse($val->tanggal_selesai)->translatedFormat('j F Y') }}
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ count($val->assignment) }}</span>
                                        </td>
                                        <td><span class="badge badge-secondary">{{ $val->kuota }}</span></td>
                                        <td>
                                            @if ($val->status == 'aktif')
                                                <span class="badge badge-success">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="badge badge-danger">
                                                    Non Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info detailUsulan" data-id_program="{{ $val->id }}">
                                                <i class="fa fa-users"></i>
                                            </button>
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

    <div class="box box-info mt-5" id="formPegawai" style="display: none;">
        {{-- form-pegawai-usulan --}}
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
        $(document).ready(function () {
            $('.detailUsulan').on('click', function () {
                let id_program = $(this).data('id_program');
                $('#formPegawai').empty();
                $('#formPegawai').append(`<div class="box-body" style="height: 100px;" id="loadingPegawai"><div class="overlay"><i class="fa fa-refresh fa-spin"></i></div></div>`);
                $('#formPegawai').show();
                $('html, body').animate({
                    scrollTop: $("#formPegawai").offset().top
                }, 1000);

                $('#formPegawai').empty();
                $('#formPegawai').append(`<div class="box-body" style="height: 100px;" id="loadingPegawai"><div class="overlay"><i class="fa fa-refresh fa-spin"></i></div></div>`);
                $('#formPegawai').show();
    
                $.ajax({
                    url: '{{ route('biro-sdm.pengusulan.listPegawai') }}',
                    type: 'GET',
                    data: {
                        id_program: id_program,
                        assigned: true,
                    },
                    success: function (data) {
                        $('#formPegawai').empty();
                        $('#formPegawai').html(data);
                    }
                });
            });
        });
    </script>

    <script>
        function resetForm() {
            const form = document.querySelector('form[action="{{ route('biro-sdm.program.index') }}"]');
            form.querySelector('input[name="search"]').value = '';
            form.querySelector('input[name="tanggal_mulai"]').value = '';
            form.querySelector('input[name="tanggal_selesai"]').value = '';
            form.submit();
        }

        function loadDetailModal(id) {
            var modal = $('#detailModal');
            modal.modal('show');
            $('#modal-loader').show(); // Show the loader

            $.ajax({
                url: '{{ route('biro-sdm.pengusulan.show', '') }}/' + id,
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
