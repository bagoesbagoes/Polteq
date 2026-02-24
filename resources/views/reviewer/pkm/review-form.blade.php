<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="bg-gray-800 shadow sm:rounded-lg p-6">
        
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('reviewer.pkm') }}" 
               class="inline-flex items-center text-sm text-indigo-400 hover:text-indigo-300">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar PKM
            </a>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="mb-6 rounded-md bg-red-900/20 p-4 border border-red-700">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-400">Ada kesalahan pada review anda :</h3>
                        <ul class="mt-2 text-sm text-red-300 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>   
                    </div>
                </div>
            </div>
        @endif

        {{-- Informasi Proposal --}}
        <div class="mb-6 pb-6 border-b border-gray-700">
            <h2 class="text-2xl font-bold text-white mb-2">{{ $pkm->judul }}</h2>
            <p class="text-gray-400 mb-2">Oleh : {{ $pkm->author->name }} </p>
            <p class="text-gray-300">{{ Str::limit($pkm->abstrak, 150) }}</p>

            {{-- info tambahan --}}
            <div class="mt-3 flex flex-wrap gap-4 text-sm">
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-700 text-gray-300">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Tahun : {{ $pkm->tahun_pelaksanaan }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-700 text-gray-300">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $pkm->sumber_dana }}
                </span>
                <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-700 text-gray-300">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    {{ $pkm->kategori_pkm }}
                </span>
            </div>

            {{-- Action Buttons --}}
            <div class="mt-4 flex gap-3">
                {{-- Download File --}}
                <a href="{{ route('pkm.download', $pkm) }}" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download File PKM
                </a>
                
                {{-- View Detail --}}
                <a href="{{ route('pkm.show', $pkm) }}" 
                target="_blank"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Detail Lengkap
                </a>
            </div>
        </div>
           
        {{-- Form Penilaian --}}
        <form action="POST" action="{{ route('reviewer.pkm-store-review', $pkm) }}" class="space-y-6">
            @csrf

            <h3 class="text-xl font-bold text-white mb-4">Kriteria penilaian PKM</h3>
            
            {{-- Table Scoring --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-300">
                    <thead class="text-xs uppercase bg-gray-700 text-gray-400">
                        <tr>
                            <th class="px-4 py-3">No.</th>
                            <th class="px-4 py-3">Kriteria Penilaian</th>
                            <th class="px-4 py-3">Bobot (%)</th>
                            <th class="px-4 py-3">Skor (0-100)</th>
                            <th class="px-4 py-3">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Kriteria 1 --}}
                        <tr class="border-b border-gray-700">
                            <td class="px-4 py-3">1.</td>
                            <td class="px-4 py-3">
                                <strong>Perumusan Masalah</strong>
                                <ul class="text-xs text-gray-400 mt-1">
                                    <li>. Ketajaman perumusan masalah</li>
                                    <li>. Tujuan penelitian</li>
                                </ul>
                            </td>
                            <td class="px-4 py-3">25</td>
                            <td class="px-4 py-3">
                                <input  type="number" name="perumusan_masalah" min="0" max="100" step="1"
                                        value="{{ old('perumusan_masalah') }}"
                                        class="w-20 bg-gray-700 border border-gray-600 text-white rounded px-2 py-1"
                                        oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0; hitungNilai();"
                                        required>
                            </td>
                            <td class="px-4 py-3"> 
                                <span id="nilai_perumusan" class="font-bold">0</span>
                            </td>
                        </tr>

                        {{-- Kriteria 2 --}}
                        <tr class="border-b border-gray-700">
                            <td class="px-4py-3">2.</td>
                            <td class="px-4 py-3">
                                <strong>Peluang luaran penelitian</strong>
                                <ul class=" text-xs text-gray-400 mt-1">
                                    <li>. Publikasi ilmiah</li>
                                    <li>. Pengembangan iptek - sosbud</li>
                                    <li>. Pengayaan bahan ajar</li>
                                    <li>. HKI</li>
                                </ul>
                            </td>
                            <td class="px-4 py-3">25</td>
                            <td class="px-4 py-3">
                                <input type="number" name="peluang_luaran" min="0" max="100" step="1"
                                        value="{{ old('peluang_luaran') }}"
                                        class="w-20 bg-gray-700 border border-gray-600 text-white rounded px-2 py-1"
                                        oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0; hitungNilai();"
                                        required>
                            </td>
                            <td class="px-4 py-3">
                                <span id="nilai_peluang" class="font-bold">0</span>
                            </td>
                        </tr>

                        {{-- Kriteria 3 --}}
                        <tr class="border-b border-gray-700">
                            <td class="px-4py-3">3.</td>
                            <td class="px-4 py-3">
                                <strong>Metode penelitian</strong>
                                <ul class=" text-xs text-gray-400 mt-1">
                                    <li>. Ketetapan dan kesesuaian metode yang digunakan</li>
                                </ul>
                            </td>
                            <td class="px-4 py-3">25</td>
                            <td class="px-4 py-3">
                                <input type="number" name="metode_pkm" min="0" max="100" step="1"
                                        value="{{ old('metode_pkm') }}"
                                        class="w-20 bg-gray-700 border border-gray-600 text-white rounded px-2 py-1"
                                        oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0; hitungNilai();"
                                        required>
                            </td>
                            <td class="px-4 py-3">
                                <span id="nilai_metode" class="font-bold">0</span>
                            </td>
                        </tr>

                        {{-- Kriteria 4 --}}
                        <tr class="border-b border-gray-700">
                            <td class="px-4py-3">4.</td>
                            <td class="px-4 py-3">
                                <strong>Tinjauan Pustaka</strong>
                                <ul class=" text-xs text-gray-400 mt-1">
                                    <li>. Kesesuaian waktu</li>
                                    <li>. Kesesuaian biaya</li>
                                    <li>. Kesesuaian personalia</li>
                                </ul>
                            </td>
                            <td class="px-4 py-3">15</td>
                            <td class="px-4 py-3">
                                <input type="number" name="tinjauan_pustaka" min="0" max="100" step="1"
                                        value="{{ old('tinjauan_pustaka') }}"
                                        class="w-20 bg-gray-700 border border-gray-600 text-white rounded px-2 py-1"
                                        oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0; hitungNilai();"
                                        required>
                            </td>
                            <td class="px-4 py-3">
                                <span id="nilai_tinjauan" class="font-bold">0</span>
                            </td>
                        </tr>

                        {{-- Kriteria 5 --}}
                        <tr class="border-b border-gray-700">
                            <td class="px-4 py-3">5.</td>
                            <td class="px-4 py-3">
                                <strong>Kelayakan PKM</strong>
                                <ul class="text-xs text-gray-400 mt-1">
                                    <li>• Kesesuaian waktu</li>
                                    <li>• Kesesuaian biaya</li>
                                    <li>• Kesesuaian personalia</li>
                                </ul>
                            </td>
                            <td class="px-4 py-3">10</td>
                            <td class="px-4 py-3">
                                <input type="number" name="kelayakan_pkm" min="0" max="100" step="1"
                                        value="{{ old('kelayakan_pkm') }}"
                                        class="w-20 bg-gray-700 border border-gray-600 text-white rounded px-2 py-1"
                                        oninput="if(this.value > 100) this.value = 100; if(this.value < 0) this.value = 0; hitungNilai();"
                                        required>
                            </td>
                            <td class="px-4 py-3">
                                <span id="nilai_kelayakan" class="font-bold">0</span>
                            </td>
                        </tr>

                        {{-- Total --}}
                        <tr class="bg-gray-700 font-bold">
                            <td colspan="2" class="px-4 py-3 text-right">JUMLAH</td>
                            <td class="px-4 py-3">100</td>
                            <td class="px-4 py-3">-</td>
                            <td class="px-4 py-3">
                                <span id="total_nilai" class="text-lg">0</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Rekomendasi --}}
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-300 mb-3">
                    Rekomendasi <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-6">
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="recommendation" value="setuju" 
                                {{ old('recommendation') == 'setuju' ? 'checked' : '' }}
                                class="w-4 h-4 text-green-600" required>
                        <span class="ml-2 text-white">✅ Setuju (PKM Diterima)</span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="recommendation" value="tidak_setuju"
                                {{ old('recommendation') == 'tidak_setuju' ? 'checked' : '' }}
                                class="w-4 h-4 text-red-600" required>
                        <span class="ml-2 text-white">❌ Tidak Setuju (Perlu Revisi)</span>
                    </label>
                </div>
            </div>

            {{-- Catatan reviewer --}}

            <div>
                <label for="comment" class="block text-sm font-medium text-gray-300 mb-2">
                    catatan/feedback <span class="text-gray-500">(Opsional)</span>
                </label>
                <textarea   name="comment" id="comment" rows="6" maxlength="5000"
                            class="block w-full bg-gray-700 border border-gray-600 text-white rounded-md px-3 py-2"
                            placeholder="Berikan catatan atau saran perbaikan....">{{ old('comment') }}</textarea>
                <p class="mt-1 text-xs text-gray-400">Maksimal 5000 karakter</p>
            </div>

            {{-- Tombol Submit --}}

            <div class="flex justify-end gap-3">
                <a  href="{{ route('reviewer.pkm') }}"
                    class="px-6 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600 transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        💾 Simpan Review
                </button>
            </div>
        </form>
    </div>

    {{-- Javascript hitung nilai otomatis --}}

    <script>
        function hitungNilai() {
            const perumusan = parseFloat(document.querySelector('[name="perumusan_masalah"]').value) || 0;
            const peluang = parseFloat(document.querySelector('[name="peluang_luaran"]').value) || 0;
            const metode = parseFloat(document.querySelector('[name="metode_pkm"]').value) || 0;
            const tinjauan = parseFloat(document.querySelector('[name="tinjauan_pustaka"]').value) || 0;
            const kelayakan = parseFloat(document.querySelector('[name="kelayakan_pkm"]').value) || 0;

            // Hitung nilai dengan bobot
            const nilaiPerumusan = perumusan * 0.25;
            const nilaiPeluang = peluang * 0.25;
            const nilaiMetode = metode * 0.25;
            const nilaiTinjauan = tinjauan * 0.15;
            const nilaiKelayakan = kelayakan * 0.10;

            // Update tampilan
            document.getElementById('nilai_perumusan').textContent = nilaiPerumusan.toFixed(2);
            document.getElementById('nilai_peluang').textContent = nilaiPeluang.toFixed(2);
            document.getElementById('nilai_metode').textContent = nilaiMetode.toFixed(2);
            document.getElementById('nilai_tinjauan').textContent = nilaiTinjauan.toFixed(2);
            document.getElementById('nilai_kelayakan').textContent = nilaiKelayakan.toFixed(2);

            // Total
            const total = nilaiPerumusan + nilaiPeluang + nilaiMetode + nilaiTinjauan + nilaiKelayakan;
            document.getElementById('total_nilai').textContent = total.toFixed(2);
        }

        // Event listener
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', hitungNilai);
        });

        // Hitung saat load
        document.addEventListener('DOMContentLoaded', hitungNilai);
    </script>

</x-layout>