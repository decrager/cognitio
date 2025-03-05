<!DOCTYPE html>
<html>
<head>
    <title>Program Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 2px;
        }
        .mb-2 {
            margin-bottom: 0.5rem;
        }
        .m-0 {
            margin: 0;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            border-collapse: collapse;
            font-size: 14px;
        }
        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border: 1px solid #dee2e6; /* Add border to table cells */
        }
        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6; /* Add border to table header */
        }

    </style>
</head>
<body>
<div class="container">
    <div class="row mb-2">
        {{-- Detail Program --}}
        <div style="text-align: center; margin-bottom: 20px;">
            <h2>{{$program->nama_pelatihan}}</h2>
        </div>
        <div>
            {{$program->deskripsi}}
            <table>
                <tr>
                    <td>
                        <strong>Tanggal Pelaksanaan:</strong> {{ \Carbon\Carbon::parse($program->tanggal_mulai)->locale('id')->isoFormat('D MMMM YYYY') }} - {{ \Carbon\Carbon::parse($program->tanggal_selesai)->locale('id')->isoFormat('D MMMM YYYY') }}
                    </td>
                    <td style="padding-left: 15px">
                        <strong>Kuota:</strong> {{$program->kuota}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Penyelenggara:</strong> {{$program->penyelenggara}}
                    </td>
                    <td style="padding-left: 15px">
                        <strong>Lokasi:</strong> {{$program->lokasi}}
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row" style="margin-top: 20px">
        <table class="table">
            <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Pegawai</th>
                <th width="60%">Unit Kerja</th>
                <th width="30%">Jabatan</th>
            </tr>
            </thead>
            <tbody>
            @if(count($pegawai) > 0)
                @foreach($pegawai as $key => $val)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td><strong>{{ $val->nama }}</strong><br>{{ $val->nip }}<br>{{ $val->nik }}
                        </td>
                        <td>{{ $val->nama_unit }}</td>
                        <td>{{ $val->nama_jabatan }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</body>
</html>
