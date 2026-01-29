<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    {{-- Success Message --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 rounded-md bg-green-900/20 p-4 border border-green-700">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm font-medium text-green-400">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Header with Create Button --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">{{ $title }}</h2>
        
        @if($type === 'laporan_akhir')
            <a href="{{ route('reports.create-laporan-akhir') }}" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Upload Laporan Akhir
            </a>
        @else
            <a href="{{ route('reports.create-luaran') }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Upload Luaran
            </a>
        @endif
    </div>

    {{-- Reports Table --}}
    @if($reports->count() > 0)
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-700 bg-gray-800">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-white">Judul</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-white">Usulan Terkait</th>
                        @if($type === 'luaran')
                            <th class="px-6 py-3 text-left text-sm font-semibold text-white">Tipe</th>
                        @endif
                        <th class="px-6 py-3 text-left text-sm font-semibold text-white">Tanggal Upload</th>
                        <th class="px-6 py-3 text-right text-sm font-semibold text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 bg-gray-800">
                    @foreach($reports as $report)
                        <tr class="hover:bg-gray-700/50 transition">
                            <td class="px-6 py-4 text-sm text-gray-200">
                                {{ Str::limit($report->title, 50) }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-300">
                                {{ Str::limit($report->proposal->judul, 40) }}
                            </td>
                            @if($type === 'luaran')
                                <td class="px-6 py-4 text-sm text-gray-300">
                                    @if($report->luaran_type === 'file')
                                        <span class="inline-flex items-center px-2 py-1 text-xs bg-blue-900/30 text-blue-300 rounded">
                                            {{ $report->file_icon }} File
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 text-xs bg-green-900/30 text-green-300 rounded">
                                            🔗 Link
                                        </span>
                                    @endif
                                </td>
                            @endif
                            <td class="px-6 py-4 text-sm text-gray-400">
                                {{ $report->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('reports.show', ['type' => $type, 'report' => $report]) }}" 
                                       class="text-indigo-400 hover:text-indigo-300">
                                        Lihat
                                    </a>
                                    
                                    <span class="text-gray-600">|</span>
                                    
                                    <form method="POST" 
                                          action="{{ route('reports.destroy', ['type' => $type, 'report' => $report]) }}" 
                                          class="inline-block"
                                          onsubmit="return confirm('Yakin ingin menghapus {{ $type === 'laporan_akhir' ? 'laporan akhir' : 'luaran' }} ini?')">
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

        <div class="mt-6">
            {{ $reports->links() }}
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-12 bg-gray-800 rounded-lg border border-gray-700">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-white">Belum Ada {{ $type === 'laporan_akhir' ? 'Laporan Akhir' : 'Luaran' }}</h3>
            <p class="mt-1 text-gray-400">Mulai upload {{ $type === 'laporan_akhir' ? 'laporan akhir' : 'luaran' }} penelitian Anda.</p>
            <div class="mt-6">
                @if($type === 'laporan_akhir')
                    <a href="{{ route('reports.create-laporan-akhir') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Upload Laporan Akhir
                    </a>
                @else
                    <a href="{{ route('reports.create-luaran') }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Upload Luaran
                    </a>
                @endif
            </div>
        </div>
    @endif
</x-layout>