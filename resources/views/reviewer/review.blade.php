{{-- resources/views/reviewer/review.blade.php --}}
<x-layout>
    <x-slot:title>Form Review Usulan</x-slot:title>

    <div class="bg-gray-800 shadow sm:rounded-lg p-6">
        
        {{-- ERROR MESSAGES SECTION --}}
        @if($errors->any())
            <div class="mb-6 rounded-md bg-red-900/20 p-4 border border-red-700">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-400">Ada kesalahan pada review Anda:</h3>
                        <ul class="mt-2 text-sm text-red-300 list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
            <div class="mb-6 rounded-md bg-green-900/20 p-4 border border-green-700">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-400">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Informasi Proposal --}}
        <div class="mb-6 pb-6 border-b border-gray-700">
            <h2 class="text-2xl font-bold text-white mb-2">{{ $proposal->judul }}</h2>
            <p class="text-gray-400 mb-2">Oleh: {{ $proposal->author->name }}</p>
            <p class="text-gray-300">{{ Str::limit($proposal->deskripsi, 200) }}</p>
            
            {{-- Link Download File --}}
            <a href="{{ Storage::url($proposal->file_usulan) }}" 
               target="_blank"
               class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                📄 Lihat File usulan
            </a>
        </div>

        {{-- Form Review --}}
        <form method="POST" action="{{ route('reviewer.store-review', $proposal) }}" class="space-y-6">
            @csrf

            <h3 class="text-xl font-bold text-white mb-4">Kriteria Penilaian</h3>

            {{-- Tabel Scoring --}}
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
                        {{-- Kriteria 1 : Perumusan masalah --}}
                        <tr class="border-b border-gray-700">
                            <td class="px-4 py-3">1.</td>
                            <td class="px-4 py-3">
                                <strong>Perumusan masalah</strong>
                                <ul class="text-xs text-gray-400 mt-1">
                                    <li>• Ketajaman perumusan masalah</li>
                                    <li>• Tujuan penelitian</li>
                                </ul>
                            </td>
                            <td class="px-4 py-3">25</td>
                            <td class="px-4 py-3">
                                <input 
                                    type="number" 
                                    name="pendahuluan" 
                                    min="0" 
                                    max="100" 
                                    step="1"
                                    value="{{ old('pendahuluan', $review->scores['pendahuluan'] ?? '') }}"
                                    class="w-20 bg-gray-700 border {{ $errors->has('pendahuluan') ? 'border-red-500' : 'border-gray-600' }} text-white rounded px-2 py-1"
                                    oninput=
                                    "
                                        if(this.value > 100) this.value = 100;
                                        if(this.value < 0) this.value = 0;
                                        hitungNilai();
                                    "
                                    required>
                                @error('pendahuluan')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </td>
                            <td class="px-4 py-3">
                                <span id="nilai_pendahuluan" class="font-bold">0</span>
                            </td>
                        </tr>

                        {{-- Kriteria 2 : Peluang luaran penelitian --}}
                        <tr class="border-b border-gray-700">
                            <td class="px-4 py-3">2.</td>
                            <td class="px-4 py-3">
                                <strong>Peluang luaran penelitian</strong>
                                <ul class="text-xs text-gray-400 mt-1">
                                    <li>• Publikasi ilmiah</li>
                                    <li>• Pengembangan Iptek - Sosbud</li>
                                    <li>• Pengayaan bahan ajar</li>
                                    <li>• HKI</li>
                                </ul>
                            </td>
                            <td class="px-4 py-3">25</td>
                            <td class="px-4 py-3">
                                <input 
                                    type="number" 
                                    name="tinjauan_pustaka" 
                                    min="0" 
                                    max="100" 
                                    step="1"
                                    value="{{ old('tinjauan_pustaka', $review->scores['tinjauan_pustaka'] ?? '') }}"
                                    class="w-20 bg-gray-700 border {{ $errors->has('tinjauan_pustaka') ? 'border-red-500' : 'border-gray-600' }} text-white rounded px-2 py-1"
                                    oninput="
                                        if(this.value > 100) this.value = 100;
                                        if(this.value < 0) this.value = 0;
                                        hitungNilai();
                                    "
                                    required>
                                @error('tinjauan_pustaka')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </td>
                            <td class="px-4 py-3">
                                <span id="nilai_tinjauan" class="font-bold">0</span>
                            </td>
                        </tr>

                        {{-- Kriteria 3 : Metode penelitian --}}
                        <tr class="border-b border-gray-700">
                            <td class="px-4 py-3">3.</td>
                            <td class="px-4 py-3">
                                <strong>Metode penelitian</strong>
                                <ul class="text-xs text-gray-400 mt-1">
                                    <li>• Ketetapan dan kesesuaian metode yang digunakan</li>
                                </ul>
                            </td>
                            <td class="px-4 py-3">25</td>
                            <td class="px-4 py-3">
                                <input 
                                    type="number" 
                                    name="metodologi" 
                                    min="0" 
                                    max="100" 
                                    step="1"
                                    value="{{ old('metodologi', $review->scores['metodologi'] ?? '') }}"
                                    class="w-20 bg-gray-700 border {{ $errors->has('metodologi') ? 'border-red-500' : 'border-gray-600' }} text-white rounded px-2 py-1"
                                    oninput="
                                        if(this.value > 100) this.value = 100;
                                        if(this.value < 0) this.value = 0;
                                        hitungNilai();
                                    "
                                    required>
                                @error('metodologi')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </td>
                            <td class="px-4 py-3">
                                <span id="nilai_metodologi" class="font-bold">0</span>
                            </td>
                        </tr>

                        {{-- Kriteria 4 : Tinjauan pustaka --}}
                        <tr class="border-b border-gray-700">
                            <td class="px-4 py-3">4.</td>
                            <td class="px-4 py-3">
                                <strong>Tinjauan pustaka</strong>
                                <ul class="text-xs text-gray-400 mt-1">
                                    <li>• Kesesuaian waktu</li>
                                    <li>• Kesesuaian biaya</li>
                                    <li>• Kesesuaian personalia</li>
                                </ul>
                            </td>
                            <td class="px-4 py-3">15</td>
                            <td class="px-4 py-3">
                                <input 
                                    type="number" 
                                    name="kelayakan" 
                                    min="0" 
                                    max="100" 
                                    step="1"
                                    value="{{ old('kelayakan', $review->scores['kelayakan'] ?? '') }}"
                                    class="w-20 bg-gray-700 border {{ $errors->has('kelayakan') ? 'border-red-500' : 'border-gray-600' }} text-white rounded px-2 py-1"
                                    oninput="
                                        if(this.value > 100) this.value = 100;
                                        if(this.value < 0) this.value = 0;
                                        hitungNilai();
                                    "
                                    required>
                                @error('kelayakan')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </td>
                            <td class="px-4 py-3">
                                <span id="nilai_kelayakan" class="font-bold">0</span>
                            </td>
                        </tr>

                        {{-- Kriteria 5 : Kelayakan Penelitian --}}
                        <tr class="border-b border-gray-700">
                            <td class="px-4 py-3">5.</td>
                            <td class="px-4 py-3">
                                <strong>Kelayakan penelitian</strong>
                                <ul class="text-xs text-gray-400 mt-1">
                                    <li>• Kesesuaian penelitian</li>
                                    <li>• Kesesuaian biaya</li>
                                    <li>• Kesesuaian personalia</li>
                                </ul>
                            </td>
                            <td class="px-4 py-3">10</td>
                            <td class="px-4 py-3">
                                <input 
                                    type="number" 
                                    name="kelayakan_penelitian" 
                                    min="0" 
                                    max="100" 
                                    step="1"
                                    value="{{ old('kelayakan_penelitian', $review->scores['kelayakan_penelitian'] ?? '') }}"
                                    class="w-20 bg-gray-700 border {{ $errors->has('kelayakan_penelitian') ? 'border-red-500' : 'border-gray-600' }} text-white rounded px-2 py-1"
                                    oninput="
                                        if(this.value > 100) this.value = 100;
                                        if(this.value < 0) this.value = 0;
                                        hitungNilai();
                                    "
                                    required>
                                @error('kelayakan_penelitian')
                                    <p class="text-xs text-red-400 mt-1">{{ $message }}</p>
                                @enderror
                            </td>
                            <td class="px-4 py-3">
                                <span id="nilai_kelayakan_penelitian" class="font-bold">0</span>
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
                               {{ old('recommendation', $review->recommendation ?? '') == 'setuju' ? 'checked' : '' }}
                               class="w-4 h-4 text-green-600" required>
                        <span class="ml-2 text-white">✅ Setuju </span>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input type="radio" name="recommendation" value="tidak_setuju"
                               {{ old('recommendation', $review->recommendation ?? '') == 'tidak_setuju' ? 'checked' : '' }}
                               class="w-4 h-4 text-red-600" required>
                        <span class="ml-2 text-white">❌ Tidak Setuju </span>
                    </label>
                </div>
                @error('recommendation')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Catatan --}}
            <div>
                <label for="comment" class="block text-sm font-medium text-gray-300 mb-2">
                    Catatan/Feedback <span class="text-gray-500">(Opsional)</span>
                </label>
                <textarea name="comment" id="comment" rows="6" 
                          maxlength="5000"
                          class="block w-full bg-gray-700 border {{ $errors->has('comment') ? 'border-red-500' : 'border-gray-600' }} text-white rounded-md px-3 py-2"
                          placeholder="Berikan catatan atau saran untuk perbaikan...">{{ old('comment', $review->comment ?? '') }}</textarea>
                @error('comment')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-400">Maksimal 5000 karakter</p>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('proposals.browse') }}" 
                class="px-6 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    💾 Simpan Review
                </button>
            </div>
        </form>
    </div>

    {{-- JavaScript hitung nilai otomatis --}}
    <script>
        function hitungNilai() {
        const pendahuluan = parseFloat(document.querySelector('[name="pendahuluan"]').value) || 0;
        const tinjauan = parseFloat(document.querySelector('[name="tinjauan_pustaka"]').value) || 0;
        const metodologi = parseFloat(document.querySelector('[name="metodologi"]').value) || 0;
        const kelayakan = parseFloat(document.querySelector('[name="kelayakan"]').value) || 0;
        const kelayakanPenelitian = parseFloat(document.querySelector('[name="kelayakan_penelitian"]').value) || 0;

        // Hitung nilai (bobot 20% untuk setiap kriteria)
        const nilaiPendahuluan = pendahuluan * 0.25;
        const nilaiTinjauan = tinjauan * 0.25;
        const nilaiMetodologi = metodologi * 0.25;
        const nilaiKelayakan = kelayakan * 0.15;
        const nilaiKelayakanPenelitian = kelayakanPenelitian * 0.10;

        // Update tampilan
        document.getElementById('nilai_pendahuluan').textContent = nilaiPendahuluan.toFixed(2);
        document.getElementById('nilai_tinjauan').textContent = nilaiTinjauan.toFixed(2);
        document.getElementById('nilai_metodologi').textContent = nilaiMetodologi.toFixed(2);
        document.getElementById('nilai_kelayakan').textContent = nilaiKelayakan.toFixed(2);
        document.getElementById('nilai_kelayakan_penelitian').textContent = nilaiKelayakanPenelitian.toFixed(2);

        // Total
        const total = nilaiPendahuluan + nilaiTinjauan + nilaiMetodologi + nilaiKelayakan + nilaiKelayakanPenelitian;
        document.getElementById('total_nilai').textContent = total.toFixed(2);
        }

        // Event listener untuk semua input
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('input', hitungNilai);
        });

        // Hitung saat pertama load (kalau ada data lama)
        document.addEventListener('DOMContentLoaded', hitungNilai);
    </script>
</x-layout>