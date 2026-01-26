{{-- resources/views/proposals/index.blade.php --}}
<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
        
    @if ($proposals->count() > 0)
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('proposals.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                Buat pengajuan Usulan Baru
            </a>
        </div>
    @endif
    
    @if ($proposals->count() > 0)
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-700 bg-gray-800">
                <thead class="bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Judul</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Dibuat</th>
                        <th scope="col" class="px-6 py-3 text-right text-sm font-semibold text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 bg-gray-800">
                    @foreach ($proposals as $proposal)
                        <tr class="hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 text-sm text-gray-200">
                                <div class="max-w-md truncate" title="{{ $proposal->judul }}">
                                    {{ $proposal->judul }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-200">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ App\Helpers\ProposalHelper::statusColor($proposal->status) }}">
                                    {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">
                                {{ $proposal->created_at->format('d M Y') }}
                            </td>
                            
                            {{-- ✅ FIXED: Action Buttons dengan Flexbox --}}
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center justify-end gap-3">
                                    
                                    {{-- Tombol Lihat (Selalu ada) --}}
                                    <a href="{{ route('proposals.show', $proposal) }}" 
                                       class="text-indigo-400 hover:text-indigo-300 font-medium transition">
                                        Lihat
                                    </a>
                                    
                                    {{-- Tombol Edit & Hapus (Hanya untuk status draft) --}}
                                    @if($proposal->status === 'draft')
                                        <span class="text-gray-600">|</span>
                                        
                                        <a href="{{ route('proposals.edit', $proposal) }}" 
                                           class="text-blue-400 hover:text-blue-300 font-medium transition">
                                            Edit
                                        </a>
                                        
                                        <span class="text-gray-600">|</span>
                                        
                                        <form method="POST" 
                                              action="{{ route('proposals.destroy', $proposal) }}" 
                                              class="inline-block"
                                              onsubmit="return confirm('Yakin ingin menghapus usulan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-400 hover:text-red-300 font-medium transition">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                    
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $proposals->links() }}
        </div>
    @else
        <div class="text-center py-12 bg-gray-800 rounded-lg border border-gray-700">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            
            @if(request()->routeIs('proposals.accepted'))
                <h3 class="mt-2 text-lg font-medium text-white">Belum Ada Usulan Disetujui</h3>
                <p class="mt-1 text-gray-400">Usulan Anda yang telah disetujui reviewer akan muncul di sini.</p>
                <div class="mt-6">
                    <a href="{{ route('proposals.index') }}" class="text-indigo-400 hover:text-indigo-300">
                        Lihat Semua Usulan →
                    </a>
                </div>
            @elseif (request()->routeIs('proposals.revisions'))
                <h3 class="mt-2 text-lg font-medium text-white">Belum Ada Usulan yang perlu perbaikan</h3>
                <p class="mt-1 text-gray-400">Usulan Anda yang membutuhkan perbaikan akan muncul di sini.</p>
                <div class="mt-6">
                    <a href="{{ route('proposals.index') }}" class="text-indigo-400 hover:text-indigo-300">
                        Lihat Semua Usulan →
                    </a>
                </div>
            @else
                <h3 class="mt-2 text-lg font-medium text-white">Belum Ada usulan</h3>
                <p class="mt-1 text-gray-400">Mulai buat pengajuan usulan penelitian Anda.</p>
                <div class="mt-6">
                    <a href="{{ route('proposals.create') }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Buat usulan Baru
                    </a>
                </div>
            @endif
        </div>
    @endif
</x-layout>