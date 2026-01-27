{{-- resources/views/reviewer/dashboard.blade.php --}}
<x-layout>
    <x-slot:title>Dashboard Reviewer</x-slot:title>

    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        
        {{-- Pesan Sukses --}}
        @if(session('success'))
        <div class="mb-6 rounded-md bg-green-900/20 p-4 border border-green-700">
            <p class="text-sm text-green-400">{{ session('success') }}</p>
        </div>
        @endif

        {{-- BAGIAN 1: Proposal yang Perlu Direview --}}
        <div class="mb-10">
            <h2 class="text-xl font-bold text-white mb-4">Daftar Proposal Menunggu Review</h2>
            <div class="overflow-hidden rounded-lg bg-gray-800 shadow ring-1 ring-white/10">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white">Judul Proposal</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-white">Pengusul</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-white">Tanggal Submit</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-white">Status</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                <span class="sr-only">Action</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 bg-gray-800">
                        @forelse($proposals as $proposal)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white">
                                {{ Str::limit($proposal->judul, 40) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                {{ $proposal->author->name ?? 'Unknown' }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                {{ $proposal->created_at->format('d M Y') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                <span class="inline-flex items-center rounded-md bg-yellow-400/10 px-2 py-1 text-xs font-medium text-yellow-500 ring-1 ring-inset ring-yellow-400/20">
                                    {{ ucfirst($proposal->status) }}
                                </span>
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('reviewer.review-form', $proposal) }}" class="text-indigo-400 hover:text-indigo-300">
                                    Mulai Review <span class="sr-only">, {{ $proposal->judul }}</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-400">
                                Tidak ada proposal baru yang perlu direview saat ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $proposals->links() }}
            </div>
        </div>

        {{-- BAGIAN 2: Riwayat Review Saya --}}
        <div>
            <h2 class="text-xl font-bold text-white mb-4">Riwayat Review Saya</h2>
            <div class="overflow-hidden rounded-lg bg-gray-800 shadow ring-1 ring-white/10">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-900/50">
                        <tr>
                            <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white">Proposal</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-white">Total Skor</th>
                            <th class="px-3 py-3.5 text-left text-sm font-semibold text-white">Rekomendasi</th>
                            <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 bg-gray-800">
                        @forelse($myReviews as $review)
                        <tr>
                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-white">
                                {{ Str::limit($review->proposal->judul, 40) }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-300">
                                {{ $review->total_score }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset 
                                    {{ $review->recommendation == 'accepted' ? 'bg-green-400/10 text-green-400 ring-green-400/20' : 'bg-red-400/10 text-red-400 ring-red-400/20' }}">
                                    {{ ucfirst($review->recommendation) }}
                                </span>
                            </td>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <a href="{{ route('reviewer.show-review', $review) }}" class="text-indigo-400 hover:text-indigo-300">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-400">
                                Anda belum melakukan review apapun.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $myReviews->links() }}
            </div>
        </div>

    </div>
</x-layout>