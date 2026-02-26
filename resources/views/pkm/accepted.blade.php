{{-- resources/views/pkm/accepted.blade.php --}}
<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
        
    @if ($pkms->count() > 0)
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('pkm.create') }}" class="rounded-md bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                Buat PKM Baru
            </a>
        </div>
    @endif
    
    @if ($pkms->count() > 0)
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-700 bg-gray-800">
                <thead class="bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Judul</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Kategori</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Tahun</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Disetujui</th>
                        <th scope="col" class="px-6 py-3 text-right text-sm font-semibold text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 bg-gray-800">
                    @foreach ($pkms as $pkm)
                        <tr class="hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 text-sm text-gray-200">
                                <div class="max-w-md truncate" title="{{ $pkm->judul }}">
                                    {{ $pkm->judul }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-200">
                                <div class="max-w-xs truncate" title="{{ $pkm->kategori_pkm }}">
                                    {{ $pkm->kategori_pkm }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-200 whitespace-nowrap">
                                {{ $pkm->tahun_pelaksanaan }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-200">
                                {!! $pkm->status_badge !!}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">
                                {{ $pkm->updated_at->format('d M Y') }}
                            </td>
                            
                            {{-- Action Buttons --}}
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center justify-end gap-3">
                                    
                                    {{-- Tombol Lihat --}}
                                    <a href="{{ route('pkm.show', $pkm) }}" 
                                       class="text-indigo-400 hover:text-indigo-300 font-medium transition">
                                        Lihat
                                    </a>
                                    
                                    <span class="text-gray-600">|</span>
                                    
                                    {{-- Tombol Download Surat Tugas --}}
                                    <a href="{{ route('pkm.download-surat-tugas', $pkm) }}" 
                                       class="text-green-400 hover:text-green-300 font-medium transition">
                                        Surat Tugas
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $pkms->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-gray-800 rounded-lg border border-gray-700">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            
            <h3 class="mt-2 text-lg font-medium text-white">Belum Ada PKM Disetujui</h3>
            <p class="mt-1 text-gray-400">PKM Anda yang telah disetujui reviewer akan muncul di sini.</p>
            <div class="mt-6">
                <a href="{{ route('pkm.index') }}" class="text-indigo-400 hover:text-indigo-300">
                    Lihat Semua PKM →
                </a>
            </div>
        </div>
    @endif
</x-layout>