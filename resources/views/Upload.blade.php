{{-- resources/views/Upload.blade.php --}}
<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>
    
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        
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

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-12">
                
                {{-- Section: Profile Information --}}
                <div class="border-b border-white/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-white">Edit Profile</h2>
                    <p class="mt-1 text-sm/6 text-gray-400">Update your personal information.</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        
                        {{-- Full Name --}}
                        <div class="sm:col-span-4">
                            <label for="name" class="block text-sm/6 font-medium text-white">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input 
                                    id="name" 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name', $user->name) }}"
                                    required
                                    class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                            </div>
                        </div>

                        {{-- Username --}}
                        {{-- <div class="sm:col-span-4">
                            <label for="username" class="block text-sm/6 font-medium text-white">
                                Username <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input 
                                    id="username" 
                                    type="text" 
                                    name="username" 
                                    value="{{ old('username', $user->username) }}"
                                    required
                                    class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                            </div>
                            <p class="mt-2 text-sm text-gray-400">Username harus unique dan akan digunakan untuk login.</p>
                        </div> --}}

                        {{-- Email --}}
                        <div class="sm:col-span-4">
                            <label for="email" class="block text-sm/6 font-medium text-white">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input 
                                    id="email" 
                                    type="email" 
                                    name="email" 
                                    value="{{ old('email', $user->email) }}"
                                    required
                                    autocomplete="email" 
                                    class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                            </div>
                        </div>

                        {{-- Password (Optional) --}}
                        <div class="sm:col-span-4">
                            <label for="password" class="block text-sm/6 font-medium text-white">
                                Password Baru (Opsional)
                            </label>
                            <div class="mt-2">
                                <input 
                                    id="password" 
                                    type="password" 
                                    name="password" 
                                    placeholder="Kosongkan jika tidak ingin mengubah password"
                                    autocomplete="new-password"
                                    class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                            </div>
                            <p class="mt-2 text-sm text-gray-400">Minimal 5 karakter.</p>
                        </div>

                        {{-- NIDN/NUPTK --}}
                        <div class="sm:col-span-4">
                            <label for="nidn_nuptk" class="block text-sm/6 font-medium text-white">
                                NIDN/NUPTK <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input 
                                    id="nidn_nuptk" 
                                    type="text" 
                                    name="nidn_nuptk" 
                                    value="{{ old('nidn_nuptk', $user->nidn_nuptk) }}"
                                    required
                                    minlength="10"
                                    maxlength="16"
                                    pattern="[0-9]{10,16}"
                                    placeholder="Contoh: 1234567890 / 12345678901234567"
                                    class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                            </div>
                            <p class="mt-2 text-sm text-gray-400">NIDN : 10 digit | NUPTK : 16 digit. Angka tanpa spasi atau tanda baca.</p>
                        </div>


                        {{-- JABATAN FUNGSIONAL --}}
                        <div class="sm:col-span-4">
                            <label for="jabatan_fungsional" class="block text-sm/6 font-medium text-white">
                                Jabatan Fungsional <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input 
                                    id="jabatan_fungsional" 
                                    type="text" 
                                    name="jabatan_fungsional" 
                                    value="{{ old('jabatan_fungsional', $user->jabatan_fungsional) }}"
                                    required
                                    placeholder="Contoh: Lektor, Asisten Ahli"
                                    class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section: Account Info (Read-Only) --}}
                <div class="border-b border-white/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-white">Informasi akun</h2>
                    <p class="mt-1 text-sm/6 text-gray-400">Informasi akun yang tidak dapat diubah.</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        
                        {{-- Role --}}
                        <div class="sm:col-span-3">
                            <label class="block text-sm/6 font-medium text-white">Role</label>
                            <div class="mt-2">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $user->role === 'admin' ? 'bg-purple-700 text-purple-200' : '' }}
                                    {{ $user->role === 'reviewer' ? 'bg-blue-700 text-blue-200' : '' }}
                                    {{ $user->role === 'publisher' ? 'bg-green-700 text-green-200' : '' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>

                        {{-- Member Since --}}
                        <div class="sm:col-span-3">
                            <label class="block text-sm/6 font-medium text-white">Terdaftar sejak</label>
                            <div class="mt-2">
                                <p class="text-sm text-gray-400">{{ $user->created_at->format('d F Y') }}</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Action Buttons --}}
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="/profile" class="text-sm/6 font-semibold text-white hover:text-gray-300">
                    Cancel
                </a>
                <button 
                    type="submit"
                    class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Save Changes
                </button>
            </div>
      </form>
    </div>
</x-layout>