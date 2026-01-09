<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    {{-- SUCCESS & ERROR MESSAGES --}}
    <div class="mb-6">
        {{-- Success Message --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="rounded-md bg-green-900/20 p-4 border border-green-700 mb-4">
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

        {{-- Error Message --}}
        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" class="rounded-md bg-red-900/20 p-4 border border-red-700 mb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm font-medium text-red-400">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="text-red-400 hover:text-red-300">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <div class="bg-white shadow overflow-hidden sm:rounded-lg dark:bg-gray-800">

        {{-- Header Info --}}
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Informasi Proposal
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Detail lengkap proposal yang diajukan.
            </p>
        </div>

        {{-- Detail Proposal --}}
        <div class="border-t border-gray-200 dark:border-gray-700">
            <dl>
                {{-- Judul --}}
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Judul</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $proposal->judul }}
                    </dd>
                </div>

                {{-- Publisher/Author --}}
                <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Pengaju usulan</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $proposal->author->name ?? 'N/A' }}
                    </dd>
                </div>
                
                {{-- Deskripsi --}}
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Abstrak/Deskripsi</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        {{ $proposal->deskripsi }}
                    </dd>
                </div>

                {{-- Status --}}
                <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ App\Helpers\ProposalHelper::statusColor($proposal->status) }}">
                            {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
                        </span>
                    </dd>
                </div>

                {{-- File Proposal --}}
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-300">File Proposal</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white sm:mt-0 sm:col-span-2">
                        <a href="{{ Storage::url($proposal->file_usulan) }}" 
                           target="_blank"
                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            📄 Lihat File
                        </a>
                    </dd>
                </div>

                {{-- Hasil Review (jika sudah direview) --}}
                @if($proposal->reviews->count() > 0)
                    <div class="bg-white dark:bg-gray-800 px-4 py-5 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-300 mb-4">Hasil Review</dt>
                        
                        @foreach($proposal->reviews as $review)
                            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                {{-- Info Reviewer --}}
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <p class="font-medium text-white">Direview oleh: {{ $review->reviewer->name }}</p>
                                        <p class="text-sm text-gray-400">{{ $review->created_at->format('d M Y, H:i') }}</p>
                                    </div>

                                    {{-- Handle recommendation bahasa Indonesia --}}
                                    @php
                                        $isApproved = $review->recommendation === 'setuju';
                                        $badgeClass = $isApproved ? 'bg-green-700 text-green-200' : 'bg-red-700 text-red-200';
                                        $badgeText = $isApproved ? '✅ Disetujui' : '❌ Perlu Revisi';
                                    @endphp

                                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                        {{ $review->recommendation === 'setuju' ? 'bg-green-700 text-green-200' : 'bg-red-700 text-red-200' }}">
                                        {{ $review->recommendation === 'setuju' ? '✅ Disetujui' : '❌ Perlu Revisi' }}
                                    </span>
                                </div>

                                {{-- Tabel Scoring --}}
                                @if($review->scores)
                                    <div class="mb-4">
                                        <h4 class="text-sm font-medium text-gray-300 mb-2">Detail Penilaian:</h4>
                                        <table class="w-full text-sm text-gray-300">
                                            <thead class="text-xs uppercase bg-gray-600">
                                                <tr>
                                                    <th class="px-3 py-2 text-left">Kriteria</th>
                                                    <th class="px-3 py-2 text-center">Skor</th>
                                                    <th class="px-3 py-2 text-center">Nilai (25%)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="border-b border-gray-600">
                                                    <td class="px-3 py-2">Pendahuluan</td>
                                                    <td class="px-3 py-2 text-center">{{ $review->scores['pendahuluan'] ?? 0 }}</td>
                                                    <td class="px-3 py-2 text-center">{{ ($review->scores['pendahuluan'] ?? 0) * 0.25 }}</td>
                                                </tr>
                                                <tr class="border-b border-gray-600">
                                                    <td class="px-3 py-2">Tinjauan Pustaka</td>
                                                    <td class="px-3 py-2 text-center">{{ $review->scores['tinjauan_pustaka'] ?? 0 }}</td>
                                                    <td class="px-3 py-2 text-center">{{ ($review->scores['tinjauan_pustaka'] ?? 0) * 0.25 }}</td>
                                                </tr>
                                                <tr class="border-b border-gray-600">
                                                    <td class="px-3 py-2">Metodologi Penelitian</td>
                                                    <td class="px-3 py-2 text-center">{{ $review->scores['metodologi'] ?? 0 }}</td>
                                                    <td class="px-3 py-2 text-center">{{ ($review->scores['metodologi'] ?? 0) * 0.25 }}</td>
                                                </tr>
                                                <tr class="border-b border-gray-600">
                                                    <td class="px-3 py-2">Kelayakan Penelitian</td>
                                                    <td class="px-3 py-2 text-center">{{ $review->scores['kelayakan'] ?? 0 }}</td>
                                                    <td class="px-3 py-2 text-center">{{ ($review->scores['kelayakan'] ?? 0) * 0.25 }}</td>
                                                </tr>
                                                <tr class="bg-gray-600 font-bold">
                                                    <td class="px-3 py-2">TOTAL</td>
                                                    <td class="px-3 py-2 text-center">-</td>
                                                    <td class="px-3 py-2 text-center">{{ $review->total_score }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                {{-- Catatan Reviewer --}}
                                @if($review->comment)
                                    <div class="mt-4">
                                        <h4 class="text-sm font-medium text-gray-300 mb-2">Catatan Reviewer:</h4>
                                        <div class="p-3 bg-gray-600 rounded text-gray-200">
                                            {{ $review->comment }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Tombol Action --}}
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-5 sm:px-6">
                    <div class="flex gap-3 justify-end">
                        @auth
                            {{-- Tombol untuk Reviewer --}}
                            @if(Auth::user()->role === 'reviewer')
                                @php
                                    // ✅ LOGIKA BARU: Cek apakah proposal masih bisa direview
                                    $canReview = in_array($proposal->status, ['submitted', 'under_review']);
                                    
                                    // ✅ Cek apakah reviewer ini sudah pernah review
                                    $hasReviewed = $proposal->reviews()
                                        ->where('reviewer_id', Auth::id())
                                        ->exists();
                                    
                                    // ✅ Status untuk pesan
                                    $isAccepted = $proposal->status === 'accepted';
                                    $needsRevision = $proposal->status === 'need_revision';
                                @endphp

                                {{-- ✅ HANYA TAMPIL JIKA: status = submitted/under_review DAN belum direview --}}
                                @if($canReview && !$hasReviewed)
                                    <a href="{{ route('reviewer.review-form', $proposal) }}" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                                        📝 Beri Review
                                    </a>
                                
                                {{-- ✅ Jika sudah pernah review --}}
                                @elseif($hasReviewed)
                                    <div class="inline-flex items-center px-4 py-2 text-sm text-blue-400 bg-blue-900/20 rounded-md border border-blue-700">
                                        ✅ Anda sudah melakukan review
                                    </div>

                                {{--Jika status accepted --}}
                                @elseif($isAccepted)
                                    <div class="inline-flex items-center px-4 py-2 text-sm text-green-400 bg-green-900/20 rounded-md border border-green-700">
                                        ✅ Proposal telah disetujui
                                    </div>

                                {{--Jika perlu revisi --}}
                                @elseif($needsRevision)
                                    <div class="inline-flex items-center px-4 py-2 text-sm text-orange-400 bg-orange-900/20 rounded-md border border-orange-700">
                                        ⏳ Menunggu revisi dari publisher
                                    </div>

                                {{--Status lainnya (rejected, draft, dll) --}}
                                @else
                                    <div class="inline-flex items-center px-4 py-2 text-sm text-gray-400 bg-gray-900/20 rounded-md border border-gray-700">
                                        ℹ️ Proposal tidak dapat direview (Status: {{ ucfirst(str_replace('_', ' ', $proposal->status)) }})
                                    </div>
                                @endif
                            @endif

                            {{-- Tombol untuk Publisher (Owner) --}}
                            @if(Auth::user()->id === $proposal->user_id)
                                @php
                                    // ✅ Cek apakah proposal bisa diedit (hanya draft dan need_revision)
                                    $canEdit = in_array($proposal->status, ['draft', 'need_revision']);
                                @endphp

                                {{-- Tombol Submit (hanya untuk status draft) --}}
                                @if($proposal->status === 'draft')
                                    <form action="{{ route('proposals.submit', $proposal) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                            📤 Submit untuk Review
                                        </button>
                                    </form>
                                @endif

                                {{-- Tombol Edit (hanya untuk draft dan need_revision) --}}
                                @if($canEdit)
                                    <a href="{{ route('proposals.edit', $proposal) }}" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white 
                                    {{ $proposal->status === 'need_revision' ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-gray-600 hover:bg-gray-500' }}">
                                        @if($proposal->status === 'need_revision')
                                            ✏️ Upload Revisi
                                        @else
                                            ✏️ Edit
                                        @endif
                                    </a>
                                @else
                                    {{-- Info: Tidak bisa edit --}}
                                    <div class="inline-flex items-center px-4 py-2 text-sm text-gray-400 bg-gray-900/20 rounded-md border border-gray-700">
                                        🔒 Tidak dapat diedit ({{ ucfirst(str_replace('_', ' ', $proposal->status)) }})
                                    </div>
                                @endif
                            @endif
                        @endauth

                        {{-- Tombol Kembali (berdasarkan role) --}}
                        @php
                            $backUrl = Auth::user()->role === 'publisher' 
                                ? route('proposals.index')      // Publisher → /proposals
                                : route('proposals.browse');    // Reviewer/Admin → /proposals/browse
                        @endphp

                        <a href="{{ $backUrl }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
                            Kembali
                        </a>
                    </div>
                </div>

            </dl>
        </div>
    </div>
</x-layout>