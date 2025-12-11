<x-layout>
    <x-slot:title>Upload Proposal</x-slot>
    <form action="{{ url('/publisher/upload') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Judul</label>
        <input type="text" name="judul" value="{{ old('judul') }}" />
        @error('judul') <div class="text-red-500">{{ $message }}</div> @enderror

        <label>Deskripsi</label>
        <textarea name="deskripsi">{{ old('deskripsi') }}</textarea>

        <label>File (PDF)</label>
        <input type="file" name="file_usulan" accept="application/pdf" />
        @error('file_usulan') <div class="text-red-500">{{ $message }}</div> @enderror

        <button type="submit">Upload</button>

        <a href="{{ asset('storage/' . $proposal->file_usulan) }}" target="_blank">Download PDF</a>

    </form>
</x-layout>
