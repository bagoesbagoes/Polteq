<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas - {{ $judulUsulan }}</title>
    <style>
        @page {
            margin: 2cm 2cm 2cm 2cm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }
        
        /* KOP SURAT */
        .kop-surat {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 20px;
            /* border-bottom: 3px solid #000; */
        }
        
        .kop-content {
            display: table;
            width: 100%;
        } 
        
        .kop-logo {
            display: table-cell;
            width: 100px;
            vertical-align: middle;
            padding-right: 15px;
        } */
        
        .kop-logo img {
            width: 700px; /* Sesuaikan ukuran yang pas menurutmu */
            height: auto;
            display: block;
            margin-left: auto;
            margin-right: auto; 
        }
        
        /* .kop-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 0 100px 0 0;
        }
        
        .kop-text h1 {
            margin: 0;
            padding: 0;
            font-size: 14pt;
            font-weight: bold;
            color: #4A90E2;
            letter-spacing: 1px;
        }
        
        .kop-text h2 {
            margin: 3px 0;
            padding: 0;
            font-size: 12pt;
            font-weight: bold;
            color: #000;
        }
        
        .kop-text p {
            margin: 2px 0;
            font-size: 9pt;
            line-height: 1.3;
        } */
        
        .kop-border {
            position: absolute;
            right: 0;
            top: 0;
            width: 8px;
            height: 100%;
            background: linear-gradient(to bottom, #E74C3C 0%, #E74C3C 50%, #C0392B 100%);
            border-radius: 2px;
        }
        
        /* JUDUL SURAT */
        .judul-surat {
            text-align: center;
            margin: 30px 0 20px 0;
        }
        
        .judul-surat h3 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
        }
        
        .judul-surat .nomor {
            margin-top: 5px;
            font-size: 11pt;
        }
        
        /* ISI SURAT */
        .isi-surat {
            text-align: justify;
            margin-bottom: 20px;
        }
        
        .isi-surat p {
            margin: 10px 0;
            text-indent: 50px;
        }
        
        .isi-surat p.no-indent {
            text-indent: 0;
        }
        
        /* TABEL TIM PENELITI */
        .tabel-tim {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .tabel-tim th,
        .tabel-tim td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 11pt;
        }
        
        .tabel-tim th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        .tabel-tim td:nth-child(1) {
            text-align: center;
            width: 30px;
        }
        
        .tabel-tim td:nth-child(2) {
            width: 35%;
        }
        
        .tabel-tim td:nth-child(3) {
            width: 25%;
            text-align: center;
        }
        
        .tabel-tim td:nth-child(4) {
            width: 30%;
            text-align: center;
        }
        
        /* HIGHLIGHT TEXT */
        .highlight-yellow {
            background-color: #FFFF00;
            padding: 2px 4px;
        }
        
        /* TTD */
        .ttd-section {
            margin-top: 40px;
            text-align: right;
        }

        .ttd-box {
            display: inline-block;
            text-align: left; /* ← UBAH dari center ke left */
            min-width: 200px;
        }

        .ttd-box .tempat-tanggal {
            margin-bottom: 0; /* ← UBAH dari 5px ke 0 */
        }

        .ttd-box .jabatan {
            font-weight: bold;
            margin-bottom: 70px; /* Tetap 70px untuk space TTD */
        }

        .ttd-box .nama {
            font-weight: bold;
            text-align: left; /* ← PASTIKAN rata kiri */
        }

        .ttd-box .nik {
            margin-top: 2px;
            font-size: 10pt;
            text-align: left; /* ← TAMBAHKAN ini */
        }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="kop-surat">
        <div class="kop-content">
            <div class="kop-logo">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="KopPolteq.png">
                @endif
            </div>
        </div>
        <div class="kop-border"></div>
    </div>

    {{-- JUDUL SURAT --}}
    <div class="judul-surat">
        <h3>SURAT TUGAS</h3>
        <div class="nomor">Nomor: {{ $nomorSurat }}</div>
    </div>

    {{-- ISI SURAT --}}
    <div class="isi-surat">
        <p class="no-indent">
            Ketua Unit Penelitian dan Pengabdian pada Masyarakat (UPPM) Politeknik Tonggak Equator Pontianak dengan ini memberikan tugas kepada:
        </p>

        {{-- TABEL TIM PENELITI --}}
        <table class="tabel-tim">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIDN/NUPTK/NIM</th>
                    <th>Posisi</th>
                </tr>
            </thead>
            <tbody>
                {{-- Baris 1: Auto-fill dari data dosen --}}
                <tr>
                    <td>1</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Ketua Peneliti</td>
                </tr>
                {{-- Baris 2-4: Kosong untuk diisi manual --}}
                <tr>
                    <td>2</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Anggota Peneliti</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Mahasiswa</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>Mahasiswa</td>
                </tr>
            </tbody>
        </table>

        <p class="no-indent">
            Untuk melaksanakan penelitian dalam rangka memenuhi salah satu tugas Tri Dharma Perguruan Tinggi dengan 
            judul<strong>"{{ $judulUsulan }}"</strong> 
            dan diwajibkan untuk memberikan laporan akhir penelitian kepada Ketua UPPM Politeknik Tonggak Equator Pontianak.
        </p>

        <p class="no-indent">
            Demikian tugas ini dibuat agar dapat dipergunakan sebagaimana mestinya.
        </p>
    </div>

    {{-- TTD --}}
    <div class="ttd-section">
        <div class="ttd-box">
            <div class="tempat-tanggal">
                Pontianak, {{ $tanggalSurat }}
            </div>
            <div class="jabatan">
                Ketua UPPM,
            </div>
            <div class="nama">
                Fera Maulina, SET., MM., CAP., PMR.
            </div>
            <div class="nik">
                NIK. 035.1.2908.05
            </div>
        </div>
    </div>

</body>
</html>
