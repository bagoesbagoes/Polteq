<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="bg-white shadow overflow-hidden sm:rounded-lg dark:bg-gray-800">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Informasi Proposal
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Detail lengkap proposal yang diajukan.
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <dl>
                {{-- Baris Judul Proposal --}}
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                        Judul
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $proposal->title }}
                    </dd>
                </div>

                {{-- Baris Publisher/Author --}}
                <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                        Pengaju / Publisher
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $proposal->user->name ?? 'N/A' }} 
                    </dd>
                </div>

                {{-- Baris Deskripsi Proposal --}}
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                        Abstrak/Deskripsi
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $proposal->description }} {{-- Sesuaikan nama kolom --}}
                    </dd>
                </div>
                
                {{-- Baris Status Proposal --}}
                <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">
                        Status
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            {{ $proposal->status ?? 'Menunggu Review' }} {{-- Sesuaikan nama kolom status --}}
                        </span>
                    </dd>
                </div>

                {{-- Tambahkan baris data lain yang relevan (tanggal, jenis, dll.) --}}

                {{-- Bagian Reviewer/Action --}}
                @auth
                    {{-- Memeriksa apakah user yang sedang login adalah 'reviewer' --}}
                    @if(Auth::user()->role === 'reviewer')
                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:px-6 text-right">
                            <a href="{{ route('reviewer.review-form', $proposal) }}" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Beri Review
                            </a>
                        </div>
                    {{-- Tombol ini HANYA akan dirender jika kondisi di atas terpenuhi --}}
                    @endif
                @endauth

            </dl>
        </div>
    </div>
</x-layout>