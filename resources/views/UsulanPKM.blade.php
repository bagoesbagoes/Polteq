<x-layout>
    <x-slot:title>{{ $title }}</x-slot>
    <section class="bg-white dark:bg-gray-900">
        <div class="py-8 px-4 mx-auto max-w-screen-7xl sm:py-16 lg:px-6">
            <div class="space-y-8 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-12 md:space-y-0">
                
                {{-- Card 1 : PKM terkirim --}}
                <div class="border border-gray-700 rounded-xl p-6 hover:shadow-lg transition relative">
                    
                    {{-- Badge Counter --}}
                    @if (isset($counts['all']))
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center justify-center w-8 h-8 text-sm font-bold text-white rounded-full shadow-lg
                                {{ $counts['all'] > 0 ? 'bg-green-600' : 'bg-gray-600' }}">
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
                            ? route('pkm.index')
                            : route('pkm.browse');
                    @endphp

                    <a  href="{{ $link }}"
                        class="mb-2 text-xl font-bold dark:text-white hover:underline">
                        PKM Terkirim
                    </a>

                    <p  class="text-gray-500 dark:text-gray-400 text-sm">
                        @if (Auth::user()->role === 'publisher')
                            PKM yang telah terkirim (draft & submitted)
                        @else
                            PKM yang telah diupload dosen
                        @endif
                    </p>

                </div>

                {{-- Card 2: PKM Disetujui (HANYA PUBLISHER) --}}
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

                        <a href="{{ route('pkm.accepted') }}"
                           class="mb-2 text-xl font-bold dark:text-white hover:underline">
                            PKM Disetujui
                        </a>

                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            PKM yang telah disetujui reviewer
                        </p>
                    </div>
                @endif

                {{-- Card 3 : Revisi PKM (hanya Publisher) --}}
                @if (Auth::user()->role === 'publisher')
                    <div class="border border-gray-700 rounded-xl p-6 hover:shadow-lg transition relative">

                        {{-- Badge Counter (selalu tampil, pulsa jika > 0) --}}
                        @if (isset($counts['need_revision']))
                            <div class="absolute top-4 right-4">
                                <span   class="inline-flex items-center justify-center w-8 h-8 text-sm font-bold text-white rounded-full shadow-lg
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

                        <a href="{{ route('pkm.revisions') }}"
                           class="mb-2 text-xl font-bold dark:text-white hover:underline">
                            Revisi PKM
                        </a>

                        <p class="text-gray-500 dark:text-gray-400 text-sm">
                            PKM yang memerlukan perbaikan
                        </p>

                    </div>
                @endif

                {{-- Card 4: Dashboard Admin PKM--}}
                @if(Auth::user()->role === 'admin')
                    <div class="border border-gray-700 rounded-xl p-6 hover:shadow-lg transition">
                        <div class="flex justify-center items-center mb-4 w-10 h-10 rounded-full bg-primary-100 lg:h-12 lg:w-12 dark:bg-primary-900">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M12 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4h-4Z" clip-rule="evenodd"/>
                            </svg>
                        </div>

                        <a href="{{ route('admin.pkm') }}"
                           class="mb-2 text-xl font-bold dark:text-white hover:underline">
                            Dashboard Admin PKM
                        </a>

                        <p class="text-gray-500 dark:text-gray-400">
                            Kelola dan pantau semua usulan PKM
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </section>
</x-layout>