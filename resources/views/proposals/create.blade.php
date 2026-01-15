<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    {{-- ✅ ERROR MESSAGES SECTION --}}
    @if($errors->any())
        <div class="mb-6 rounded-md bg-red-900/20 p-4 border border-red-700">
            <div class="flex">
                <div class="shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-400">Ada kesalahan pada input Anda:</h3>
                    <ul class="mt-2 text-sm text-red-300 list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('proposals.store') }}" enctype="multipart/form-data" class="bg-gray-800 shadow sm:rounded-lg p-6">
        @csrf
        <div class="space-y-6">
            <div>
                <label for="judul" class="block text-sm font-medium leading-6 text-white">Judul Usulan</label>
                <input type="text" name="judul" id="judul" value="{{ old('judul') }}" required class="mt-2 block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6" />
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-medium leading-6 text-white">Deskripsi</label>
                <textarea name="deskripsi" id="deskripsi" rows="6" required class="mt-2 block w-full rounded-md border-0 bg-gray-700 px-3 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-600 placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-500 sm:text-sm sm:leading-6">{{ old('deskripsi') }}</textarea>
            </div>

            <div>
                <label for="file_usulan" class="block text-sm font-medium leading-6 text-white">Upload PDF</label>
                <input type="file" name="file_usulan" id="file_usulan" accept=".pdf" required class="mt-2 block w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700" />
            </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <a href="{{ route('proposals.index') }}" class="rounded-md bg-gray-700 px-4 py-2 text-sm font-medium text-white hover:bg-gray-600">Batal</a>
            <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">Simpan</button>
        </div>
    </form>
</x-layout>