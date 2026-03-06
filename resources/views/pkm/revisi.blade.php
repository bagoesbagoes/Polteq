<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6" x-data="{ anggotaCount: {{ $pkm->anggota_tim ? count($pkm->anggota_tim) : 0 }} }">
        
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('pkm.show', $pkm) }}" 
               class="inline-flex items-center text-sm text-indigo-400 hover:text-indigo-300">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Detail PKM
            </a>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="mb-6 rounded-md bg-red-900/20 p-4 border border-red-700">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-red-400">Ada kesalahan:</h3>
                        <ul class="mt-2 text-sm text-red-300 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        

        {{-- SECTION 1: DETAIL PKM (READ-ONLY) --}}
        <div class="mb-6 bg-gray-800 shadow sm:rounded-lg overflow-hidden">
            
            {{-- Header --}}
            <div class="px-6 py-5 border-b border-gray-700">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-white">{{ $pkm->judul }}</h2>
                        <div class="mt-3 flex flex-wrap gap-3">
                            {!! $pkm->status_badge !!}
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                                📅 Tahun {{ $pkm->tahun_pelaksanaan }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-700 text-gray-300">
                                👤 {{ $pkm->author->name }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="px-6 py-5 space-y-6">
                
                {{-- Abstrak --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-2">Abstrak</h3>
                    <p class="text-white whitespace-pre-line">{{ $pkm->abstrak }}</p>
                </div>

                {{-- Anggota Tim --}}
                @if($pkm->anggota_tim && count($pkm->anggota_tim) > 0)
                    <div>
                        <h3 class="text-sm font-medium text-gray-400 mb-2">Anggota Tim</h3>
                        <ul class="list-disc list-inside text-white space-y-1">
                            @foreach($pkm->anggota_tim as $anggota)
                                <li>{{ is_array($anggota) ? ($anggota['nama'] ?? $anggota) : $anggota }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- File Info --}}
                <div>
                    <h3 class="text-sm font-medium text-gray-400 mb-2">File PKM</h3>
                    <div class="flex items-center gap-3">
                        <span class="text-white">{{ basename($pkm->file_usulan) }}</span>
                        <span class="text-gray-400 text-sm">({{ $pkm->file_size_human }})</span>
                        <a href="{{ route('pkm.download', $pkm) }}" 
                           class="inline-flex items-center text-sm text-indigo-400 hover:text-indigo-300">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>

                {{-- Timestamps --}}
                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-700">
                    <div>
                        <h3 class="text-sm font-medium text-gray-400">Dibuat</h3>
                        <p class="text-white">{{ $pkm->created_at->format('d M Y H:i') }}</p>
                    </div>
                    @if($pkm->submitted_at)
                        <div>
                            <h3 class="text-sm font-medium text-gray-400">Disubmit</h3>
                            <p class="text-white">{{ $pkm->submitted_at->format('d M Y H:i') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- SECTION 2 : Form nilai --}}
        @if($pkm->reviews && $pkm->reviews->count() > 0)
            <div class="mb-6 bg-gray-800 shadow sm:rounded-lg overflow-hidden">
                <div class="px-6 py-5">
                    @foreach($pkm->reviews as $review)
                        <div class="mb-6 last:mb-0">
                            {{-- Reviewer Info --}}
                            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-700">
                                <div>
                                    <p class="text-sm font-medium text-white">{{ $review->reviewer->name }}</p>
                                    <p class="text-xs text-gray-400">Direview pada: {{ $review->created_at->format('d M Y, H:i') }}</p>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $review->recommendation === 'setuju' ? 'bg-green-900/30 text-green-300' : 'bg-red-900/30 text-red-300' }}">
                                        {{ $review->recommendation === 'setuju' ? '✅ Disetujui' : '❌ Perlu Revisi' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Tabel Penilaian --}}
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Kriteria Penilaian</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase">Bobot (%)</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase">Skor (0-100)</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase">Nilai</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        {{-- Kriteria 1: Perumusan Masalah --}}
                                        <tr class="hover:bg-gray-700/50">
                                            <td class="px-4 py-3 text-white">
                                                <strong>Perumusan Masalah</strong>
                                                <ul class="text-xs text-gray-400 mt-1 ml-4 list-disc">
                                                    <li>Ketajaman perumusan masalah</li>
                                                    <li>Tujuan penelitian</li>
                                                </ul>
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-300">25</td>
                                            <td class="px-4 py-3 text-center text-white font-medium">
                                                {{ $review->scores['perumusan_masalah'] ?? 0 }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-white font-medium">
                                                {{ number_format(($review->scores['perumusan_masalah'] ?? 0) * 0.25, 2) }}
                                            </td>
                                        </tr>

                                        {{-- Kriteria 2: Peluang Luaran --}}
                                        <tr class="hover:bg-gray-700/50">
                                            <td class="px-4 py-3 text-white">
                                                <strong>Peluang Luaran Penelitian</strong>
                                                <ul class="text-xs text-gray-400 mt-1 ml-4 list-disc">
                                                    <li>Publikasi ilmiah</li>
                                                    <li>Pengembangan iptek - sosbud</li>
                                                    <li>Pengayaan bahan ajar</li>
                                                    <li>HKI</li>
                                                </ul>
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-300">25</td>
                                            <td class="px-4 py-3 text-center text-white font-medium">
                                                {{ $review->scores['peluang_luaran'] ?? 0 }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-white font-medium">
                                                {{ number_format(($review->scores['peluang_luaran'] ?? 0) * 0.25, 2) }}
                                            </td>
                                        </tr>

                                        {{-- Kriteria 3: Metode PKM --}}
                                        <tr class="hover:bg-gray-700/50">
                                            <td class="px-4 py-3 text-white">
                                                <strong>Metode Penelitian</strong>
                                                <ul class="text-xs text-gray-400 mt-1 ml-4 list-disc">
                                                    <li>Ketepatan dan kesesuaian metode yang digunakan</li>
                                                </ul>
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-300">25</td>
                                            <td class="px-4 py-3 text-center text-white font-medium">
                                                {{ $review->scores['metode_pkm'] ?? 0 }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-white font-medium">
                                                {{ number_format(($review->scores['metode_pkm'] ?? 0) * 0.25, 2) }}
                                            </td>
                                        </tr>

                                        {{-- Kriteria 4: Tinjauan Pustaka --}}
                                        <tr class="hover:bg-gray-700/50">
                                            <td class="px-4 py-3 text-white">
                                                <strong>Tinjauan Pustaka</strong>
                                                <ul class="text-xs text-gray-400 mt-1 ml-4 list-disc">
                                                    <li>Kesesuaian waktu</li>
                                                    <li>Kesesuaian biaya</li>
                                                    <li>Kesesuaian personalia</li>
                                                </ul>
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-300">15</td>
                                            <td class="px-4 py-3 text-center text-white font-medium">
                                                {{ $review->scores['tinjauan_pustaka'] ?? 0 }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-white font-medium">
                                                {{ number_format(($review->scores['tinjauan_pustaka'] ?? 0) * 0.15, 2) }}
                                            </td>
                                        </tr>

                                        {{-- Kriteria 5: Kelayakan PKM --}}
                                        <tr class="hover:bg-gray-700/50">
                                            <td class="px-4 py-3 text-white">
                                                <strong>Kelayakan PKM</strong>
                                                <ul class="text-xs text-gray-400 mt-1 ml-4 list-disc">
                                                    <li>Kesesuaian waktu</li>
                                                    <li>Kesesuaian biaya</li>
                                                    <li>Kesesuaian personalia</li>
                                                </ul>
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-300">10</td>
                                            <td class="px-4 py-3 text-center text-white font-medium">
                                                {{ $review->scores['kelayakan_pkm'] ?? 0 }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-white font-medium">
                                                {{ number_format(($review->scores['kelayakan_pkm'] ?? 0) * 0.10, 2) }}
                                            </td>
                                        </tr>

                                        {{-- Total --}}
                                        <tr class="bg-gray-700 font-bold">
                                            <td class="px-4 py-3 text-right text-white" colspan="2">TOTAL NILAI</td>
                                            <td class="px-4 py-3 text-center text-white">-</td>
                                            <td class="px-4 py-3 text-center text-lg text-white">
                                                {{ number_format($review->total_score ?? $review->score ?? 0, 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            {{-- Comments --}}
                            @if($review->comments)
                                <div class="mt-6 mb-6 bg-yellow-900/20 border border-yellow-700 rounded-lg p-6">
                                    <div class="flex items-start">
                                        <svg class="w-6 h-6 text-yellow-400 mr-3 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-yellow-400">Catatan Revisi dari Reviewer</h3>
                                            <div class="mt-3 p-4 bg-yellow-900/30 rounded-md border border-yellow-800">
                                                <p class="text-sm text-yellow-200 whitespace-pre-line">{{ $pkm->revision_notes }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- SECTION 3: FORM REVISI --}}
        <div class="bg-gray-800 shadow sm:rounded-lg p-6">
            
            <div class="border-b border-gray-700 pb-4 mb-6">
                <h2 class="text-xl font-semibold text-white">Form Revisi PKM</h2>
                <p class="mt-1 text-sm text-gray-400">Perbaiki PKM Anda sesuai catatan reviewer di atas</p>
            </div>

            <form method="POST" action="{{ route('pkm.update', $pkm) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Judul --}}
                <div>
                    <label for="judul" class="block text-sm font-medium text-white">
                        Judul PKM <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $pkm->judul) }}" required
                           class="mt-2 block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                </div>

                {{-- Tahun Pelaksanaan --}}
                <div>
                    <label for="tahun_pelaksanaan" class="block text-sm font-medium text-white">
                        Tahun Pelaksanaan <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="tahun_pelaksanaan" id="tahun_pelaksanaan" 
                           value="{{ old('tahun_pelaksanaan', $pkm->tahun_pelaksanaan) }}" 
                           min="{{ date('Y') }}" 
                           required
                           class="mt-2 block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                    <p class="mt-1 text-xs text-gray-400">Tahun minimal: {{ date('Y') }}</p>
                </div>

                {{-- Anggota Tim --}}
                <div>
                    <label class="block text-sm font-medium text-white mb-2">
                        Anggota Tim <span class="text-gray-400">(Opsional)</span>
                    </label>
                    
                    {{-- Existing Anggota --}}
                    @if($pkm->anggota_tim && count($pkm->anggota_tim) > 0)
                        @foreach($pkm->anggota_tim as $index => $anggota)
                            <div class="flex gap-2 mb-2">
                                <input type="text" 
                                       name="anggota_tim[{{ $index }}]" 
                                       value="{{ is_array($anggota) ? ($anggota['nama'] ?? $anggota) : $anggota }}" 
                                       placeholder="Nama Anggota"
                                       class="flex-1 rounded-md border-0 bg-gray-700 px-3 py-2 text-white ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        @endforeach
                    @endif
                    
                    {{-- Dynamic Anggota --}}
                    <template x-for="i in anggotaCount - {{ $pkm->anggota_tim ? count($pkm->anggota_tim) : 0 }}" :key="i">
                        <div class="flex gap-2 mt-2">
                            <input type="text" 
                                   :name="'anggota_tim[' + ({{ $pkm->anggota_tim ? count($pkm->anggota_tim) : 0 }} + i - 1) + ']'" 
                                   placeholder="Nama Anggota"
                                   class="flex-1 rounded-md border-0 bg-gray-700 px-3 py-2 text-white ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                            <button type="button" @click="anggotaCount--"
                                    class="px-3 py-2 text-red-400 hover:text-red-300">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                    
                    <button type="button" @click="anggotaCount++" 
                            class="mt-2 inline-flex items-center px-3 py-1 text-sm text-indigo-400 hover:text-indigo-300">
                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Anggota
                    </button>
                </div>

                {{-- Abstrak --}}
                <div>
                    <label for="abstrak" class="block text-sm font-medium text-white">
                        Abstrak <span class="text-red-500">*</span>
                    </label>
                    <textarea name="abstrak" id="abstrak" rows="6" required
                              class="mt-2 block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-indigo-500 sm:text-sm">{{ old('abstrak', $pkm->abstrak) }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">Minimal 100 karakter</p>
                </div>

                {{-- File Upload --}}
                <div>
                    <label for="file_usulan" class="block text-sm font-medium text-white">
                        Upload File Usulan Baru <span class="text-gray-400">(Opsional - kosongkan jika tidak ingin mengubah file)</span>
                    </label>
                    <input type="file" name="file_usulan" id="file_usulan" accept=".pdf"
                           class="mt-2 block w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                    <p class="mt-2 text-sm text-gray-400">File saat ini: {{ basename($pkm->file_usulan) }} ({{ $pkm->file_size_human }})</p>
                    <p class="text-sm text-gray-400">Format: PDF. Maksimal: 10MB</p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-700">
                    
                    <div class=" bg-blue-900/20 border border-blue-700 rounded-md items-center px-4 py-2">
                        <p class="text-xs text-blue-300">
                            💡 <strong>Reminder :</strong> Setelah Anda menyimpan perubahan, PKM akan otomatis disubmit ulang untuk direview kembali.
                        </p>
                    </div>

                    <a href="{{ route('pkm.show', $pkm) }}" 
                       class="rounded-md bg-gray-700 px-4 py-2 text-sm font-medium text-white hover:bg-gray-600 transition">
                        Batal
                    </a>
                    <button type="submit" 
                            class="rounded-md bg-yellow-600 px-4 py-2 text-sm font-medium text-white hover:bg-yellow-700 transition">
                        📝 Simpan Revisi & Submit Ulang
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-layout>