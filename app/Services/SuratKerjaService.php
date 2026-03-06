<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Proposal;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Shared\Converter;


class SuratKerjaService
{
    
    public function generateDocx(Proposal $proposal, array $data)
    {
        $phpWord = new PhpWord();
        
        // Konfigurasi Section
        $section = $phpWord->addSection([
            'marginLeft' => Converter::cmToTwip(2),
            'marginRight' => Converter::cmToTwip(2),
            'marginTop' => Converter::cmToTwip(3),
            'marginBottom' => Converter::cmToTwip(2),
            'headerHeight' => Converter::cmToTwip(2),
        ]);

        // Build document sections
        $this->addHeader($section);
        $this->addJudul($section, $data['nomorSurat']);
        $this->addPembuka($section);
        $this->addTabelTim($section, $phpWord);
        $this->addTugas($section, $data['judulUsulan']);
        $this->addPenutup($section);
        $this->addTandaTangan($section, $data['tanggalSurat']);

        // Save and return
        return $this->saveTempFile($phpWord, $proposal);
    }

    /**
     * Add header with logo
     */
    private function addHeader($section)
    {
        $logoPath = public_path('image/KopPolteq.png');
        
        if (file_exists($logoPath)) {
            $header = $section->addHeader();
            
            $header->addImage($logoPath, [
                'width' => 500,
                'height' => 90,
                'marginTop' => -20,
                'alignment' => Jc::CENTER,
            ]);

            $header->addText('', [], ['spaceAfter' => 250]);

        }
    }

    /**
     * Add title section
     */
    private function addJudul($section, $nomorSurat)
    {
        $section->addText(
            'SURAT TUGAS',
            // Array 2: Font Style
            [
                'bold' => true, 
                'size' => 14, 
                'name' => 'Times New Roman',
                'underline' => 'single'
            ],
            // Array 3: Paragraph Style
            [
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 
                'spaceAfter' => 0,
                'lineHeight' => 1.5,
                'spaceAfter' => 150,
            ]
        );
        
        $section->addText(
            'Nomor: ........................................................ ' ,
            ['size' => 12, 'name' => 'Times New Roman'],

            [   'alignment' => Jc::CENTER, 'spaceAfter' => 200,
                'lineHeight' => 1.5,

            ]
        );
    }

    /**
     * Add opening paragraph
     */
    private function addPembuka($section)
    {
        $section->addText(
            'Ketua Unit Penelitian dan Pengabdian pada Masyarakat (UPPM) Politeknik Tonggak Equator Pontianak dengan ini memberikan tugas kepada:',
            
            [   'size' => 12, 'name' => 'Times New Roman'],
            
            [
                'alignment' => Jc::BOTH, 'spaceAfter' => 200,
                'lineHeight' => 1.5,
            ]
        );
    }

    /**
     * Add team table
     */
    private function addTabelTim($section, $phpWord)
    {
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
            'alignment' => Jc::CENTER,
        ];
        
        $phpWord->addTableStyle('TimPeneliti', $tableStyle);
        $table = $section->addTable('TimPeneliti');

