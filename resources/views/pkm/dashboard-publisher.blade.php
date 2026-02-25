<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
        
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('pkm.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat PKM Baru
            </a>
        </div>
        
        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Terkirim (Draft + Submitted) --}}
            <div class="bg-linear-to-br from-blue-900 to-blue-800 rounded-lg shadow-lg p-6 border border-blue-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-200 text-sm font-medium">PKM Terkirim</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $stats['terkirim'] }}</p>
                        <p class="text-blue-300 text-xs mt-1">Draft & Submitted</p>
                    </div>
                    <div class="bg-blue-700/30 rounded-full p-3">
                        <svg class="w-8 h-8 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('pkm.index') }}" 
                   class="mt-4 inline-flex items-center text-sm text-blue-200 hover:text-white transition">
                    Lihat semua
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Disetujui --}}
            <div class="bg-linear-to-br from-green-900 to-green-800 rounded-lg shadow-lg p-6 border border-green-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-200 text-sm font-medium">PKM Disetujui</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $stats['disetujui'] }}</p>
                        <p class="text-green-300 text-xs mt-1">Accepted</p>
                    </div>
                    <div class="bg-green-700/30 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('pkm.accepted') }}" 
                   class="mt-4 inline-flex items-center text-sm text-green-200 hover:text-white transition">
                    Lihat semua
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            {{-- Revisi --}}
            <div class="bg-linear-to-br from-yellow-900 to-yellow-800 rounded-lg shadow-lg p-6 border border-yellow-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-200 text-sm font-medium">Revisi PKM</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $stats['revisi'] }}</p>
                        <p class="text-yellow-300 text-xs mt-1">Need Revision</p>
                    </div>
                    <div class="bg-yellow-700/30 rounded-full p-3">
                        <svg class="w-8 h-8 text-yellow-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                </div>
                <a href="{{ route('pkm.revisions') }}" 
                   class="mt-4 inline-flex items-center text-sm text-yellow-200 hover:text-white transition">
                    Lihat semua
                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Recent PKM --}}
        <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-white">PKM Terbaru</h2>
                <a href="{{ route('pkm.index') }}" class="text-sm text-indigo-400 hover:text-indigo-300">
                    Lihat Semua →
                </a>
            </div>

            @if($pkms->isEmpty())
                <div class="text-center py-12 bg-gray-800 rounded-lg border border-gray-700">
                    <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-300">Belum ada usulan PKM</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat usulan PKM baru.</p>
                    <div class="mt-6">
                        <a href="{{ route('pkm.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Buat PKM Baru
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($pkms as $pkm)
                        <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 hover:border-indigo-500 transition-all duration-200">
                            <div class="p-6">
                                {{-- Status Badge --}}
                                <div class="mb-3">
                                    {!! $pkm->status_badge !!}
                                </div>

                                {{-- Title --}}
                                <h3 class="text-lg font-semibold text-white mb-2 line-clamp-2">
                                    {{ $pkm->judul }}
                                </h3>

                                {{-- Info --}}
                                <div class="space-y-2 mb-4">
                                    <p class="text-sm text-gray-400 flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Tahun: {{ $pkm->tahun_pelaksanaan }}
                                    </p>
                                    <p class="text-sm text-gray-400">
                                        {{ Str::limit($pkm->kategori_pkm, 30) }}
                                    </p>
                                </div>

                                {{-- Action Button --}}
                                <a href="{{ route('pkm.show', $pkm) }}" 
                                   class="block w-full text-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-layout>