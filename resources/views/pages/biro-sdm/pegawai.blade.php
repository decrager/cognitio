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
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($results as $val)
                                <tr>
                                    <td>{{ ($results->currentPage() - 1) * $results->perPage() + $loop->iteration }}</td>
                                    <td>
                                      <p class="p-0 m-0 text-decoration-none text-primary text-bold" style="cursor: pointer;" onmouseover="this.classList.add('text-info', 'text-decoration-underline');" onmouseout="this.classList.remove('text-info', 'text-decoration-underline');" onclick="loadDetailModal({{$val->id}})">{{ $val->nama }}</p>
                                        <span class="badge
                                            @if($val->tipe == 'PNS')
                                            badge-success
                                            @else
                                            badge-warning
                                             @endif">
                                            {{$val->tipe}}
                                        </span>
                                    </td>
                                    <td>{{ $val->jabatan->tipe_jabatan }}<br><span class="text-info">{{$val->jabatan->nama_jabatan}}</span></td>
                                    <td>{{ $val->nip }}</td>
                                    <td>{{ $val->nik }}</td>
                                    <td>{{ $val->telepon }}</td>
                                    <td>{{ $val->tanggal_lahir }}</td>
                                    <td>{{ $val->jenis_kelamin }}</td>
                                    <td>{{ $val->alamat }}</td>
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
    </script>
@endsection
