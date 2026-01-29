<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="mx-auto max-w-4xl px-4 py-6">
        
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

        <form method="POST" 
              action="{{ $type === 'laporan_akhir' ? route('reports.store-laporan-akhir') : route('reports.store-luaran') }}" 
              enctype="multipart/form-data" 
              class="bg-gray-800 shadow sm:rounded-lg p-6"
              @if($type === 'luaran') x-data="{ luaranType: '{{ old('luaran_type', 'file') }}' }" @endif>
            @csrf
            
            <div class="space-y-6">
                
                {{-- Header --}}
                <div class="border-b border-gray-700 pb-4">
                    <h2 class="text-xl font-semibold text-white">{{ $title }}</h2>
                    <p class="mt-1 text-sm text-gray-400">
                        Upload {{ $type === 'laporan_akhir' ? 'laporan akhir penelitian' : 'luaran penelitian' }} Anda
                    </p>
                </div>

                {{-- Pilih Usulan --}}
                <div>
                    <label for="proposal_id" class="block text-sm font-medium leading-6 text-white">
                        Pilih Usulan <span class="text-red-500">*</span>
                    </label>
                    <select name="proposal_id" id="proposal_id" required 
                            class="mt-2 block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                        <option value="">-- Pilih Usulan yang Sudah Disetujui --</option>
                        @foreach($proposals as $proposal)
                            <option value="{{ $proposal->id }}" {{ old('proposal_id') == $proposal->id ? 'selected' : '' }}>
                                {{ $proposal->judul }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-400">Pilih usulan dengan status "Accepted"</p>
                </div>

                {{-- Judul --}}
                <div>
                    <label for="title" class="block text-sm font-medium leading-6 text-white">
                        Judul {{ $type === 'laporan_akhir' ? 'Laporan' : 'Luaran' }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title') }}" 
                           required 
                           class="mt-2 block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-500 sm:text-sm" 
                           placeholder="{{ $type === 'laporan_akhir' ? 'Contoh: Laporan Akhir Penelitian Machine Learning' : 'Contoh: Publikasi Jurnal Internasional Scopus' }}" />
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="block text-sm font-medium leading-6 text-white">
                        Deskripsi <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="4" 
                              class="mt-2 block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-500 sm:text-sm"
                              placeholder="Deskripsi singkat tentang {{ $type === 'laporan_akhir' ? 'laporan akhir' : 'luaran' }} ini...">{{ old('description') }}</textarea>
                </div>

                @if($type === 'luaran')
                    {{-- Tipe Luaran (File atau Hyperlink) --}}
                    <div>
                        <label class="block text-sm font-medium leading-6 text-white mb-3">
                            Tipe Luaran <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="flex gap-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" 
                                       name="luaran_type" 
                                       value="file" 
                                       x-model="luaranType"
                                       {{ old('luaran_type', 'file') === 'file' ? 'checked' : '' }}
                                       class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-white">📄 Upload File</span>
                            </label>
                            
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" 
                                       name="luaran_type" 
                                       value="link" 
                                       x-model="luaranType"
                                       {{ old('luaran_type') === 'link' ? 'checked' : '' }}
                                       class="w-4 h-4 text-green-600 focus:ring-green-500">
                                <span class="ml-2 text-white">🔗 Hyperlink URL</span>
                            </label>
                        </div>
                    </div>

                    {{-- Upload File (Show if type = file) --}}
                    <div x-show="luaranType === 'file'" x-transition>
                        <label for="file_upload" class="block text-sm font-medium leading-6 text-white">
                            Upload File <span class="text-red-500">*</span>
                        </label>
                        <input type="file" 
                               name="file_upload" 
                               id="file_upload" 
                               class="mt-2 block w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700" />
                        <p class="mt-2 text-sm text-gray-400">Format: Semua file. Maksimal: 10MB</p>
                    </div>

                    {{-- Hyperlink URL (Show if type = link) --}}
                    <div x-show="luaranType === 'link'" x-transition>
                        <label for="hyperlink" class="block text-sm font-medium leading-6 text-white">
                            URL Hyperlink <span class="text-red-500">*</span>
                        </label>
                        <input type="url" 
                               name="hyperlink" 
                               id="hyperlink" 
                               value="{{ old('hyperlink') }}" 
                               class="mt-2 block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-500 sm:text-sm" 
                               placeholder="https://example.com/luaran-penelitian" />
                        <p class="mt-2 text-sm text-gray-400">Contoh: Link ke jurnal, repositori, Google Drive, dll.</p>
                    </div>

                @else
                    {{-- Upload File PDF (Laporan Akhir) --}}
                    <div>
                        <label for="file_upload" class="block text-sm font-medium leading-6 text-white">
                            Upload File PDF <span class="text-red-500">*</span>
                        </label>
                        <input type="file" 
                               name="file_upload" 
                               id="file_upload" 
                               accept=".pdf" 
                               required 
                               class="mt-2 block w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700" />
                        <p class="mt-2 text-sm text-gray-400">Format: PDF. Maksimal: 10MB</p>
                    </div>
                @endif

            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('laporan_penelitian') }}" 
                   class="rounded-md bg-gray-700 px-4 py-2 text-sm font-medium text-white hover:bg-gray-600 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="rounded-md {{ $type === 'laporan_akhir' ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-green-600 hover:bg-green-700' }} px-4 py-2 text-sm font-medium text-white transition focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $type === 'laporan_akhir' ? 'focus:ring-indigo-500' : 'focus:ring-green-500' }}">
                    💾 Upload {{ $type === 'laporan_akhir' ? 'Laporan Akhir' : 'Luaran' }}
                </button>
            </div>
        </form>
    </div>
</x-layout>