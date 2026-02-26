<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
        
        {{-- Header --}}
        <div class="mb-6">
            <p class="text-gray-400 text-sm mt-1">Kelola dan pantau semua usulan PKM dari dosen</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            {{-- Total PKM --}}
            <div class="bg-linear-to-br from-indigo-900 to-indigo-800 rounded-lg shadow-lg p-6 border border-indigo-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-200 text-sm font-medium">Total PKM</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $stats['total'] }}</p>
                        <p class="text-indigo-300 text-xs mt-1">Semua Status</p>
                    </div>
                    <div class="bg-indigo-700/30 rounded-full p-3">
                        <svg class="w-8 h-8 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Submitted --}}
            <div class="bg-linear-to-br from-blue-900 to-blue-800 rounded-lg shadow-lg p-6 border border-blue-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-200 text-sm font-medium">Submitted</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $stats['submitted'] }}</p>
                        <p class="text-blue-300 text-xs mt-1">Menunggu Review</p>
                    </div>
                    <div class="bg-blue-700/30 rounded-full p-3">
                        <svg class="w-8 h-8 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Accepted --}}
            <div class="bg-linear-to-br from-green-900 to-green-800 rounded-lg shadow-lg p-6 border border-green-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-200 text-sm font-medium">Disetujui</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $stats['accepted'] }}</p>
                        <p class="text-green-300 text-xs mt-1">Accepted</p>
                    </div>
                    <div class="bg-green-700/30 rounded-full p-3">
                        <svg class="w-8 h-8 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Revisi --}}
            <div class="bg-linear-to-br from-yellow-900 to-yellow-800 rounded-lg shadow-lg p-6 border border-yellow-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-200 text-sm font-medium">Revisi</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $stats['revisi'] }}</p>
                        <p class="text-yellow-300 text-xs mt-1">Need Revision</p>
                    </div>
                    <div class="bg-yellow-700/30 rounded-full p-3">
                        <svg class="w-8 h-8 text-yellow-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search & Filter Bar --}}
        <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-4 mb-6">
            <form method="GET" action="{{ route('admin.pkm') }}">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                    
                    {{-- Search --}}
                    <div class="lg:col-span-2">
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="🔍 Cari judul atau nama dosen..." 
                               class="w-full bg-gray-700 border border-gray-600 text-white rounded-md px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>

                    {{-- Filter Status --}}
                    <select name="status" 
                            class="bg-gray-700 border border-gray-600 text-white rounded-md px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Status</option>
                        <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>📤 Submitted</option>
                        <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>✅ Accepted</option>
                        <option value="need_revision" {{ request('status') === 'need_revision' ? 'selected' : '' }}>📝 Need Revision</option>
                    </select>

                    {{-- Filter Dosen --}}
                    <select name="author" 
                            class="bg-gray-700 border border-gray-600 text-white rounded-md px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Dosen</option>
                        @foreach($publishers as $publisher)
                            <option value="{{ $publisher->id }}" {{ request('author') == $publisher->id ? 'selected' : '' }}>
                                {{ $publisher->name }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Filter Tahun --}}
                    <select name="tahun" 
                            class="bg-gray-700 border border-gray-600 text-white rounded-md px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Tahun</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Filter Kategori --}}
                    <select name="kategori" 
                            class="bg-gray-700 border border-gray-600 text-white rounded-md px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('kategori') === $category ? 'selected' : '' }}>
                                {{ Str::limit($category, 25) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3 mt-4">
                    <button type="submit" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition text-sm">
                        🔍 Filter
                    </button>
                    <a href="{{ route('admin.pkm') }}" 
                       class="px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600 transition text-sm">
                        🔄 Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- PKM Table --}}
        @if($pkms->isEmpty())
            <div class="text-center py-12 bg-gray-800 rounded-lg border border-gray-700">
                <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-300">Tidak ada PKM ditemukan</h3>
                <p class="mt-1 text-sm text-gray-500">Coba ubah filter atau pencarian Anda.</p>
            </div>
        @else
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-700 bg-gray-800">
                    <thead class="bg-gray-900">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Judul</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Dosen</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Tahun</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Dibuat</th>
                            <th scope="col" class="px-6 py-3 text-right text-sm font-semibold text-white">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 bg-gray-800">
                        @foreach($pkms as $pkm)
                            <tr class="hover:bg-gray-700/50 transition">
                                <td class="px-3 py-4 text-sm text-gray-200">
                                    <div class="max-w-xs truncate" title="{{ $pkm->judul }}">
                                        {{ $pkm->judul }}
                                    </div>
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-200 items-center">
                                    {{ $pkm->author->name }}
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-200">
                                    <div class="max-w-xs truncate" title="{{ $pkm->kategori_pkm }}">
                                        {{ $pkm->kategori_pkm }}
                                    </div>
                                </td>
                                <td class="px-7 py-4 text-sm text-gray-200 whitespace-nowrap">
                                    {{ $pkm->tahun_pelaksanaan }}
                                </td>
                                <td class="px-3 py-4 text-sm">
                                    {!! $pkm->status_badge !!}
                                </td>
                                <td class="px-3 py-4 text-sm text-gray-400 whitespace-nowrap">
                                    {{ $pkm->created_at->format('d M Y') }}
                                </td>
                                <td class="px-3 py-4 text-sm items-center">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('pkm.show', $pkm) }}" 
                                           class="text-indigo-400 hover:text-indigo-300 font-medium transition">
                                            Lihat
                                        </a>
                                        <span class="text-gray-600">|</span>
                                        <a href="{{ route('pkm.edit', $pkm) }}" 
                                           class="text-blue-400 hover:text-blue-300 font-medium transition">
                                            Edit
                                        </a>
                                        <span class="text-gray-600">|</span>
                                        <form method="POST" 
                                              action="{{ route('pkm.destroy', $pkm) }}" 
                                              class="inline-block"
                                              onsubmit="return confirm('Yakin ingin menghapus PKM ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-400 hover:text-red-300 font-medium transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $pkms->links() }}
            </div>
        @endif

    </div>
</x-layout>