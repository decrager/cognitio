<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Surat Permohonan Diklat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
        }
        .header {
            text-align: center;
            font-weight: bold;
        }
        .content {
            margin-top: 20px;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <p>{{$penyelenggara}}<br>
        PEMERINTAH KOTA BANDUNG<br>
        PROVINSI JAWA BARAT</p>
        <p>Jl. Delima Jingga Raya no. 337-339 Telp. 044-33655, 33677 Fax. 044-33666</p>
        <p>http://www.pariwisatakotabandung.gov Email: askadmin@pariwisatakotabandung.gov</p>
        <hr>
    </div>
    <div class="content">
        <p><strong>Nomor</strong> : 031/SPDP/DPKB/VIII/2016</p>
        <p><strong>Perihal</strong> : Permohonan Diklat</p>
        <p><strong>Lampiran</strong> : 1 Lembar</p>
        <br>
        <p>Kepada</p>
        <p>{{$nama_jabatan}}<br>
        Badan Kepegawaian Daerah<br>
        {{$lokasi}}<br>
        Kabupaten Bandung</p>
        <br>
        <p>Dengan hormat,</p>
        <p>Dalam rangka meningkatkan sumber daya manusia (SDM) dan untuk menambah pengetahuan dan kualitas para pegawai negeri sipil yang baru diangkat di lingkungan Dinas Pariwisata Kota Bandung ini, dengan ini kami bermaksud untuk mengirimkan dan mendaftarkan 30 orang pegawai kami untuk mengikuti kegiatan {{$nama_pelatihan}} di {{$lokasi}} ini mulai tanggal {{$tanggal_mulai}} – {{$tanggal_selesai}}.</p>
        <p>Kami harap semoga permohonan ini dapat diterima dan adanya konfirmasi secepatnya untuk melangsungkan kegiatan tersebut. Demikian surat permohonan ini kami sampaikan, atas perhatian dan kerjasama Bapak/Ibu kami ucapkan terimakasih.</p>
    </div>
    <div class="footer">
        <p>Bandung, 17 Agustus 2016</p>
        <p><strong>{{$nama_jabatan}}</strong></p>
        <p>PEMERINTAH KOTA BANDUNG<br>
        PROVINSI JAWA BARAT</p>
    </div>
</body>
</html>
