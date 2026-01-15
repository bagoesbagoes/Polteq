<x-layout>
    <x-slot:title>Edit usulan - {{ $proposal->judul }}</x-slot:title>
    
    <div class="mx-auto max-w-4xl px-4 py-6">
        
        {{-- Alert: Info Status --}}
        @if($proposal->status === 'need_revision')
            <div class="mb-6 rounded-md bg-orange-900/20 p-4 border border-orange-700">
                <div class="flex">
                    <div class="shrink-0">
                        <svg class="h-5 w-5 text-orange-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-orange-400">Revisi Diperlukan</h3>
                        <div class="mt-2 text-sm text-orange-300">
                            <p>Usulan Anda memerlukan revisi. Silakan perbarui sesuai feedback reviewer.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 rounded-md bg-green-900/20 p-4 border border-green-700">
                <p class="text-sm text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="mb-6 rounded-md bg-red-900/20 p-4 border border-red-700">
                <ul class="text-sm text-red-400 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Edit --}}
        <form method="POST" action="{{ route('proposals.update', $proposal) }}" enctype="multipart/form-data" class="bg-gray-800 shadow sm:rounded-lg p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                
                {{-- Header --}}
                <div class="border-b border-gray-700 pb-4">
                    <h2 class="text-xl font-semibold text-white">Edit pengajuan usulan</h2>
                    <p class="mt-1 text-sm text-gray-400">
                        @if($proposal->status === 'need_revision')
                            Upload revisi sesuai feedback dari reviewer
                        @else
                            Perbarui informasi usulan Anda
                        @endif
                    </p>
                </div>

                {{-- Judul --}}
                <div>
                    <label for="judul" class="block text-sm font-medium leading-6 text-white">
                        Judul Usulan <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="judul" 
                        id="judul" 
                        value="{{ old('judul', $proposal->judul) }}" 
                        required 
                        class="mt-2 block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6" 
                    />
                    @error('judul')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="deskripsi" class="block text-sm font-medium leading-6 text-white">
                        Abstrak <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        name="deskripsi" 
                        id="deskripsi" 
                        rows="8" 
                        required 
                        class="mt-2 block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6"
                    >{{ old('deskripsi', $proposal->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- File Upload --}}
                <div>
                    <label for="file_usulan" class="block text-sm font-medium leading-6 text-white">
                        Upload PDF 
                        @if($proposal->status === 'need_revision')
                            <span class="text-red-500">*</span> (Revisi)
                        @else
                            <span class="text-gray-400">(Opsional - kosongkan jika tidak ingin mengubah)</span>
                        @endif
                    </label>
                    
                    {{-- Info File Saat Ini --}}
                    @if($proposal->file_usulan)
                        <div class="mt-2 mb-3 p-3 bg-gray-700 rounded-md border border-gray-600">
                            <p class="text-sm text-gray-300">
                                📄 File saat ini : 
                                <a href="{{ Storage::url($proposal->file_usulan) }}" 
                                   target="_blank" 
                                   class="text-blue-400 hover:text-blue-300 underline">
                                    {{ basename($proposal->file_usulan) }}
                                </a>
                            </p>
                        </div>
                    @endif

                    <input 
                        type="file" 
                        name="file_usulan" 
                        id="file_usulan" 
                        accept=".pdf" 
                        {{ $proposal->status === 'need_revision' ? 'required' : '' }}
                        class="mt-2 block w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700" 
                    />
                    
                    @if($proposal->status === 'need_revision')
                        <p class="mt-2 text-sm text-orange-400">
                            ⚠️ Wajib upload file revisi baru
                        </p>
                    @else
                        <p class="mt-2 text-sm text-gray-400">
                            Format: PDF, Maksimal: 10MB
                        </p>
                    @endif
                    
                    @error('file_usulan')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info Status --}}
                <div class="bg-gray-700 rounded-md p-4 border border-gray-600">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-300">Status saat ini:</p>
                            <span class="mt-1 inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full {{ App\Helpers\ProposalHelper::statusColor($proposal->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $proposal->status)) }}
                            </span>
                        </div>
                        
                        @if($proposal->status === 'need_revision')
                            <div class="text-right">
                                <p class="text-sm text-gray-300">Setelah update:</p>
                                <span class="mt-1 inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-blue-700 text-blue-200">
                                    Submitted (untuk review ulang)
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('proposals.show', $proposal) }}" 
                   class="rounded-md bg-gray-700 px-4 py-2 text-sm font-medium text-white hover:bg-gray-600">
                    Batal
                </a>
                <button 
                    type="submit"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    @if($proposal->status === 'need_revision')
                        💾 Simpan & Submit Revisi
                    @else
                        💾 Simpan Perubahan
                    @endif
                </button>
            </div>
        </form>
    </div>
</x-layout>