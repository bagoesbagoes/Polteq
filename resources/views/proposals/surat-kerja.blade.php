<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Kerja - {{ $judulUsulan }}</title>
    <style>
        
           /* GENERAL STYLING */
           
        @page {
            margin: 2cm 2cm 2cm 2cm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
        }
        
        
           /* KOP SURAT */
           
        .kop-surat {
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .kop-surat .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .kop-surat h1 {
            margin: 0;
            padding: 0;
            font-size: 18pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .kop-surat h2 {
            margin: 5px 0 0 0;
            padding: 0;
            font-size: 16pt;
            font-weight: bold;
        }
        
        .kop-surat p {
            margin: 3px 0;
            font-size: 10pt;
            line-height: 1.4;
        }
        
        
           /* HEADER SURAT */
           
        .nomor-surat {
            margin-top: 20px;
            margin-bottom: 30px;
        }
        
        .nomor-surat table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .nomor-surat td {
            padding: 3px 0;
            vertical-align: top;
        }
        
        .nomor-surat td:nth-child(1) {
            width: 120px;
        }
        
        .nomor-surat td:nth-child(2) {
            width: 10px;
            text-align: center;
        }
        
        
           /* ISI SURAT */
           
        .isi-surat {
            text-align: justify;
            margin-bottom: 30px;
        }
        
        .isi-surat p {
            margin-bottom: 15px;
        }
        
        .isi-surat .indent {
            text-indent: 50px;
        }
        
        /* Tabel detail penelitian */
        .detail-penelitian {
            margin: 20px 0;
            width: 100%;
        }
        
        .detail-penelitian table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .detail-penelitian td {
            padding: 5px 0;
            vertical-align: top;
        }
        
        .detail-penelitian td:nth-child(1) {
            width: 200px;
            font-weight: bold;
        }
        
        .detail-penelitian td:nth-child(2) {
            width: 20px;
            text-align: center;
        }
        
        
           /* TTD & STEMPEL */
           
        .ttd-section {
            margin-top: 50px;
            text-align: left;
        }
        
        .ttd-box {
            display: inline-block;
            text-align: center;
            margin-left: 60%;
        }
        
        .ttd-box .tempat-tanggal {
            margin-bottom: 5px;
        }
        
        .ttd-box .jabatan {
            font-weight: bold;
            margin-bottom: 80px; /* Space untuk tanda tangan */
        }
        
        .ttd-box .nama {
            font-weight: bold;
            text-decoration: underline;
        }
        
        .ttd-box .nip {
            margin-top: 3px;
            font-size: 10pt;
        }
        
        
           /* TEMBUSAN */
           
        .tembusan {
            margin-top: 40px;
            font-size: 11pt;
        }
        
        .tembusan p {
            margin: 5px 0;
        }
        
        .tembusan ol {
            margin: 5px 0;
            padding-left: 20px;
        }
        
        .tembusan li {
            margin: 3px 0;
        }
    </style>
</head>

<body>
        {{-- KOP SURAT --}}
         
    <div class="kop-surat">
        {{-- Logo Institusi --}}
        @if($logoBase64)
        <img src="{{ $logoBase64 }}" alt="Logo POLTEQ" class="logo">
        @else
            <div style="height: 80px; display: flex; align-items: center; justify-content: center;">
                <span style="font-size: 24pt; font-weight: bold;">POLTEQ</span>
            </div>
        @endif
        
        {{-- Nama Institusi --}}
        <h1>POLITEKNIK TEKNOLOGI</h1>
        <h2>Politeknik Tonggak Equator</h2>
        
        {{-- Alamat & Kontak --}}
        <p>
            JL. FATIMAH NO 1-2 PONTIANAK<br>
            TELP. (0561) 767 884 / HP : 0819 560 8767 / 0812 5771 8282 (WA)
        </p>
    </div>

    
    {{-- NOMOR & TANGGAL SURAT --}}
         
    <div class="nomor-surat">
        <table>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td><strong>{{ $nomorSurat }}</strong></td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>1 (satu) berkas</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><strong>Surat Tugas Penelitian</strong></td>
            </tr>
        </table>
    </div>

    
    {{-- ISI SURAT --}}
         
    <div class="isi-surat">
        {{-- Pembukaan --}}
        <p style="text-align: center; font-weight: bold; margin-bottom: 30px;">
            SURAT TUGAS
        </p>
        
        {{-- Dasar Hukum --}}
        <p>Berdasarkan hasil review dan evaluasi usulan penelitian yang telah dilakukan, dengan ini Ketua Lembaga Penelitian dan Pengabdian Masyarakat (LPPM) Politeknik Teknologi memberikan tugas kepada:</p>
        
        {{-- Detail Dosen --}}
        <div class="detail-penelitian">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $namaDosen }}</td>
                </tr>
                <tr>
                    <td>NIDN / NUPTK</td>
                    <td>:</td>
                    <td>{{ $nidnNuptk }}</td>
                </tr>
                <tr>
                    <td>Jabatan Fungsional</td>
                    <td>:</td>
                    <td>{{ $jabatan }}</td>
                </tr>
                <tr>
                    <td>Judul Penelitian</td>
                    <td>:</td>
                    <td><strong>{{ $judulUsulan }}</strong></td>
                </tr>
            </table>
        </div>
        
        {{-- Tugas --}}
        <p class="indent">
            Untuk melaksanakan penelitian sesuai dengan proposal yang telah disetujui. Penelitian ini diharapkan dapat memberikan kontribusi positif bagi pengembangan ilmu pengetahuan dan teknologi.
        </p>
        
        {{-- Kewajiban --}}
        <p>Dalam melaksanakan tugas penelitian, yang bersangkutan berkewajiban untuk:</p>
        <ol style="padding-left: 30px; margin: 10px 0;">
            <li>Melaksanakan penelitian sesuai dengan proposal yang telah disetujui;</li>
            <li>Melaporkan perkembangan penelitian secara berkala kepada LPPM;</li>
            <li>Menyerahkan laporan akhir penelitian paling lambat 6 (enam) bulan sejak tanggal surat tugas ini;</li>
            <li>Mempublikasikan hasil penelitian pada jurnal ilmiah atau seminar nasional/internasional.</li>
        </ol>
        
        {{-- Penutup --}}
        <p class="indent">
            Demikian surat tugas ini dibuat untuk dapat dilaksanakan dengan penuh tanggung jawab.
        </p>
    </div>

    
    {{-- TTD & STEMPEL --}}
         
    <div class="ttd-section">
        <div class="ttd-box">
            <div class="tempat-tanggal">
                Pontianak, {{ $tanggalSurat }}
            </div>
            <div class="jabatan">
                Ketua UPPM
            </div>
            <div class="nama">
                Dr. Ahmad Santoso, M.T.
            </div>
            <div class="nip">
                NIP. 196801011995031001
            </div>
        </div>
    </div>

    
    {{-- TEMBUSAN --}}
         
    <div class="tembusan">
        <p><strong>Tembusan:</strong></p>
        <ol>
            <li>Direktur Politeknik Teknologi;</li>
            <li>Kepala Bagian Akademik;</li>
            <li>Arsip.</li>
        </ol>
    </div>
</body>
</html>