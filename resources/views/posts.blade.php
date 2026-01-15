{{-- TAMPILAN DAFTAR USULAN UNTUK REVIEWER --}}

<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    {{-- CUSTOM CSS FOR LINE CLAMP --}}
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            max-height: 3.6em; 
            line-height: 1.8em;
        }
        
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* Tooltip styling */
        .tooltip-trigger {
            position: relative;
            cursor: help;
        }
    </style>

    {{-- SUCCESS MESSAGE (DITAMBAHKAN) --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" class="mb-6 rounded-md bg-green-900/20 p-4 border border-green-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium text-green-400">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-green-400 hover:text-green-300">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    {{-- SEARCH & FILTER SECTION --}}
    <div class="mb-6 bg-gray-800 rounded-lg p-6 shadow">
        <form method="GET" action="{{ route('proposals.browse') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                {{-- Search Input --}}
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-300 mb-2">
                        🔍 Cari Usulan
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        id="search"
                        value="{{ request('search') }}"
                        placeholder="Cari berdasarkan judul atau nama author..."
                        class="w-full bg-gray-700 border border-gray-600 text-white rounded-md px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    />
                </div>

                {{-- Status Filter --}}
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-300 mb-2">
                        📊 Filter Status
                    </label>
                    <select 
                        name="status" 
                        id="status"
                        class="w-full bg-gray-700 border border-gray-600 text-white rounded-md px-4 py-2 focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="">Semua Status</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="need_revision" {{ request('status') == 'need_revision' ? 'selected' : '' }}>Need Revision</option>
                    </select>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3">
                <button 
                    type="submit"
                    class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
                    🔍 Cari
                </button>
                
                @if(request('search') || request('status'))
                    <a 
                        href="{{ route('proposals.browse') }}"
                        class="px-6 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-600"
                    >
                        🔄 Reset
                    </a>
                @endif

                {{-- Sort Dropdown --}}
                <div class="ml-auto">
                    <select 
                        name="sort" 
                        onchange="this.form.submit()"
                        class="bg-gray-700 border border-gray-600 text-white rounded-md px-4 py-2 text-sm focus:ring-2 focus:ring-indigo-500"
                    >
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>📅 Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>📅 Terlama</option>
                    </select>
                </div>
            </div>
        </form>

        {{-- Search Results Info --}}
        @if(request('search') || request('status'))
            <div class="mt-4 pt-4 border-t border-gray-700">
                <p class="text-sm text-gray-400">
                    Menampilkan hasil untuk:
                    @if(request('search'))
                        <span class="inline-flex items-center px-2 py-1 bg-indigo-900/30 text-indigo-300 rounded text-xs">
                            "{{ request('search') }}"
                        </span>
                    @endif
                    @if(request('status'))
                        <span class="inline-flex items-center px-2 py-1 bg-blue-900/30 text-blue-300 rounded text-xs">
                            Status: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
                        </span>
                    @endif
                    <span class="ml-2 font-semibold text-white">
                        ({{ $posts->total() }} hasil)
                    </span>
                </p>
            </div>
        @endif
    </div>

    {{-- PROPOSAL LIST --}}
    <section class="bg-white dark:bg-gray-900">
        <div class="py-4 mx-auto max-w-7xl lg:py-8">
            
            {{-- Total Info --}}
            @if($posts->count() > 0 && !request('search') && !request('status'))
                <div class="mb-6">
                    <p class="text-sm text-gray-400">
                        Menampilkan {{ $posts->count() }} dari {{ $posts->total() }} usulan penelitian
                    </p>
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-3">
                @forelse ($posts as $post)
                    <article class="p-6 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700 flex flex-col min-h-[350px]">

                        <div class="flex justify-between items-center mb-4 text-gray-500">
                            {{-- Category Badge --}}
                            <span class="bg-primary-100 text-primary-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-primary-200 dark:text-primary-800">
                                Usulan
                            </span>

                            {{-- Status Badge --}}
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ App\Helpers\ProposalHelper::statusColor($post->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $post->status)) }}
                            </span>

                            <span class="text-sm">
                                {{ $post->created_at->format('d/m/Y') }}
                            </span>
                        </div>

                        <h2 class="mb-2 text-1xl font-bold tracking-tight text-gray-900 dark:text-white">
                            <a href="{{ route('proposals.view', $post->id) }}" 
                               class="hover:underline line-clamp-2"
                               title="{{ $post->title }}">
                                {{ $post->title }}
                            </a>
                        </h2>

                        <p class="mb-5 font-light text-gray-500 dark:text-gray-400 line-clamp-3" 
                           title="{{ strip_tags($post->body) }}">
                            {{ Str::limit($post->body, 150) }}
                        </p>

                        <div class="flex justify-between items-center mt-auto pt-4 border-t border-gray-700">
                            <div class="flex items-center space-x-2 text-white">
                                <h4>
                                    {{ $post->author->name }}
                                </h4>
                            </div>

                            <div class="flex gap-2">
                                {{-- Tombol Lihat Detail --}}
                                <a href="{{ route('proposals.view', $post->id) }}" class="font-medium text-blue-500 hover:underline">
                                    Lihat Detail 
                                </a>

                                {{-- Tombol Review (Hanya untuk Reviewer) --}}
                                @auth
                                    @if(Auth::user()->role === 'reviewer')
                                        @php
                                            $canReview = in_array($post->status, ['submitted', 'under_review']);
                                            $hasReviewed = $post->reviews()
                                                ->where('reviewer_id', Auth::id())
                                                ->exists();
                                        @endphp

                                        @if($canReview && !$hasReviewed)
                                            <a href="{{ route('reviewer.review-form', $post->id) }}" 
                                               class="inline-flex items-center px-3 py-1 text-sm font-medium text-white bg-green-600 rounded hover:bg-green-700">
                                                📝 Review
                                            </a>
                                        @elseif($hasReviewed)
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-400 bg-blue-900/20 rounded border border-blue-700">
                                                ✅ Sudah Review
                                            </span>
                                        @endif
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </article>
                @empty
                    {{-- Empty State --}}
                    <div class="col-span-3 text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-lg font-medium text-white">
                            @if(request('search') || request('status'))
                                Tidak Ada Hasil Ditemukan
                            @else
                                Belum Ada Usulan
                            @endif
                        </h3>
                        <p class="mt-1 text-gray-400">
                            @if(request('search') || request('status'))
                                Coba gunakan kata kunci atau filter yang berbeda.
                            @else
                                Belum ada proposal yang disubmit untuk direview.
                            @endif
                        </p>
                        @if(request('search') || request('status'))
                            <div class="mt-6">
                                <a href="{{ route('proposals.browse') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    🔄 Lihat Semua Usulan
                                </a>
                            </div>
                        @endif
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($posts->hasPages())
                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </section>
</x-layout>