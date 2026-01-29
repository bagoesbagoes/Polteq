{{-- resources/views/UsulanPenelitian.blade.php --}}
<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    @if(session('success'))
        @php
            $successMessage = session('success');
            session()->forget('success');
        @endphp
        
        <div
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            class="mb-4 bg-green-600 text-white px-4 py-3 rounded-lg flex justify-between items-center shadow-lg"
        >
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-medium">{{ $successMessage }}</span>
            </div>
            <button 
                @click="show = false" 
                class="ml-4 text-white hover:text-gray-200 font-bold px-2 focus:outline-none"
                aria-label="Close"
            >
                ×
            </button>
        </div>
    @endif

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
                        <svg class="w-6 h-6 text-gray-800 dark:text-white"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                d="M12 2a1 1 0 0 1 .932.638l7 18a1 1 0 0 1-1.326 1.281L13 19.517V13a1 1 0 1 0-2 0v6.517l-5.606 2.402a1 1 0 0 1-1.326-1.281l7-18A1 1 0 0 1 12 2Z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                    @php
                        $link = Auth::user()->role === 'publisher' 
                            ? route('proposals.index')
                            : route('proposals.browse');
                    @endphp

                    <a href="{{ $link }}"
                       class="mb-2 text-xl font-bold dark:text-white hover:underline">
                        Usulan Terkirim
                    </a>

                    <p class="text-gray-500 dark:text-gray-400">
                        Hasil karya ilmiah yang telah dikirim
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
                            Usulan Disetujui
                        </a>

                        <p class="text-gray-500 dark:text-gray-400">
                            Hasil karya ilmiah yang telah disetujui reviewer
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

                {{-- Card 4: Pengumuman Hasil Akhir (HANYA PUBLISHER) --}}
                @if(Auth::user()->role === 'publisher')
                    <div class="border border-gray-700 rounded-xl p-6 hover:shadow-lg transition">
                        <div class="flex justify-center items-center mb-4 w-10 h-10 rounded-full bg-primary-100 lg:h-12 lg:w-12 dark:bg-primary-900">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M18.458 3.11A1 1 0 0 1 19 4v16a1 1 0 0 1-1.581.814L12 16.944V7.056l5.419-3.87a1 1 0 0 1 1.039-.076ZM22 12c0 1.48-.804 2.773-2 3.465v-6.93c1.196.692 2 1.984 2 3.465ZM10 8H4a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6V8Zm0 9H5v3a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-3Z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <h3 class="mb-2 text-xl font-bold dark:text-white">Pengumuman Hasil Akhir</h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            Surat kerja untuk proposal yang disetujui
                        </p>
                    </div>
                @endif

                {{-- Card 5: halaman dashboard admin (HANYA admin) --}}
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