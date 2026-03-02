<?php

namespace Database\Factories;

use App\Models\PkmProposal;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PkmProposal>
 */
class PkmProposalFactory extends Factory
{

    protected $model = PkmProposal::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategoriPkm = [
            'PKM-Karya Cipta (PKM-KC)',
            'PKM-Penelitian (PKM-PE)',
            'PKM-Pengabdian Masyarakat (PKM-PM)',
            'PKM-Kewirausahaan(PKM-K)',
            'PKM-Teknologi (PKM-T)',
            'PKM-Gagasan Tertulis (PKM-GT)',
            'PKM-Artikel ilmiah (PKM-AI)',
        ];

        $themes = [
            'Pemberdayaan Masyarakat',
            'Pengembangan Teknologi Tepat Guna',
            'Pelatihan dan Pendampingan',
            'Peningkatan Kesejahteraan',
            'Edukasi dan Literasi',
            'Inovasi Produk Lokal',
            'Penguatan UMKM',
            'Konservasi Lingkungan',
            'Digitalisasi Desa',
            'Kesehatan Masyarakat',
            'Ekonomi Kreatif',
            'Pertanian Berkelanjutan',
        ];

        $targets =[
            'Desa Mandiri Sejahtera',
            'Kelompok Tani Maju',
            'UMKM Lokal',
            'Ibu-ibu PKK',
            'Karang Taruna',
            'Komuntasi Petani Organik',
            'Kelompok Pengrajinan',
            'Peternak Sapi Perah',
            'Nelayan Pesisir',
            'Pedagang Pasar Tradisional',
            'Lansia Produktif',
        ];

        $locations = [
            'Kabupaten Sleman',
            'Kabupaten Bantul',
            'Kabupaten Kulon Progo',
            'Kabupaten Yogyakarta',
            'Kabupaten Gunungkidul',
            'Kabupaten Purworejo',
            'Kota Surakarta',
            'Kabupaten Boyolali',
            'Kabupaten Klaten',
        ];

        $judul =    $this->faker->randomElement($themes). ''.
                    $this->faker->randomElement($targets). ' di '.
                    $this->faker->randomElement($locations);

        $abstrak = "Program pengabdian kepada masyarakat ini bertujuan untuk".
                    strtolower($this->faker->randomElement($themes)). "".
                    strtolower($this->faker->randomElement($targets)). "".
                    "di wilayah" .$this->faker->randomElement($locations). ".".
                    "Kegiatan dilaksanakan selama" . $this->faker->numberBetween(3, 12). " bulan ".
                    "dengan melibatkan" .$this->faker->numberBetween(20, 100). "peserta.".
                    "Metode pelaksanaan meliputi". $this->faker->randomElement([
                        'Sosialisasi, Pelatihan praktis dan pendampingan berkelanjutan',
                        'Workshop, demonstrasi produk dan evaluasi berkala',
                        'Penyuluhan, praktik lapangan  dan monitoring rutin',
                        'edukasi interaktif, simulasi dan asistensi teknik'
                    ]) . "." .
                    "Target luaran yang diharapkan adalah". $this->faker->randomElement([
                        'Peningkatan keterampilan dan produktivitas masyarakat',
                        'terciptanya produk inovatif yang bernilai ekonomi tinggi',
                        'terbentuknya kelompok usaha mandiri dan berkelanjutan',
                        'meningkatnya kesadaran dan partisipasi aktif masyarakat',
                    ]) . ". " .
                    "Program ini diharapkan memberikan dampak positif jangka panjang ".
                    "bagi kesejahteraan masyarakat dan pembangunan daerah.";

        $anggotaTim = [];
        $jumlahAnggota = $this->faker->numberBetween(1, 4);

        for ($i = 0; $i < $jumlahAnggota; $i++) {
            $anggotaTim[] = [
                'nama' => $this->faker->name(),
                'nidn' => $this->faker->numerify('##########'),
                'peran' => $this->faker->randomElement(['Ketua', 'Anggota', 'Anggota', 'Anggota']),
            ];
        }

        $kelompokRiset = $this->faker->optional(0, 6)->randomElement([
            'Teknologi Informasi dan Komnikasi',
            'Kewirausahaan dan UMKN',
            'Pertanian Berkelanjutan',
            'Kesehatan Masyarakat',
            'Pendidikan dan Literasi',
            'Energi Terbarukan',
            'Ekonomi kreatif',
            'Pengembangan Masyarakat',
        ]);

        $status = $this->faker->randomElement([
            'draft',
            'submitted',
            'accepted',
            'rejected',
            'need_revision'
        ]);

        $revisionNotes = null;
        if ($status === 'need_revision') {
            $revisionNotes = $this->faker->randomElement([
                'Abstrak perlu diperjelas terkait metode pelaksanaan dan target luaran yang spesifik',
                'Judul terlalu umum, mohon dipersempit fokus kegiatan PKM.',
                'Anggaran yang diajukan perlu disesuaikan dengan kegiatan yang direncanakan.',
                'Kelompok sasaran dan lokasi pelaksanaan perlu dijelaskan lebih detail',
                'Luaran yang diharapkan belum terstruktur dengan jelas, mohon dikembangkan indikator keberhasilan',
            ]);
        }

        $submittedAt = in_array($status, ['submitted', 'accepted', 'need_revision'])
            ? $this->faker->dateTimeBetween('-3 months', 'now')
            : null;
            
        return [
            'user_id' =>User::where('role', 'publisher')->inRandomOrder()->first()?->id
                ?? User::factory()->create(['role' => 'publisher'])->id,
            'judul' => $judul,
            'tahun_pelaksanaan' => $this->faker->numberBetween(2024, 2026),
            'sumber_dana' => null,
            'kategori_pkm' => null,
            'kelompok_riset' => null,
            'anggota_tim' => $anggotaTim,
            'abstrak' => $abstrak,
            'file_usulan' => 'pkm_proposal/dummy_pkm_' . $this->faker->uuid() . '.pdf',
            'status' => $status,
            'revision_notes' => $revisionNotes,
            'submitted_at' =>$submittedAt,
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];

    }       

    public function draft(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'draft',
            'submitted_at' => null,
            'revision_notes' => null,
        ]);
    }

    public function submitted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'submitted',
            'submitted_at' => $this->faker->dateTimeBetween('-2 months','now'),
            'revision_notes' => null,
        ]);
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'accepted',
            'submitted_at' => $this->faker->dateTimeBetween('-3 months', '-1 month'),
            'revision_notes' => null,
        ]);
    }

    public function needRevision(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'need_revision',
            'submitted_at' => $this->faker->dateTimeBetween('-2 months', '-1 week' ),
            'revision_notes' => $this->faker->randomElement([
                'Abstrak perlu diperjelas terkait metode pelaksanaan dan target luaran yang spesifik.',
                'Judul terlalu umum, mohon dipersempit fokus kegiatan PKM.',
                'Anggaran yang diajukan perlu disesuaikan dengan kegiatan yang direncanakan',
                'Kelompok sasaran dan lokasi pelaksanaan perlu dijelaskan lebih detail.',
                'Luaran yang diharapkan belum terukur dengan jelas, mohon ditambahkan indikator keberhasilan '
            ])
        ]);
    }

    public function dipa(): static
    {
        return $this->state(fn (array $attributes) => [
            'sumber_dana' => 'DIPA',
        ]);
    }

    public function nonDipa(): static
    {
        return $this->state(fn(array $attributes) => [
            'sumber_dana' => 'Non-DIPA'
        ]);
    }
}
