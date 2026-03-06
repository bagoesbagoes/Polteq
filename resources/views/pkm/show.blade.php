<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="mx-auto max-w-5xl px-4 py-6">
        
        {{-- Back Button --}}
        <div class="mb-6">
            @if(Auth::user()->role === 'admin')
                <a href="{{ route('pkm.browse') }}" class="inline-flex items-center text-sm text-indigo-400 hover:text-indigo-300">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Browse PKM
                </a>
            @elseif(Auth::user()->role === 'reviewer')
                <a href="{{ route('reviewer.pkm') }}" class="inline-flex items-center text-sm text-indigo-400 hover:text-indigo-300">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Daftar Review PKM
                </a>
            @else
                <a href="{{ route('pkm.index') }}" class="inline-flex items-center text-sm text-indigo-400 hover:text-indigo-300">
                    <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke PKM Saya
                </a>
            @endif
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 class="mb-6 rounded-md bg-green-900/20 p-4 border border-green-700">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Error Message --}}
        @if (session('error'))
            <div x-data="{ show: true }" 
                x-show="show" 
                x-init="setTimeout(() => show = false, 5000)"
                x-transition:leave="transition ease-in duration-500"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="mb-4 flex items-center justify-between rounded-md bg-red-900/20 p-4 border border-red-700 text-red-400">
                
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>

                {{-- Tombol X --}}
                <button @click="show = false" class="ml-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8 hover:bg-red-900/30 transition">
                    <span class="sr-only">Close</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        @endif   
        
        {{-- Pesan revisi--}}
        @if($pkm->status === 'need_revision' && $pkm->user_id === Auth::id())
            <div class="mb-6 bg-yellow-900/20 border border-yellow-700 rounded-lg p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-400 mr-3 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-yellow-400">PKM Perlu Revisi</h3>
                        <div class="mt-4 flex gap-3">
                            {{-- Button Revisi - UPDATED ROUTE --}}
                            <a href="{{ route('pkm.revision.form', $pkm) }}" 
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-md hover:bg-yellow-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Revisi Sekarang
                            </a>
                            <a href="{{ route('pkm.revisions') }}" 
                            class="inline-flex items-center px-4 py-2 bg-gray-700 text-white text-sm font-medium rounded-md hover:bg-gray-600 transition">
                                Lihat Semua PKM Revisi
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Main Card --}}
        <div class="bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-700">
            
            {{-- Header --}}
            <div class="bg-linear-to-r from-green-900 to-green-800 px-6 py-8 border-b border-gray-700">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        {!! $pkm->status_badge !!}
                        <h1 class="text-3xl font-bold text-white mt-3">{{ $pkm->judul }}</h1>
                        <p class="text-green-200 mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Dibuat: {{ $pkm->created_at->format('d F Y, H:i') }} WIB
                        </p>
                    </div>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-6 space-y-6">

                {{-- Author Info --}}
                <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-300 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Pengusul
                    </h3>
                    <p class="text-white font-medium">{{ $pkm->author->name }}</p>
                    <p class="text-gray-400 text-sm mt-1">{{ $pkm->author->email }}</p>
                </div>

                {{-- Details Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-300 mb-2">Tahun Pelaksanaan</h3>
                        <p class="text-white">{{ $pkm->tahun_pelaksanaan }}</p>
                    </div>

                    @if($pkm->kelompok_riset)
                        <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-300 mb-2">Kelompok Riset</h3>
                            <p class="text-white">{{ $pkm->kelompok_riset }}</p>
                        </div>
                    @endif
                </div>

                {{-- Anggota Tim --}}
                @if($pkm->anggota_tim && count($pkm->anggota_tim) > 0)
                    <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-300 mb-3">Anggota Tim</h3>
                        <ul class="list-disc list-inside text-white space-y-1">
                            @foreach($pkm->anggota_tim as $anggota)
                                <li>{{ $anggota }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Abstrak --}}
                <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-300 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
                        </svg>
                        Abstrak
                    </h3>
                    <p class="text-gray-300 leading-relaxed whitespace-pre-line">{{ $pkm->abstrak }}</p>
                </div>

                {{-- File Info --}}
                <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-300 mb-3">File Usulan</h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white font-medium">{{ basename($pkm->file_usulan) }}</p>
                            <p class="text-gray-400 text-sm mt-1">Ukuran: {{ $pkm->file_size_human }}</p>
                        </div>
                        <a href="{{ route('pkm.download', $pkm) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>
                    </div>
                </div>

                {{-- Surat Tugas (if applicable) --}}
                @if($pkm->status === 'accepted')
                    <div class="mt-6 bg-green-900/20 border border-green-700 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-green-400 mr-3 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-green-400">PKM Disetujui</h3>
                                <p class="mt-1 text-sm text-green-300">
                                    PKM Anda telah disetujui! Anda dapat mengunduh Surat Tugas.
                                </p>
                                <div class="mt-3">
                                    <a href="{{ route('pkm.download-surat-tugas', $pkm) }}" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download Surat Tugas PKM
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Revision Notes --}}
                @if($pkm->status === 'need_revision' && $pkm->revision_notes)
                    <div class="bg-yellow-900/20 rounded-lg p-5 border border-yellow-700">
                        <h3 class="text-sm font-semibold text-yellow-300 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            Catatan Revisi
                        </h3>
                        <p class="text-yellow-100 whitespace-pre-line">{{ $pkm->revision_notes }}</p>
                    </div>
                @endif

                {{-- Reviews --}}
                @if($pkm->reviews->isNotEmpty())
                    <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-300 mb-4">Direview oleh </h3>
                        @foreach($pkm->reviews as $review)
                            <div class="border-t border-gray-700 pt-4 first:border-t-0 first:pt-0">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <p class="text-white font-medium">{{ $review->reviewer->name }}</p>
                                        <p class="text-gray-400 text-sm">{{ $review->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    @if($review->score)
                                        <span class="px-3 py-1 bg-indigo-600 text-white rounded-full text-sm font-semibold">
                                            Score: {{ $review->score }}
                                        </span>
                                    @endif
                                </div>
                                @if($review->recommendation)
                                    <p class="text-sm mb-2">
                                        <span class="font-semibold text-gray-300">Rekomendasi:</span>
                                        <span class="px-2 py-1 rounded text-xs {{ $review->recommendation === 'accept' ? 'bg-green-600 text-white' : 'bg-yellow-600 text-white' }}">
                                            {{ ucfirst($review->recommendation) }}
                                        </span>
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Footer Actions --}}
            <div class="px-6 py-5 border-t border-gray-700">
                <div class="flex justify-end gap-3">
                    
                    {{-- PUBLISHER DELETE BUTTON (ONLY for DRAFT) --}}
                    @if(Auth::user()->id === $pkm->user_id && $pkm->status === 'draft')
                        <form method="POST" 
                            action="{{ route('pkm.destroy', $pkm) }}" 
                            class="inline-block"
                            onsubmit="return confirm('⚠️ PERHATIAN!\n\nAnda akan menghapus PKM:\n\"{{ addslashes($pkm->judul) }}\"\n\nStatus: Draft\n\nData yang dihapus TIDAK DAPAT dikembalikan!\n\nLanjutkan menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus PKM
                            </button>
                        </form>
                    @endif

                    {{-- ADMIN DELETE (untuk semua status) --}}
                    @if(Auth::user()->role === 'admin')
                        <form method="POST" 
                            action="{{ route('pkm.destroy', $pkm) }}" 
                            class="inline-block"
                            onsubmit="return confirm('⚠️ PERHATIAN ADMIN!\n\nAnda akan menghapus PKM:\n\"{{ addslashes($pkm->judul) }}\"\n\nStatus: {{ ucfirst(str_replace('_', ' ', $pkm->status)) }}\n\n{{ $pkm->status === 'accepted' ? 'PKM INI SUDAH DISETUJUI!\n\n' : '' }}Data yang dihapus TIDAK DAPAT dikembalikan!\n\nLanjutkan menghapus?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 {{ $pkm->status === 'accepted' ? 'bg-red-700 border-2 border-red-500' : 'bg-red-600' }} text-white text-sm font-medium rounded-md hover:bg-red-800 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus PKM (Admin)
                            </button>
                        </form>
                    @endif

                    {{-- Existing buttons: Edit, Submit --}}
                    @if($pkm->status === 'draft' && $pkm->user_id === Auth::id())
                        <a href="{{ route('pkm.edit', $pkm) }}" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                            ✏️ Edit
                        </a>
                        <form method="POST" action="{{ route('pkm.submit', $pkm) }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Submit PKM untuk direview?')"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700">
                                📤 Submit untuk Review
                            </button>
                        </form>
                    @endif

                    {{-- Button Kembali --}}
                    <a href="{{ route('pkm.index') }}" 
                    class="inline-flex items-center px-4 py-2 bg-gray-700 text-white text-sm font-medium rounded-md hover:bg-gray-600 transition">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-layout>