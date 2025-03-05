<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="formCreate" action="{{ route('biro-sdm.program.create') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title" id="detailModalLabel">Tambah Program</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body-content">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th scope="row" style="width: 250px;">Nama Program Pelatihan</th>
                                    <td><input type="text" name="nama_pelatihan" class="form-control" placeholder="Nama Program Pelatihan..." required><br></td>
                                </tr>
                                <tr>
                                    <th scope="row" style="width: 250px;">Deskripsi</th>
                                    <td><textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi..." required></textarea><br></td>
                                </tr>
                                <tr>
                                    <th scope="row" style="width: 250px;">Tanggal Mulai</th>
                                    <td><input type="date" name="tanggal_mulai" class="form-control" required></td>
                                </tr>
                                <tr>
                                    <th scope="row" style="width: 250px;">Tanggal Selesai</th>
                                    <td><input type="date" name="tanggal_selesai" class="form-control" required></td>
                                </tr>
                                <tr>
                                    <th scope="row" style="width: 250px;">Lokasi</th>
                                    <td><textarea name="lokasi" class="form-control" rows="3" placeholder="Lokasi..." required></textarea><br></td>
                                </tr>
                                <tr>
                                    <th scope="row" style="width: 250px;">Kuota</th>
                                    <td><input type="number" name="kuota" class="form-control" placeholder="Kuota..." min="1" required ></td>
                                </tr>
                                <tr>
                                    <th scope="row" style="width: 250px;">Penyelenggara</th>
                                    <td><input type="penyelenggara" name="penyelenggara" class="form-control" placeholder="Penyelenggara" required><br></td>
                                </tr>
                                <tr>
                                    <th scope="row" style="width: 250px;">Kriteria Jabatan</th>
                                    <td>
                                        <select class="form-control select2" name="jabatan[]" multiple="multiple" data-placeholder="Pilih Jabatan" style="width: 100%; background: blue;" required>
                                            @foreach ($jabatan as $row)
                                                <option value="{{ $row->id }}">{{ $row->nama_jabatan }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()
        })

        function createProgram() {
            $('#formCreate').submit();
        }
    </script>
@endpush