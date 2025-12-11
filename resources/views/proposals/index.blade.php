
<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('proposals.create') }}" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
            Buat Proposal usulan Baru
        </a>
    </div>

    @if ($proposals->count() > 0)
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
            <table class="min-w-full divide-y divide-gray-700 bg-gray-800">
                <thead class="bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Judul</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-sm font-semibold text-white">Dibuat</th>
                        <th scope="col" class="relative px-6 py-3 text-left text-sm font-semibold text-white">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach ($proposals as $proposal)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-200">{{ $proposal->judul }}</td>
                            <td class="px-6 py-4 text-sm text-gray-200">
                                @php
                                    $statusClass = match($proposal->status) {
                                        'draft' => 'bg-gray-700 text-gray-200',
                                        'submitted' => 'bg-blue-700 text-blue-200',
                                        'under_review' => 'bg-yellow-700 text-yellow-200',
                                        'accepted' => 'bg-green-700 text-green-200',
                                        default => 'bg-red-700 text-red-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst($proposal->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">{{ $proposal->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-sm text-right">
                                <a href="{{ route('proposals.show', $proposal) }}" class="text-indigo-400 hover:text-indigo-300">Lihat</a>
                                @if($proposal->status === 'draft')
                                    <a href="{{ route('proposals.edit', $proposal) }}" class="ml-4 text-blue-400 hover:text-blue-300">Edit</a>
                                    <form method="POST" action="{{ route('proposals.destroy', $proposal) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ml-4 text-red-400 hover:text-red-300" onclick="return confirm('Yakin hapus?')">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $proposals->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-400 text-lg">Belum ada proposal. <a href="{{ route('proposals.create') }}" class="text-indigo-400 hover:text-indigo-300">Buat proposal baru</a></p>
        </div>
    @endif
</x-layout>