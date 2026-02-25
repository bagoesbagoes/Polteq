<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
        
        {{-- Header dengan Button Buat PKM --}}
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $title }}</h1>
                <p class="text-gray-400 text-sm mt-1">Kelola semua usulan PKM Anda</p>
            </div>
            <a href="{{ route('pkm.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Buat PKM Baru
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

        {{-- PKM Grid --}}
        @if($pkms->isEmpty())
            <div class="text-center py-16 bg-gray-800 rounded-lg border border-gray-700">
                <svg class="mx-auto h-16 w-16 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-300">Belum ada usulan PKM</h3>
                <p class="mt-2 text-sm text-gray-500">Mulai dengan membuat usulan PKM baru untuk program pengabdian kepada masyarakat.</p>
                <div class="mt-8">
                    <a href="{{ route('pkm.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 transition shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Buat PKM Baru
                    </a>
                </div>
            </div>
        @else
            {{-- Stats Summary --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                @php
                    $draftCount = $pkms->where('status', 'draft')->count();
                    $submittedCount = $pkms->where('status', 'submitted')->count();
                    $acceptedCount = $pkms->where('status', 'accepted')->count();
                    $revisionCount = $pkms->where('status', 'need_revision')->count();
                @endphp

                <div class="bg-gray-800 rounded-lg p-4 border border-gray-700">
                    <p class="text-gray-400 text-sm">Draft</p>
                    <p class="text-2xl font-bold text-gray-300 mt-1">{{ $draftCount }}</p>
                </div>
                <div class="bg-gray-800 rounded-lg p-4 border border-blue-700">
                    <p class="text-blue-400 text-sm">Submitted</p>
                    <p class="text-2xl font-bold text-blue-300 mt-1">{{ $submittedCount }}</p>
                </div>
                <div class="bg-gray-800 rounded-lg p-4 border border-green-700">
                    <p class="text-green-400 text-sm">Accepted</p>
                    <p class="text-2xl font-bold text-green-300 mt-1">{{ $acceptedCount }}</p>
                </div>
                <div class="bg-gray-800 rounded-lg p-4 border border-yellow-700">
                    <p class="text-yellow-400 text-sm">Need Revision</p>
                    <p class="text-2xl font-bold text-yellow-300 mt-1">{{ $revisionCount }}</p>
                </div>
            </div>

            {{-- PKM Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($pkms as $pkm)
                    <article class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 hover:border-indigo-500 transition-all duration-200 overflow-hidden">
                        
                        {{-- Header Card --}}
                        <div class="p-6 pb-4">
                            {{-- Status Badge --}}
                            <div class="mb-3">
                                {!! $pkm->status_badge !!}
                            </div>

                            {{-- Title --}}
                            <a href="{{ route('pkm.show', $pkm) }}" 
                               class="block text-lg font-semibold text-white hover:text-indigo-400 transition mb-2 line-clamp-2">
                                {{ $pkm->judul }}
                            </a>

                            {{-- Meta Info --}}
                            <div class="space-y-2 text-sm text-gray-400">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Tahun: {{ $pkm->tahun_pelaksanaan }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    <span class="line-clamp-1">{{ $pkm->kategori_pkm }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                    <span>{{ $pkm->sumber_dana }}</span>
                                </div>
                            </div>

                            {{-- Abstrak Preview --}}
                            <p class="mt-3 text-sm text-gray-300 line-clamp-3">
                                {{ $pkm->abstrak }}
                            </p>
                        </div>

                        {{-- Footer Card --}}
                        <div class="px-6 py-4 bg-gray-900/50 border-t border-gray-700 flex items-center justify-between">
                            <div class="flex items-center text-xs text-gray-400">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ $pkm->created_at->diffForHumans() }}
                            </div>
                            <a href="{{ route('pkm.show', $pkm) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded hover:bg-indigo-700 transition">
                                Lihat Detail
                                <svg class="w-3 h-3 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>

                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $pkms->links() }}
            </div>
        @endif

    </div>
</x-layout>