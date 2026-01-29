{{-- resources/views/LaporanPenelitian.blade.php --}}
<x-layout>
    <x-slot:title>{{ $title }}</x-slot>
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-screen-7xl sm:py-16 lg:px-6">
            
            <div class="space-y-8 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-12 md:space-y-0">

                {{-- Card 1: Usulan Terkirim (Semua role) --}}
                <div class="border border-gray-700 rounded-xl p-6 hover:shadow-lg transition relative">

                    {{-- BADGE COUNTER - Pojok Kanan Atas (Selalu tampil) --}}
                    @if(isset($counts['all']))
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-bold text-white rounded-full shadow-lg
                                {{ $counts['all'] > 0 ? 'bg-blue-600' : 'bg-gray-600' }}">
                                {{ $counts['all'] }}
                            </span>
                        </div>
                    @endif

                    <div class="flex justify-center items-center mb-4 w-10 h-10 rounded-full bg-primary-100 lg:h-12 lg:w-12 dark:bg-primary-900">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7Z" clip-rule="evenodd"/>
                        </svg>
                    </div>

                    @php
                        $link = Auth::user()->role === 'publisher' 
                            ? route('proposals.index')
                            : route('proposals.browse');
                    @endphp

                    <a href="{{ $link }}"
                       class="mb-2 text-xl font-bold dark:text-white hover:underline">
                        Upload laporan akhir
                    </a>

                    <p class="text-gray-500 dark:text-gray-400">
                        tempat pengumpulan laporan akhir
                    </p>
                </div>

                {{-- Card 2: Usulan Disetujui (HANYA PUBLISHER) --}}
                @if(Auth::user()->role === 'publisher')
                    <div class="border border-gray-700 rounded-xl p-6 hover:shadow-lg transition relative">
                        
                        {{-- BADGE COUNTER (Selalu tampil) --}}
                        @if(isset($counts['accepted']))
                            <div class="absolute top-4 right-4">
                                <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-bold text-white rounded-full shadow-lg
                                    {{ $counts['accepted'] > 0 ? 'bg-green-600' : 'bg-gray-600' }}">
                                    {{ $counts['accepted'] }}
                                </span>
                            </div>
                        @endif
                            
                        <div class="flex justify-center items-center mb-4 w-10 h-10 rounded-full bg-primary-100 lg:h-12 lg:w-12 dark:bg-primary-900">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white"
                                 xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round"
                                      stroke-linejoin="round" stroke-width="2"
                                      d="M5 11.917 9.724 16.5 19 7.5" />
                            </svg>
                        </div>

                        <a href="{{ route('proposals.accepted') }}"
                           class="mb-2 text-xl font-bold dark:text-white hover:underline">
                            Luaran
                        </a>

                        <p class="text-gray-500 dark:text-gray-400">
                            tempat pengumpulan luaran, hasil dari penilaian
                        </p>
                    </div>
                @endif

                {{-- Card 3: Revisi Usulan (HANYA PUBLISHER) --}}
                @if(Auth::user()->role === 'publisher')
                    <div class="border border-gray-700 rounded-xl p-6 hover:shadow-lg transition relative">
                        
                        {{-- BADGE COUNTER (Selalu tampil, pulse jika > 0) --}}
                        @if(isset($counts['need_revision']))
                            <div class="absolute top-4 right-4">
                                <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-bold text-white rounded-full shadow-lg
                                    {{ $counts['need_revision'] > 0 ? 'bg-red-600 animate-pulse' : 'bg-gray-600' }}">
                                    {{ $counts['need_revision'] }}
                                </span>
                            </div>
                        @endif

                        <div class="flex justify-center items-center mb-4 w-10 h-10 rounded-full bg-primary-100 lg:h-12 lg:w-12 dark:bg-primary-900">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white"
                                 xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round"
                                      stroke-width="2"
                                      d="m6 6 12 12m3-6a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>

                        <a href="{{ route('proposals.revisions') }}"
                           class="mb-2 text-xl font-bold dark:text-white hover:underline">
                            Revisi Usulan
                        </a>

                        <p class="text-gray-500 dark:text-gray-400">
                            Hasil karya ilmiah tidak memenuhi kriteria
                        </p>
                    </div>
                @endif

                {{-- Card 4: halaman dashboard admin (HANYA admin) --}}
                @if(Auth::user()->role === 'admin')
                    <div class="border border-gray-700 rounded-xl p-6 hover:shadow-lg transition">
                        <div class="flex justify-center items-center mb-4 w-10 h-10 rounded-full bg-primary-100 lg:h-12 lg:w-12 dark:bg-primary-900">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <a href="{{ route('admin.dashboard') }}"
                           class="mb-2 text-xl font-bold dark:text-white hover:underline">
                            Dashboard Admin
                        </a>

                        <p class="text-gray-500 dark:text-gray-400">
                            Daftar statistik dan manajemen reviewer
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </section>

</x-layout> 