        // Header
        $table->addRow(400);
        $table->addCell(800, ['valign' => 'center'])
            ->addText('No', ['size' => 12, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
        $table->addCell(4000, ['valign' => 'center'])
            ->addText('Nama', ['size' => 12, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
        $table->addCell(3000, ['valign' => 'center'])
            ->addText('NIDN/NUPTK/NIM', ['size' => 12, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
        $table->addCell(3000, ['valign' => 'center'])
            ->addText('Posisi', ['size' => 12, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);

        // Rows
        $rows = [
            ['1', '', '', 'Ketua Peneliti'],
            ['2', '', '', 'Anggota Peneliti'],
            ['3', '', '', 'Mahasiswa'],
            ['4', '', '', 'Mahasiswa'],
        ];

        foreach ($rows as $row) {
            $table->addRow(400);
            $table->addCell(800, ['valign' => 'center'])
                ->addText($row[0], ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
            $table->addCell(4000, ['valign' => 'center'])
                ->addText($row[1], ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::LEFT]);
            $table->addCell(3000, ['valign' => 'center'])
                ->addText($row[2], ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
            $table->addCell(3000, ['valign' => 'center'])
                ->addText($row[3], ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
        }

        $section->addTextBreak(1);
    }

    /**
     * Add task description
     */
    private function addTugas($section, $judulUsulan)
    {
        $textRun = $section->addTextRun([
            'alignment' => Jc::BOTH, 
            'spaceAfter' => 200,
            'lineHeight' => 1.5
        ]);

        $textRun->addText(
            'Untuk melaksanakan penelitian dalam rangka memenuhi salah satu tugas Tri Dharma Perguruan Tinggi dengan judul ',
            ['size' => 12, 'name' => 'Times New Roman']
        );

        $textRun->addText(
            '"' . $judulUsulan . '"',
            ['bold' => true, 'size' => 12, 'name' => 'Times New Roman','marker' => 'yellow']
        );

        $textRun->addText(
            ' dan diwajibkan untuk memberikan laporan akhir penelitian kepada Ketua UPPM Politeknik Tonggak Equator Pontianak.',
            ['size' => 12, 'name' => 'Times New Roman']
        );
    }

    /**
     * Add closing paragraph
     */
    private function addPenutup($section)
    {
        $section->addText(
            'Demikian tugas ini dibuat agar dapat dipergunakan sebagaimana mestinya.',

            ['size' => 12, 'name' => 'Times New Roman'],

            [   'alignment' => Jc::BOTH, 'spaceAfter' => 400, 
                'lineHeight' => 1.5]
        );
    }

    /**
     * Add signature section
     */
    private function addTandaTangan($section, $tanggalSurat)
    {
        // Tempat & Tanggal
        $ttdTable = $section->addTable();
        $ttdTable->addRow();
        $ttdTable->addCell(6000);
        $ttdTable->addCell(5000)->addText(
            'Pontianak, ' . $tanggalSurat,
            ['size' => 12, 'name' => 'Times New Roman'],
            ['alignment' => Jc::LEFT]
        );

        // Jabatan
        $ttdTable = $section->addTable();
        $ttdTable->addRow();
        $ttdTable->addCell(6000);
        $ttdTable->addCell(5000)->addText(
            'Ketua UPPM,',
            ['size' => 12, 'name' => 'Times New Roman'],
            ['alignment' => Jc::LEFT, 'spaceAfter' => 1000]
        );

        // Nama
        $ttdTable = $section->addTable();
        $ttdTable->addRow();
        $ttdTable->addCell(6000);
        $ttdTable->addCell(5000)->addText(
            'Fera Maulina, SET., MM., CAP., PMR.',
            ['size' => 12, 'name' => 'Times New Roman'],
            [   'alignment' => Jc::LEFT,
                'spacing' => 0 ,
                'spaceAfter' => 0,
                'spaceBefore' => 0,]
        );

        // NIK
        $ttdTable = $section->addTable();
        $ttdTable->addRow();
        $ttdTable->addCell(6000);
        $ttdTable->addCell(5000)->addText(
            'NIK. 035.1.2908.05',
            ['size' => 12, 'name' => 'Times New Roman'],
            ['alignment' => Jc::LEFT,
                'spacing' => 0,
                'spaceAfter' => 0,
                'spaceBefore' => 0,]
        );
    }

    /**
     * Save temp file and return download response
     */
    private function saveTempFile($phpWord, Proposal $proposal)
    {
        $filename = 'Surat_Kerja_' . str_replace(' ', '_', $proposal->judul) . '.docx';
        $tempFile = storage_path('app/temp/' . $filename);
        
        // Create temp directory if not exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }

    /**
     * Prepare data for surat kerja
     * 
     * @param Proposal $proposal
     * @return array
     */
    public function prepareData(Proposal $proposal)
    {
        $nomorSurat = sprintf(
            '%03d/SK-PENELITIAN/POLTEQ/%s/%04d',
            $proposal->id,
            strtoupper(Carbon::now()->translatedFormat('F')),
            Carbon::now()->year
        );
        
        return [
            'proposal' => $proposal,
            'nomorSurat' => $nomorSurat,
            'tanggalSurat' => Carbon::now()->translatedFormat('d F Y'),
            'namaDosen' => $proposal->author->name,
            'nidnNuptk' => $proposal->author->nidn_nuptk,
            'jabatan' => $proposal->author->jabatan_fungsional,
            'prodi' => $proposal->author->prodi,
            'judulUsulan' => $proposal->judul,
        ];
    }

    
 
//  PKM SURAT TUGAS METHODS

    public function generatePkmDocx(\App\Models\PkmProposal $pkm, array $data)
    {
        $phpWord = new PhpWord();
        
        // Konfigurasi Section
        $section = $phpWord->addSection([
            'marginLeft' => Converter::cmToTwip(2),
            'marginRight' => Converter::cmToTwip(2),
            'marginTop' => Converter::cmToTwip(3),
            'marginBottom' => Converter::cmToTwip(2),
            'headerHeight' => Converter::cmToTwip(2),
        ]);

        // Build document sections
        $this->addHeader($section);
        $this->addJudul($section, $data['nomorSurat']);
        $this->addPembukaPkm($section);
        $this->addTabelTimPkm($section, $phpWord, $pkm);
        $this->addTugasPkm($section, $data['judulUsulan']);
        $this->addPenutup($section);
        $this->addTandaTangan($section, $data['tanggalSurat']);

        // Save and return
        return $this->saveTempFilePkm($phpWord, $pkm);
    }

/**
 * Add opening paragraph for PKM
 */
    private function addPembukaPkm($section)
    {
        $section->addText(
            'Ketua Unit Penelitian dan Pengabdian pada Masyarakat (UPPM) Politeknik Tonggak Equator Pontianak dengan ini memberikan tugas kepada:',
            
            ['size' => 12, 'name' => 'Times New Roman'],
            
            [
                'alignment' => Jc::BOTH, 
                'spaceAfter' => 200,
                'lineHeight' => 1.5,
            ]
        );
    }

/**
 * Add team table for PKM
 */
    private function addTabelTimPkm($section, $phpWord, $pkm)
    {
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
            'alignment' => Jc::CENTER,
        ];
        
        $phpWord->addTableStyle('TimPKM', $tableStyle);
        $table = $section->addTable('TimPKM');

        // Header
        $table->addRow(400);
        $table->addCell(800, ['valign' => 'center'])
            ->addText('No', ['size' => 12, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
        $table->addCell(4000, ['valign' => 'center'])
            ->addText('Nama', ['size' => 12, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
        $table->addCell(3000, ['valign' => 'center'])
            ->addText('NIDN/NUPTK', ['size' => 12, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
        $table->addCell(3000, ['valign' => 'center'])
            ->addText('Posisi', ['size' => 12, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);

        // Row 1: Ketua (Author)
        $table->addRow(400);
        $table->addCell(800, ['valign' => 'center'])
            ->addText('1', ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
        $table->addCell(4000, ['valign' => 'center'])
            ->addText($pkm->author->name, ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::LEFT]);
        $table->addCell(3000, ['valign' => 'center'])
            ->addText($pkm->author->nidn_nuptk ?? '-', ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
        $table->addCell(3000, ['valign' => 'center'])
            ->addText('Ketua PKM', ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);

        // Rows for Anggota Tim (if exists)
        if (!empty($pkm->anggota_tim) && is_array($pkm->anggota_tim)) {
            $no = 2;
            foreach ($pkm->anggota_tim as $anggota) {
                $table->addRow(400);
                $table->addCell(800, ['valign' => 'center'])
                    ->addText($no, ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
                $table->addCell(4000, ['valign' => 'center'])
                    ->addText($anggota['nama'] ?? $anggota, ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::LEFT]);
                $table->addCell(3000, ['valign' => 'center'])
                    ->addText($anggota['nidn'] ?? '-', ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
                $table->addCell(3000, ['valign' => 'center'])
                    ->addText($anggota['peran'] ?? 'Anggota PKM', ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
                $no++;
            }
        } else {
            // Empty rows if no anggota
            for ($i = 2; $i <= 3; $i++) {
                $table->addRow(400);
                $table->addCell(800, ['valign' => 'center'])
                    ->addText($i, ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
                $table->addCell(4000, ['valign' => 'center'])
                    ->addText('', ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::LEFT]);
                $table->addCell(3000, ['valign' => 'center'])
                    ->addText('', ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
                $table->addCell(3000, ['valign' => 'center'])
                    ->addText('Anggota PKM', ['size' => 11, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
            }
        }

        $section->addTextBreak(1);
    }

/**
 * Add task description for PKM
 */
    private function addTugasPkm($section, $judulUsulan)
    {
        $textRun = $section->addTextRun([
            'alignment' => Jc::BOTH, 
            'spaceAfter' => 200,
            'lineHeight' => 1.5
        ]);

        $textRun->addText(
            'Untuk melaksanakan Program Kreativitas Mahasiswa (PKM) dalam rangka memenuhi salah satu tugas Tri Dharma Perguruan Tinggi dengan judul ',
            ['size' => 12, 'name' => 'Times New Roman']
        );

        $textRun->addText(
            '"' . $judulUsulan . '"',
            ['bold' => true, 'size' => 12, 'name' => 'Times New Roman', 'bgColor' => 'FFFF00']
        );

        $textRun->addText(
            ' dan diwajibkan untuk memberikan laporan akhir PKM kepada Ketua UPPM Politeknik Tonggak Equator Pontianak.',
            ['size' => 12, 'name' => 'Times New Roman']
        );
    }

/**
 * Save temp file for PKM and return download response
 */
    private function saveTempFilePkm($phpWord, $pkm)
    {
        $filename = 'Surat_Tugas_PKM_' . str_replace(' ', '_', substr($pkm->judul, 0, 50)) . '.docx';
        $tempFile = storage_path('app/temp/' . $filename);
        
        // Create temp directory if not exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);

        return response()->download($tempFile)->deleteFileAfterSend(true);
    }

/**
 * Prepare data for PKM surat tugas
 * 
 * @param \App\Models\PkmProposal $pkm
 * @return array
 */
    public function preparePkmData($pkm)
    {
        $nomorSurat = sprintf(
            '%03d/ST-PKM/POLTEQ/%s/%04d',
            $pkm->id,
            strtoupper(Carbon::now()->translatedFormat('F')),
            Carbon::now()->year
        );
        
        return [
            'pkm' => $pkm,
            'nomorSurat' => $nomorSurat,
            'tanggalSurat' => Carbon::now()->translatedFormat('d F Y'),
            'namaDosen' => $pkm->author->name,
            'nidnNuptk' => $pkm->author->nidn_nuptk ?? '-',
            'jabatan' => $pkm->author->jabatan_fungsional ?? '-',
            'prodi' => $pkm->author->prodi ?? '-',
            'judulUsulan' => $pkm->judul,
            'tahunPelaksanaan' => $pkm->tahun_pelaksanaan,
        ];
    }

}