@extends('layouts.app')

@section('title', 'Program Pelatihan')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div id="test" class="col-md-12 ">
                    {{-- Filter --}}
                    <form class="d-flex align-items-center" action="{{ route('biro-sdm.program.index') }}" method="GET">
                        <input class="form-control w-auto" name="search" value="{{ old('search', $request->search) }}" placeholder="Cari...">
                        <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $request->tanggal_mulai) }}" class="form-control ml-2" style="width: 10%">
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $request->tanggal_selesai) }}" class="form-control ml-2" style="width: 10%">
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
                                    <th scope="col">Program Pelatihan</th>
                                    <th scope="col">Deskripsi</th>
                                    <th scope="col">Tanggal Pelaksanaan</th>
                                    <th scope="col">Kuota</th>
                                    <th scope="col">Lokasi</th>
                                    <th scope="col">Penyelenggara</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $val)
                                    <tr>
                                        <td>{{ ($results->currentPage() - 1) * $results->perPage() + $loop->iteration }}</td>
                                        <td>
                                            <p class="p-0 m-0 text-decoration-none text-primary text-bold">{{ $val->nama_pelatihan }}</p>
                                            {{-- <p class="p-0 m-0 text-decoration-none text-primary text-bold"
                                                style="cursor: pointer;"
                                                onmouseover="this.classList.add('text-info', 'text-decoration-underline');"
                                                onmouseout="this.classList.remove('text-info', 'text-decoration-underline');"
                                                onclick="loadDetailModal({{ $val->id }})">{{ $val->nama_pelatihan }}</p> --}}
                                        </td>
                                        <td>{{ $val->deskripsi }}<br></td>
                                        <td>{{ \Carbon\Carbon::parse($val->tanggal_mulai)->translatedFormat('j F Y') }} - {{ \Carbon\Carbon::parse($val->tanggal_selesai)->translatedFormat('j F Y') }}</td>
                                        <td><span class="badge badge-info">{{ $val->kuota }}</span></td>
                                        <td>{{ $val->lokasi }}</td>
                                        <td>{{ $val->penyelenggara }}</td>
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
@endsection

@section('scripts')
    <script>

        function resetForm() {
            const form = document.querySelector('form[action="{{ route('biro-sdm.program.index') }}"]');
            form.querySelector('input[name="search"]').value = '';
            form.querySelector('input[name="tanggal_mulai"]').value = '';
            form.querySelector('input[name="tanggal_selesai"]').value = '';
            form.submit();
        }

    </script>
@endsection
