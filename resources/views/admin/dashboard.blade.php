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
                        NIDN/NUPTK <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nidn_nuptk" 
                        id="nidn_nuptk"
                        value="{{ old('nidn_nuptk') }}"
                        required
                        maxlength="17"
                        pattern="[0-9]{17}"
                        class="block w-full rounded-md bg-gray-700 border border-gray-600 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="17 digit angka"
                    >
                    <p class="mt-1 text-xs text-gray-400">17 digit angka tanpa spasi</p>
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
    <div class="mt-6 bg-gray-800 rounded-lg p-6 shadow-lg">
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
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-400 text-center py-8">Belum ada reviewer terdaftar.</p>
        @endif
    </div>
</x-layout>