<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="py-4 px-4 mx-auto max-w-screen-7xl lg:px-6">
        <div class="mx-auto max-w-screen-2xl sm:text-center">
            <form action="/posts" method="GET">
                @if (request('category'))
                    <input type="hidden" name="category" value="{{request('category')}}">
                @endif

                {{-- Search Bar --}}

                <div class="items-center mx-auto mb-3 space-y-4 max-w-screen-sm sm:flex sm:space-y-0">
                    
                    <div class="relative w-full">

                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M9 2.221V7H4.221a2 2 0 0 1 .365-.5L8.5 2.586A2 2 0 0 1 9 2.22ZM11 2v5a2 2 0 0 1-2 2H4v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2h-7Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>

                        <input type="search" id="search" name="search" autocomplete="off"
                            placeholder="cari usulan"
                            class="block p-3 pl-10 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 
                                   sm:rounded-none sm:rounded-l-lg focus:ring-primary-500 focus:border-primary-500 
                                   dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white 
                                   dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    </div>

                    <div>
                        <button type="submit"
                            class="py-3 px-5 w-full text-sm font-medium text-center text-white rounded-lg border 
                                   cursor-pointer bg-primary-700 border-primary-600 sm:rounded-none sm:rounded-r-lg 
                                   hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 
                                   dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Search
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <section class="bg-white dark:bg-gray-900">
        <div class="py-4 mx-auto max-w-7xl lg:py-8">
            <div class="grid gap-8 lg:grid-cols-3">


                
                @foreach ($posts as $post)
                    <article
                        class="p-6 bg-white rounded-lg border border-gray-200 shadow-md
                               dark:bg-gray-800 dark:border-gray-700">

                        <div class="flex justify-between items-center mb-5 text-gray-500">
                            <a href="/posts?category={{ $post->category->slug }}"
                                class="text-base text-gray-500 hover:underline">

                                <span
                                    class="bg-primary-100 text-primary-800 text-xs font-medium inline-flex items-center 
                                           px-2.5 py-0.5 rounded dark:bg-primary-200 dark:text-primary-800">
                                    {{ $post->category->name }}
                                </span>
                            </a>

                            <span class="text-sm">
                                {{ $post->created_at->format('d/m/Y') }}
                            </span>
                        </div>

                        <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            <a href="/posts/{{ $post['slug'] }}" class="hover:underline">
                                {{ $post['title'] }}
                            </a>
                        </h2>

                        <p class="mb-5 font-light text-gray-500 dark:text-gray-400">
                            {{ Str::limit($post['body'], 150) }}
                        </p>

                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                <a href="/authors/{{ $post->author->username }}"
                                    class="text-base text-gray-500 hover:underline">
                                    <span class="font-medium text-sm dark:text-white">
                                        {{ $post->author->name }}
                                    </span>
                                </a>
                            </div>

                            <a href="/posts/{{ $post['slug'] }}"
                                class="font-medium text-blue-500 hover:underline">
                                Read more &raquo;
                            </a>
                        </div>

                    </article>
                @endforeach

            </div>
        </div>
    </section>
</x-layout>
