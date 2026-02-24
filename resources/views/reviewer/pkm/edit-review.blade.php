<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="mx-auto max-w-5xl px-4 py-6">
        
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('reviewer.pkm-show-review', $review) }}" 
               class="inline-flex items-center text-sm text-indigo-400 hover:text-indigo-300">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Detail Review
            </a>
        </div>

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="mb-6 rounded-md bg-red-900/20 p-4 border border-red-700">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-medium text-red-400">Ada kesalahan:</h3>
                        <ul class="mt-2 text-sm text-red-300 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            {{-- Left: PKM Info --}}
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
                <h2 class="text-xl font-bold text-white mb-4">Informasi PKM</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 mb-1">Judul</h3>
                        <p class="text-white">{{ $review->pkmProposal->judul }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-gray-400 mb-1">Pengusul</h3>
                        <p class="text-white">{{ $review->pkmProposal->author->name }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 mb-1">Tahun</h3>
                            <p class="text-white">{{ $review->pkmProposal->tahun_pelaksanaan }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-400 mb-1">Status</h3>
                            <div>{!! $review->pkmProposal->status_badge !!}</div>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('pkm.show', $review->pkmProposal) }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition text-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat Detail Lengkap
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right: Edit Form --}}
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
                <h2 class="text-xl font-bold text-white mb-4">Edit Review</h2>
                
                <form method="POST" action="{{ route('reviewer.pkm-update-review', $review) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Score --}}
                    <div>
                        <label for="score" class="block text-sm font-medium text-white mb-2">
                            Score <span class="text-red-500">*</span>
                            <span class="text-gray-400 font-normal">(0-100)</span>
                        </label>
                        <input type="number" name="score" id="score" min="0" max="100" 
                               value="{{ old('score', $review->score) }}" required
                               class="block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-blue-500 sm:text-sm">
                    </div>

                    {{-- Recommendation --}}
                    <div>
                        <label class="block text-sm font-medium text-white mb-3">
                            Rekomendasi <span class="text-red-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-start cursor-pointer p-3 rounded-lg border-2 transition-colors hover:border-green-500 {{ old('recommendation', $review->recommendation) === 'accept' ? 'border-green-500 bg-green-900/20' : 'border-gray-600 bg-gray-700/50' }}">
                                <input type="radio" name="recommendation" value="accept" 
                                       {{ old('recommendation', $review->recommendation) === 'accept' ? 'checked' : '' }}
                                       class="mt-1 w-4 h-4 text-green-600 focus:ring-green-500">
                                <div class="ml-3">
                                    <span class="text-white font-medium">✅ Accept (Terima)</span>
                                    <p class="text-sm text-gray-400 mt-1">PKM memenuhi kriteria</p>
                                </div>
                            </label>

                            <label class="flex items-start cursor-pointer p-3 rounded-lg border-2 transition-colors hover:border-yellow-500 {{ old('recommendation', $review->recommendation) === 'revise' ? 'border-yellow-500 bg-yellow-900/20' : 'border-gray-600 bg-gray-700/50' }}">
                                <input type="radio" name="recommendation" value="revise" 
                                       {{ old('recommendation', $review->recommendation) === 'revise' ? 'checked' : '' }}
                                       class="mt-1 w-4 h-4 text-yellow-600 focus:ring-yellow-500">
                                <div class="ml-3">
                                    <span class="text-white font-medium">📝 Revise (Perlu Revisi)</span>
                                    <p class="text-sm text-gray-400 mt-1">PKM perlu diperbaiki</p>
                                </div>
                            </label>

                            <label class="flex items-start cursor-pointer p-3 rounded-lg border-2 transition-colors hover:border-red-500 {{ old('recommendation', $review->recommendation) === 'reject' ? 'border-red-500 bg-red-900/20' : 'border-gray-600 bg-gray-700/50' }}">
                                <input type="radio" name="recommendation" value="reject" 
                                       {{ old('recommendation', $review->recommendation) === 'reject' ? 'checked' : '' }}
                                       class="mt-1 w-4 h-4 text-red-600 focus:ring-red-500">
                                <div class="ml-3">
                                    <span class="text-white font-medium">❌ Reject (Tolak)</span>
                                    <p class="text-sm text-gray-400 mt-1">PKM tidak memenuhi kriteria</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Comments --}}
                    <div>
                        <label for="comments" class="block text-sm font-medium text-white mb-2">
                            Komentar & Catatan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="comments" id="comments" rows="8" required
                                  class="block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 focus:ring-2 focus:ring-blue-500 sm:text-sm">{{ old('comments', $review->comments) }}</textarea>
                        <p class="mt-1 text-xs text-gray-400">Minimal 20 karakter</p>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex gap-3">
                        <a href="{{ route('reviewer.pkm-show-review', $review) }}" 
                           class="flex-1 text-center px-4 py-3 bg-gray-700 text-white font-medium rounded-md hover:bg-gray-600 transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="flex-1 inline-flex justify-center items-center px-4 py-3 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Review
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-layout>