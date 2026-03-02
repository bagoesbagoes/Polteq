<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="mx-auto max-w-4xl px-4 py-6">
        
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

        {{-- Form --}}
        <form method="POST" action="{{ route('pkm.update', $pkm) }}" enctype="multipart/form-data" 
              class="bg-gray-800 shadow sm:rounded-lg p-6" 
              x-data="{ anggotaCount: {{ $pkm->anggota_tim ? count($pkm->anggota_tim) : 0 }} }">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                
                {{-- Header --}}
                <div class="border-b border-gray-700 pb-4">
                    <h2 class="text-xl font-semibold text-white">Edit Usulan PKM</h2>
                    <p class="mt-1 text-sm text-gray-400">Update informasi usulan PKM Anda</p>
                </div>

                {{-- Judul --}}
                <div>
                    <label for="judul" class="block text-sm font-medium text-white">
                        Judul PKM <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $pkm->judul) }}" required
                           class="mt-2 block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                </div>

                {{-- Tahun & Sumber Dana --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="tahun_pelaksanaan" class="block text-sm font-medium text-white">
                            Tahun Pelaksanaan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="tahun_pelaksanaan" id="tahun_pelaksanaan" 
                               value="{{ old('tahun_pelaksanaan', $pkm->tahun_pelaksanaan) }}" min="2020" max="2030" required
                               class="mt-2 block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                    </div>
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
                                <input type="text" name="anggota_tim[{{ $index }}]" value="{{ $anggota }}" placeholder="Nama Anggota"
                                       class="flex-1 rounded-md border-0 bg-gray-700 px-3 py-2 text-white ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                            </div>
                        @endforeach
                    @endif
                    
                    {{-- Dynamic Anggota --}}
                    <template x-for="i in anggotaCount - {{ $pkm->anggota_tim ? count($pkm->anggota_tim) : 0 }}" :key="i">
                        <div class="flex gap-2 mt-2">
                            <input type="text" :name="'anggota_tim[' + ({{ $pkm->anggota_tim ? count($pkm->anggota_tim) : 0 }} + i - 1) + ']'" placeholder="Nama Anggota"
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
                        Upload File Usulan <span class="text-gray-400">(Opsional - kosongkan jika tidak ingin mengubah file)</span>
                    </label>
                    <input type="file" name="file_usulan" id="file_usulan" accept=".pdf"
                           class="mt-2 block w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
                    <p class="mt-2 text-sm text-gray-400">File saat ini: {{ basename($pkm->file_usulan) }} ({{ $pkm->file_size_human }})</p>
                    <p class="text-sm text-gray-400">Format: PDF. Maksimal: 10MB</p>
                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('pkm.show', $pkm) }}" 
                   class="rounded-md bg-gray-700 px-4 py-2 text-sm font-medium text-white hover:bg-gray-600 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 transition">
                    💾 Update PKM
                </button>
            </div>
        </form>
    </div>
</x-layout>