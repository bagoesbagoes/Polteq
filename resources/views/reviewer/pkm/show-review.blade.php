<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="mx-auto max-w-5xl px-4 py-6">
        
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

        {{-- Main Card --}}
        <div class="bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-700">
            
            {{-- Header --}}
            <div class="bg-linear-to-r from-blue-900 to-blue-800 px-6 py-8 border-b border-gray-700">
                <h1 class="text-3xl font-bold text-white">Review Detail</h1>
                <p class="text-blue-200 mt-2">Review untuk PKM: {{ $review->pkmProposal->judul }}</p>
            </div>

            {{-- Content --}}
            <div class="p-6 space-y-6">

                {{-- PKM Info --}}
                <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-300 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Informasi PKM
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-400">Judul</p>
                            <p class="text-white font-medium">{{ $review->pkmProposal->judul }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Pengusul</p>
                            <p class="text-white font-medium">{{ $review->pkmProposal->author->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Tahun Pelaksanaan</p>
                            <p class="text-white">{{ $review->pkmProposal->tahun_pelaksanaan }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Status PKM</p>
                            <div class="mt-1">{!! $review->pkmProposal->status_badge !!}</div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('pkm.show', $review->pkmProposal) }}" 
                           class="inline-flex items-center text-sm text-indigo-400 hover:text-indigo-300">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat Detail PKM
                        </a>
                    </div>
                </div>

                {{-- Review Info --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-300 mb-2">Score</h3>
                        <p class="text-3xl font-bold text-white">{{ $review->score }}</p>
                        <p class="text-xs text-gray-400 mt-1">dari 100</p>
                    </div>

                    <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-300 mb-2">Rekomendasi</h3>
                        @if($review->recommendation === 'accept')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-600 text-white">
                                ✅ Accept
                            </span>
                        @elseif($review->recommendation === 'revise')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-600 text-white">
                                📝 Revise
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-600 text-white">
                                ❌ Reject
                            </span>
                        @endif
                    </div>

                    <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-300 mb-2">Tanggal Review</h3>
                        <p class="text-white font-medium">{{ $review->created_at->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->format('H:i') }} WIB</p>
                    </div>
                </div>

                {{-- Comments --}}
                <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-300 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        Komentar & Catatan
                    </h3>
                    <p class="text-gray-300 leading-relaxed whitespace-pre-line">{{ $review->comments }}</p>
                </div>

            </div>

            {{-- Footer Actions --}}
            <div class="bg-gray-900/50 px-6 py-4 border-t border-gray-700 flex justify-between items-center">
                <div class="flex gap-3">
                    <a href="{{ route('reviewer.pkm-edit-review', $review) }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Review
                    </a>
                </div>

                <form method="POST" action="{{ route('reviewer.pkm-delete-review', $review) }}" 
                      onsubmit="return confirm('⚠️ Yakin ingin menghapus review ini? Tindakan ini tidak dapat dibatalkan!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Review
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-layout>