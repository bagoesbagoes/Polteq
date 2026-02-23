<x-layout>
    <x-slot:title>
        Daftar usulan PKM
    </x-slot:title>

    <div class="mx-auto max-7xl px-4 sm:px-6 lg:px-8 py-6">

        {{-- header --}}
        <div class="flex justify-between items-center mb-6">
            @if (Auth::user() === 'publisher')
                <a href="{{ route('pkm.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                Buat Usulan PKM
                </a>
            @endif
        </div>

        {{-- Pesan success --}}
        @if (session('success'))
            <div x-data="{show: true}" x=show="show" x-init="setTimeout(() => show = false, 5000)"
                class="mb-6 rounded-md bg-green-900/20 p-4 border border-green-700">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-green-300">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- grid untuk pkm --}}
        @if ($pkms->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-300">Belum ada usulan PKM</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat usulan PKM baru.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($pkms as $pkm)
                    <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 hover:border-indigo-500 transition-all duration-200">
                        <div class="p-6">
                            {{-- Status dari PKM --}}
                            <div class="mb-3">
                                {!! $pkm->status_badge !!}
                            </div>

                            {{-- Judul PKM --}}
                            <h3 class="text-lg font-semibold text-white mb-2 line-clamp-2">
                                {{ $pkm->judul }}
                            </h3>

                            {{-- penulis dan tahun pembuatan pkm --}}
                            <div class="space-y-2 mb-4">
                                <p class="text-sm text-gray-400 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    {{ $pkm->author->name }}
                                </p>
                                <p class="text-sm text-gray-400 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Tahun : {{ $pkm->tahun_pelaksanaan }}
                                </p>
                            </div>

                            {{-- Abstrak Review --}}
                            <p class="text-sm text-gray-300 mb-4 line-clamp-3">
                                {{ Str::limit($pkm->abstrak, 120) }}
                            </p>

                            {{-- Action Button --}}
                            <a  href="{{ route('pkm.show', $pkm) }}" 
                                class="block w-full text-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $pkms->links() }}
            </div>
        @endif
    </div>
</x-layout>