<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="mx-auto max-w-7xl px-4 py-6">
        
        {{-- Header with Tabs --}}
        <div class="mb-6">
            {{-- Tab Navigation --}}
            <div class="border-b border-gray-700">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('admin.reports.laporan-akhir') }}" 
                       class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium {{ $type === 'laporan_akhir' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-gray-400 hover:border-gray-500 hover:text-gray-300' }}">
                        📄 Laporan Akhir ({{ \App\Models\Report::where('type', 'laporan_akhir')->count() }})
                    </a>
                    <a href="{{ route('admin.reports.luaran') }}" 
                       class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium {{ $type === 'luaran' ? 'border-green-500 text-green-400' : 'border-transparent text-gray-400 hover:border-gray-500 hover:text-gray-300' }}">
                        🎯 Luaran ({{ \App\Models\Report::where('type', 'luaran')->count() }})
                    </a>
                </nav>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-gray-800 rounded-lg shadow p-4 mb-6 border border-gray-700">
            <form method="GET" action="{{ $type === 'laporan_akhir' ? route('admin.reports.laporan-akhir') : route('admin.reports.luaran') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    
                    {{-- Search by Title --}}
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-300 mb-2">Cari Judul</label>
                        <input type="text" 
                               name="search" 
                               id="search" 
                               value="{{ request('search') }}"
                               placeholder="Cari berdasarkan judul..."
                               class="block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    {{-- Filter by Publisher --}}
                    <div>
                        <label for="publisher" class="block text-sm font-medium text-gray-300 mb-2">Filter Publisher</label>
                        <select name="publisher" 
                                id="publisher" 
                                class="block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white focus:ring-2 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Semua Publisher --</option>
                            @foreach($publishers as $publisher)
                                <option value="{{ $publisher->id }}" {{ request('publisher') == $publisher->id ? 'selected' : '' }}>
                                    {{ $publisher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex items-end gap-2">
                        <button type="submit" 
                                class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                            🔍 Filter
                        </button>
                        <a href="{{ $type === 'laporan_akhir' ? route('admin.reports.laporan-akhir') : route('admin.reports.luaran') }}" 
                           class="bg-gray-700 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div class="bg-gray-900 rounded-lg p-6 border border-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-200 text-sm font-medium">Total {{ $type === 'laporan_akhir' ? 'Laporan Akhir' : 'Luaran' }}</p>
                        <p class="text-white text-3xl font-bold mt-1">{{ $reports->total() }}</p>
                    </div>
                    <div class="bg-gray-800 rounded-full p-3">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            @if($type === 'luaran')
                <div class="bg-gray-900 rounded-lg p-6 border border-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-200 text-sm font-medium">Luaran File</p>
                            <p class="text-white text-3xl font-bold mt-1">
                                {{ \App\Models\Report::where('type', 'luaran')->where('luaran_type', 'file')->count() }}
                            </p>
                        </div>
                        <div class="bg-gray-800 rounded-full p-3">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-lg p-6 border border-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-200 text-sm font-medium">Luaran Link</p>
                            <p class="text-white text-3xl font-bold mt-1">
                                {{ \App\Models\Report::where('type', 'luaran')->where('luaran_type', 'link')->count() }}
                            </p>
                        </div>
                        <div class="bg-gray-800 rounded-full p-3">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                            </svg>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gray-900 rounded-lg p-6 border border-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-200 text-sm font-medium">Total Dosen</p>
                            <p class="text-white text-3xl font-bold mt-1">{{ $publishers->count() }}</p>
                        </div>
                        <div class="bg-gray-800 rounded-full p-3">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Table --}}
        @if($reports->count() > 0)
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-700 bg-gray-800">
                    <thead class="bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Judul & Usulan
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Publisher
                            </th>
                            @if($type === 'luaran')
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                    Tipe
                                </th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                Tanggal Upload
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-white uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 bg-gray-800">
                        @foreach($reports as $report)
                            <tr class="hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-white">
                                        {{ Str::limit($report->title, 40) }}
                                    </div>

                                    <div class="text-sm text-gray-400 mt-1"> 
                                        @if($report->proposal)
                                            {{ Str::limit($report->proposal->judul, 35) }}
                                        @else
                                            <span class="text-gray-500 italic">Tidak terkait usulan</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-white">{{ $report->author->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $report->author->email }}</div>
                                </td>
                                @if($type === 'luaran')
                                    <td class="px-6 py-4">
                                        @if($report->luaran_type === 'file')
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-900/30 text-blue-300 rounded">
                                                {{ $report->file_icon }} File
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-green-900/30 text-green-300 rounded">
                                                🔗 Link
                                            </span>
                                        @endif
                                    </td>
                                @endif
                                <td class="px-6 py-4 text-sm text-gray-400">
                                    {{ $report->created_at->format('d M Y') }}
                                    <div class="text-xs text-gray-500">{{ $report->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('reports.show', ['type' => $type, 'report' => $report]) }}" 
                                           class="text-indigo-400 hover:text-indigo-300">
                                            Detail
                                        </a>
                                        
                                        @if($report->file_path)
                                            <span class="text-gray-600">|</span>
                                            <a href="{{ route('reports.download', ['type' => $type, 'report' => $report]) }}" 
                                               class="text-blue-400 hover:text-blue-300">
                                                Download
                                            </a>
                                        @endif
                                        
                                        <span class="text-gray-600">|</span>
                                        
                                        <form method="POST" 
                                              action="{{ route('reports.destroy', ['type' => $type, 'report' => $report]) }}" 
                                              class="inline-block"
                                              onsubmit="return confirm('⚠️ Yakin ingin menghapus {{ $type === 'laporan_akhir' ? 'laporan akhir' : 'luaran' }} ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300">
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
                {{ $reports->links() }}
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-12 bg-gray-800 rounded-lg border border-gray-700">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-lg font-medium text-white">Belum Ada Data</h3>
                <p class="mt-1 text-gray-400">
                    {{ request()->has('search') || request()->has('publisher') 
                        ? 'Tidak ada hasil dengan filter yang dipilih.' 
                        : 'Belum ada ' . ($type === 'laporan_akhir' ? 'laporan akhir' : 'luaran') . ' yang diupload.'
                    }}
                </p>
                @if(request()->has('search') || request()->has('publisher'))
                    <div class="mt-6">
                        <a href="{{ $type === 'laporan_akhir' ? route('admin.reports.laporan-akhir') : route('admin.reports.luaran') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600">
                            Reset Filter
                        </a>
                    </div>
                @endif
            </div>
        @endif

    </div>
</x-layout>
