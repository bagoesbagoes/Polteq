{{-- resources/views/admin/dashboard.blade.php --}}
<x-layout>
    <x-slot:title>Admin Dashboard</x-slot:title>

    {{-- Success Message --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="mb-6 rounded-md bg-green-900/20 p-4 border border-green-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400 mr-3" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium text-green-400">{{ session('success') }}</p>
                </div>
                <button @click="show = false" class="text-green-400 hover:text-green-300">×</button>
            </div>
        </div>
    @endif

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
        
        {{-- SECTION 1: Statistik Usulan --}}
        <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Statistik Usulan
            </h2>

            @php
                $totalProposals = \App\Models\Proposal::count();
                $draftCount = \App\Models\Proposal::where('status', 'draft')->count();
                $submittedCount = \App\Models\Proposal::where('status', 'submitted')->count();
                $acceptedCount = \App\Models\Proposal::where('status', 'accepted')->count();
                $revisionCount = \App\Models\Proposal::where('status', 'need_revision')->count();
            @endphp

            <div class="space-y-4">
                {{-- Total Usulan --}}
                <div class="flex items-center justify-between p-4 bg-gray-700 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-400">Total Usulan</p>
                        <p class="text-2xl font-bold text-white">{{ $totalProposals }}</p>
                    </div>
                    <div class="p-3 bg-indigo-600 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>

                {{-- Draft --}}
                <div class="flex items-center justify-between p-3 bg-gray-700 rounded">
                    <span class="text-sm text-gray-300">Draft</span>
                    <span class="px-3 py-1 bg-gray-600 text-white rounded-full text-sm font-medium">{{ $draftCount }}</span>
                </div>

                {{-- Submitted --}}
                <div class="flex items-center justify-between p-3 bg-gray-700 rounded">
                    <span class="text-sm text-gray-300">Submitted</span>
                    <span class="px-3 py-1 bg-blue-600 text-white rounded-full text-sm font-medium">{{ $submittedCount }}</span>
                </div>

                {{-- Accepted --}}
                <div class="flex items-center justify-between p-3 bg-gray-700 rounded">
                    <span class="text-sm text-gray-300">Disetujui</span>
                    <span class="px-3 py-1 bg-green-600 text-white rounded-full text-sm font-medium">{{ $acceptedCount }}</span>
                </div>

                {{-- Need Revision --}}
                <div class="flex items-center justify-between p-3 bg-gray-700 rounded">
                    <span class="text-sm text-gray-300">Perlu Revisi</span>
                    <span class="px-3 py-1 bg-red-600 text-white rounded-full text-sm font-medium">{{ $revisionCount }}</span>
                </div>
            </div>

            {{-- Link ke Browse Proposals --}}
            <div class="mt-6">
                <a href="{{ route('proposals.browse') }}" class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition">
                    Lihat Semua Usulan →
                </a>
            </div>
        </div>

        {{-- SECTION 2: Form Buat Akun Reviewer --}}
        <div class="bg-gray-800 rounded-lg p-6 shadow-lg">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
                Buat Akun Reviewer Baru
            </h2>

            <form method="POST" action="{{ route('admin.store-reviewer') }}" class="space-y-4">
                @csrf

                {{-- Nama Lengkap --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name"
                        value="{{ old('name') }}"
                        required
                        class="block w-full rounded-md bg-gray-700 border border-gray-600 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Contoh: Dr. Ahmad Santoso, M.Kom"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email"
                        value="{{ old('email') }}"
                        required
                        class="block w-full rounded-md bg-gray-700 border border-gray-600 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="reviewer@example.com"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NIDN/NUPTK --}}
                <div>
                    <label for="nidn_nuptk" class="block text-sm font-medium text-gray-300 mb-2">
                        NIDN / NUPTK <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nidn_nuptk" 
                        id="nidn_nuptk"
                        value="{{ old('nidn_nuptk') }}"
                        required
                        maxlength="17"
                        pattern="[0-9]{10,16}"
                        class="block w-full rounded-md bg-gray-700 border border-gray-600 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Contoh : 1234567890 / 12345678901234567"
                    >
                    <p class="mt-1 text-xs text-gray-400"> nidn :10 digit | nuptk : 16 digit .angka tanpa spasi atau tanda baca</p>
                    @error('nidn_nuptk')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jabatan Fungsional --}}
                <div>
                    <label for="jabatan_fungsional" class="block text-sm font-medium text-gray-300 mb-2">
                        Jabatan Fungsional <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="jabatan_fungsional" 
                        id="jabatan_fungsional"
                        value="{{ old('jabatan_fungsional') }}"
                        required
                        class="block w-full rounded-md bg-gray-700 border border-gray-600 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Contoh: Lektor, Asisten Ahli"
                    >
                    @error('jabatan_fungsional')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        required
                        class="block w-full rounded-md bg-gray-700 border border-gray-600 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Minimal 5 karakter"
                    >
                    <p class="mt-1 text-xs text-gray-400">Password akan diberikan ke reviewer</p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit Button --}}
                <div class="pt-4">
                    <button 
                        type="submit"
                        class="w-full flex justify-center items-center px-4 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Akun Reviewer
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- SECTION 3: Daftar Reviewer (Optional - untuk tracking) --}}
    <div class="mt-6 bg-gray-800 rounded-lg p-6 shadow-lg" x-data="{ 
        editModal: false, 
        editingReviewer: null,
        openEditModal(reviewer) {
            this.editingReviewer = reviewer;
            this.editModal = true;
        }
        }">
        <h2 class="text-xl font-bold text-white mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Daftar Reviewer
        </h2>

        @php
            $reviewers = \App\Models\User::where('role', 'reviewer')->latest()->get();
        @endphp

        @if($reviewers->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-700">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">NIDN/NUPTK</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Jabatan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase">Bergabung</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-300 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($reviewers as $reviewer)
                            <tr class="hover:bg-gray-700/50 transition">
                                <td class="px-4 py-3 text-sm text-white">{{ $reviewer->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-300">{{ $reviewer->email }}</td>
                                <td class="px-4 py-3 text-sm text-gray-300">{{ $reviewer->nidn_nuptk ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-300">{{ $reviewer->jabatan_fungsional ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-400">{{ $reviewer->created_at->format('d M Y') }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Button Edit --}}
                                        <button 
                                            @click="openEditModal({
                                                id: {{ $reviewer->id }},
                                                name: '{{ $reviewer->name }}',
                                                email: '{{ $reviewer->email }}',
                                                nidn_nuptk: '{{ $reviewer->nidn_nuptk }}',
                                                jabatan_fungsional: '{{ $reviewer->jabatan_fungsional }}'
                                            })"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition">
                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>

                                        {{-- Button Hapus --}}
                                        <form method="POST" 
                                            action="{{ route('admin.delete-reviewer', $reviewer) }}" 
                                            class="inline-block"
                                            onsubmit="return confirm('⚠️ PERHATIAN!\n\nAnda akan menghapus akun reviewer:\n{{ $reviewer->name }}\n\nData yang dihapus TIDAK DAPAT dikembalikan!\n\nLanjutkan menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button 
                                                type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition">
                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-400 text-center py-8">Belum ada reviewer terdaftar.</p>
        @endif

        {{-- MODAL EDIT REVIEWER --}}
        <div x-show="editModal" 
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto" 
            style="display: none;">
            {{-- Backdrop --}}
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="editModal" 
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @click="editModal = false"
                    class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75"></div>

                {{-- Modal Content --}}
                <div x-show="editModal"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    
                    <form method="POST" x-bind:action="`/admin/reviewer/${editingReviewer?.id}/update`">
                        @csrf
                        @method('PUT')

                        <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 text-blue-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                <h3 class="text-lg font-medium text-white">Edit Data Reviewer</h3>
                            </div>

                            <div class="space-y-4">
                                {{-- Nama --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="name" 
                                        x-model="editingReviewer.name"
                                        required
                                        class="block w-full rounded-md bg-gray-700 border border-gray-600 text-white px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">
                                        Email <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="email" 
                                        name="email" 
                                        x-model="editingReviewer.email"
                                        required
                                        class="block w-full rounded-md bg-gray-700 border border-gray-600 text-white px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                </div>

                                {{-- NIDN/NUPTK --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">
                                        NIDN/NUPTK <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="nidn_nuptk" 
                                        x-model="editingReviewer.nidn_nuptk"
                                        required
                                        maxlength="16"
                                        class="block w-full rounded-md bg-gray-700 border border-gray-600 text-white px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                </div>

                                {{-- Jabatan --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">
                                        Jabatan Fungsional <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        name="jabatan_fungsional" 
                                        x-model="editingReviewer.jabatan_fungsional"
                                        required
                                        class="block w-full rounded-md bg-gray-700 border border-gray-600 text-white px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                </div>

                                {{-- Password (Opsional) --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">
                                        Password Baru <span class="text-gray-500">(Opsional)</span>
                                    </label>
                                    <input 
                                        type="password" 
                                        name="password"
                                        class="block w-full rounded-md bg-gray-700 border border-gray-600 text-white px-3 py-2 focus:ring-2 focus:ring-blue-500"
                                        placeholder="Kosongkan jika tidak ingin mengubah password">
                                    <p class="mt-1 text-xs text-gray-400">Minimal 5 karakter</p>
                                </div>
                            </div>
                        </div>

                        {{-- Footer --}}
                        <div class="bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                            <button 
                                type="submit"
                                class="w-full sm:w-auto inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm">
                                💾 Simpan Perubahan
                            </button>
                            <button 
                                type="button"
                                @click="editModal = false"
                                class="mt-3 sm:mt-0 w-full sm:w-auto inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-gray-300 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 sm:text-sm">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>