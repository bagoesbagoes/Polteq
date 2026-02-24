<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
        
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-white">{{ $title }}</h1>
            <p class="text-gray-400 text-sm mt-1">PKM Anda yang telah disetujui oleh reviewer</p>
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
        @endif>

        {{-- PKM Grid --}}
        @if($pkms->isEmpty())
            <div class="text-center py-12 bg-gray-800 rounded-lg border border-gray-700">
                <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-300">Belum ada PKM yang disetujui</h3>
                <p class="mt-1 text-sm text-gray-500">PKM yang disetujui reviewer akan muncul di sini.</p>
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
                    <div class="bg-gray-800 rounded-lg shadow-lg border border-green-700 hover:border-green-500 transition-all duration-200">
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
                                <p class="text-sm text-gray-400 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Disetujui: {{ $pkm->updated_at->format('d M Y') }}
                                </p>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex gap-2">
                                <a href="{{ route('pkm.show', $pkm) }}" 
                                   class="flex-1 text-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                                    Lihat Detail
                                </a>
                                <a href="{{ route('pkm.download-surat-tugas', $pkm) }}" 
                                   class="flex-1 text-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-md hover:bg-purple-700 transition">
                                    Surat Tugas
                                </a>
                            </div>
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