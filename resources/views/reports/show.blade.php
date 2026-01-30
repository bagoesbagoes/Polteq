<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="mx-auto max-w-5xl px-4 py-6">
        
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ $type === 'laporan_akhir' ? route('reports.laporan-akhir') : route('reports.luaran') }}" 
               class="inline-flex items-center text-sm text-indigo-400 hover:text-indigo-300">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar {{ $type === 'laporan_akhir' ? 'Laporan Akhir' : 'Luaran' }}
            </a>
        </div>

        {{-- Main Card --}}
        <div class="bg-gray-800 shadow-lg rounded-lg overflow-hidden border border-gray-700">
            
            {{-- Header Section --}}
            <div class="bg-linear-to-r from-{{ $type === 'laporan_akhir' ? 'indigo' : 'green' }}-900 to-{{ $type === 'laporan_akhir' ? 'indigo' : 'green' }}-800 px-6 py-8 border-b border-gray-700">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $type === 'laporan_akhir' ? 'indigo' : 'green' }}-600 text-white mb-3">
                            {{ $type === 'laporan_akhir' ? '📄 Laporan Akhir' : '🎯 Luaran' }}
                        </span>
                        <h1 class="text-3xl font-bold text-white mt-2">{{ $report->title }}</h1>
                        <p class="text-{{ $type === 'laporan_akhir' ? 'indigo' : 'green' }}-200 mt-2 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Diupload: {{ $report->created_at->format('d F Y, H:i') }} WIB
                        </p>
                    </div>
                </div>
            </div>

            {{-- Content Section --}}
            <div class="p-6 space-y-6">

                {{-- Usulan Terkait --}}
                <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-300 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Usulan Terkait
                    </h3>
                    <p class="text-white font-medium">{{ $report->proposal->judul }}</p>
                    <p class="text-gray-400 text-sm mt-1">ID Proposal: #{{ $report->proposal->id }}</p>
                </div>

                {{-- Penulis --}}
                <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-300 mb-3 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Penulis
                    </h3>
                    <p class="text-white font-medium">{{ $report->author->name }}</p>
                    <p class="text-gray-400 text-sm mt-1">{{ $report->author->email }}</p>
                </div>

                {{-- Deskripsi --}}
                @if($report->description)
                    <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-300 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                            </svg>
                            Deskripsi
                        </h3>
                        <p class="text-gray-300 leading-relaxed whitespace-pre-line">{{ $report->description }}</p>
                    </div>
                @endif

                {{-- Tipe Luaran & File/Link Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    @if($type === 'luaran')
                        {{-- Tipe Luaran --}}
                        <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-300 mb-3">Tipe Luaran</h3>
                            @if($report->luaran_type === 'file')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-900/30 text-blue-300">
                                    {{ $report->file_icon }} File Upload
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-900/30 text-green-300">
                                    🔗 Hyperlink
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- File Info atau Hyperlink --}}
                    @if($report->file_path)
                        <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-300 mb-3">Informasi File</h3>
                            <div class="space-y-2">
                                <p class="text-gray-300 text-sm">
                                    <span class="text-gray-400">Tipe:</span> 
                                    <span class="font-medium uppercase">{{ $report->file_type }}</span>
                                </p>
                                <p class="text-gray-300 text-sm">
                                    <span class="text-gray-400">Ukuran:</span> 
                                    <span class="font-medium">{{ $report->file_size_human }}</span>
                                </p>
                            </div>
                        </div>
                    @endif

                    @if($report->hyperlink)
                        <div class="bg-gray-900/50 rounded-lg p-5 border border-gray-700 {{ $type === 'luaran' ? '' : 'md:col-span-2' }}">
                            <h3 class="text-sm font-semibold text-gray-300 mb-3">URL Hyperlink</h3>
                            <a href="{{ $report->hyperlink }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="text-blue-400 hover:text-blue-300 underline break-all flex items-center">
                                {{ $report->hyperlink }}
                                <svg class="w-4 h-4 ml-1 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Footer Actions --}}
            <div class="bg-gray-900/50 px-6 py-4 border-t border-gray-700 flex justify-between items-center">
                
                {{-- Download Button --}}
                @if($report->file_path)
                    <a href="{{ route('reports.download', ['type' => $type, 'report' => $report]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Download File
                    </a>
                @elseif($report->hyperlink)
                    <a href="{{ $report->hyperlink }}" 
                       target="_blank"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Buka Link
                    </a>
                @endif

                {{-- Delete Button --}}
                <form method="POST" 
                      action="{{ route('reports.destroy', ['type' => $type, 'report' => $report]) }}" 
                      onsubmit="return confirm('⚠️ Yakin ingin menghapus {{ $type === 'laporan_akhir' ? 'laporan akhir' : 'luaran' }} ini? Tindakan ini tidak dapat dibatalkan!')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>

    </div>
</x-layout>
