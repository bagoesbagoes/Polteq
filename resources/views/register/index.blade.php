{{-- resources/views/register/index.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <title>Halaman Signup</title>
</head>

<body class="h-full">
    <main class="form-registration">
        <div class="mx-auto max-w-3xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">

                <!-- Header -->
                <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                    <h2 class="mt-10 text-center text-2xl font-bold tracking-tight text-white">
                        Register your account
                    </h2>
                </div>

                <!-- Form Box -->
                <div class="mt-10 sm:mx-auto sm:w-full">

                    <form action="/signup" method="POST" class="space-y-6">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-100">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                required
                                autocomplete="name"
                                value="{{ old('name') }}"
                                class="mt-1 block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white 
                                       outline -outline-offset-1 outline-white/10 
                                       placeholder:text-gray-500 
                                       focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 
                                       sm:text-sm @error('name') border border-red-500 @enderror"  
                            />
                            @error('name')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>                              
                            @enderror
                        </div>                  
                      
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-100">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                class="mt-1 block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white 
                                       outline -outline-offset-1 outline-white/10 
                                       placeholder:text-gray-500 
                                       focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 
                                       sm:text-sm @error('email') border border-red-500 @enderror"
                            />
                            @error('email')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NIDN/NUPTK -->
                        <div>
                            <label for="nidn_nuptk" class="block text-sm font-medium text-gray-100">
                                NIDN / NUPTK <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="nidn_nuptk"
                                type="text"
                                name="nidn_nuptk"
                                value="{{ old('nidn_nuptk') }}"
                                required
                                inputmode="numeric"
                                placeholder="Contoh: 1234567890 (NIDN) atau 1234567890123456 (NUPTK)"
                                class="mt-1 block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white 
                                    outline -outline-offset-1 outline-white/10 
                                    placeholder:text-gray-500 
                                    focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 
                                    sm:text-sm @error('nidn_nuptk') border border-red-500 @enderror"
                            />
                            <p class="mt-1 text-xs text-gray-400">NIDN: 10 digit | NUPTK: 16 digit (angka saja, tanpa spasi)</p>
                            @error('nidn_nuptk')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- JABATAN FUNGSIONAL -->
                        <div>
                            <label for="jabatan_fungsional" class="block text-sm font-medium text-gray-100">
                                Jabatan Fungsional <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="jabatan_fungsional"
                                name="jabatan_fungsional"
                                required
                                class="mt-1 block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white 
                                    outline -outline-offset-1 outline-white/10 
                                    focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 
                                    sm:text-sm @error('jabatan_fungsional') border border-red-500 @enderror"
                            >
                                <option value="" class="bg-gray-800">-- Pilih Jabatan Fungsional --</option>
                                <option value="Asisten Ahli" class="bg-gray-800" {{ old('jabatan_fungsional') == 'Asisten Ahli' ? 'selected' : '' }}>
                                    Asisten Ahli
                                </option>
                                <option value="Lektor" class="bg-gray-800" {{ old('jabatan_fungsional') == 'Lektor' ? 'selected' : '' }}>
                                    Lektor
                                </option>
                                <option value="Lektor Kepala" class="bg-gray-800" {{ old('jabatan_fungsional') == 'Lektor Kepala' ? 'selected' : '' }}>
                                    Lektor Kepala
                                </option>
                                <option value="Guru Besar" class="bg-gray-800" {{ old('jabatan_fungsional') == 'Guru Besar' ? 'selected' : '' }}>
                                    Guru Besar
                                </option>
                            </select>
                            <p class="mt-1 text-xs text-gray-400">Pilih sesuai jabatan fungsional Anda saat ini</p>
                            @error('jabatan_fungsional')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- PROGRAM STUDI --}}

                        <div>
                            <label for="prodi" class="block text-sm font-medium text-gray-100">
                                Program Studi <span class="text-red-500">*</span>
                            </label>
                            <select
                                id="prodi"
                                name="prodi"
                                required
                                class="mt-1 block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white 
                                    outline -outline-offset-1 outline-white/10 
                                    focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 
                                    sm:text-sm @error('prodi') border border-red-500 @enderror"
                            >
                                <option value="" class="bg-gray-800">-- Pilih Program Studi --</option>
                                <option value="English for Business & Professional Communication" class="bg-gray-800" {{ old('prodi') == 'English for Business & Professional Communication' ? 'selected' : '' }}>
                                    English for Business & Professional Communication
                                </option>
                                <option value="Bisnis Kreatif" class="bg-gray-800" {{ old('prodi') == 'Bisnis Kreatif' ? 'selected' : '' }}>
                                    Bisnis Kreatif
                                </option>
                                <option value="Teknologi Produksi Tanaman Perkebunan" class="bg-gray-800" {{ old('prodi') == 'Teknologi Produksi Tanaman Perkebunan' ? 'selected' : '' }}>
                                    Teknologi Produksi Tanaman Perkebunan
                                </option>
                                <option value="Teknologi Pangan" class="bg-gray-800" {{ old('prodi') == 'Teknologi Pangan' ? 'selected' : '' }}>
                                    Teknologi Pangan
                                </option>
                            </select>
                            <p class="mt-1 text-xs text-gray-400">Pilih program studi tempat Anda mengajar</p>
                            @error('prodi')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-100">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="mt-1 block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white 
                                       outline -outline-offset-1 outline-white/10 
                                       placeholder:text-gray-500 
                                       focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 
                                       sm:text-sm @error('password') border border-red-500 @enderror"
                            />
                            <p class="mt-1 text-xs text-gray-400">Minimal 5 karakter</p>
                            @error('password')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="flex w-full justify-center rounded-md bg-indigo-500 
                                   px-3 py-1.5 text-sm font-semibold text-white 
                                   hover:bg-indigo-400 
                                   focus-visible:outline-2 focus-visible:outline-offset-2 
                                   focus-visible:outline-indigo-500">
                            Register 
                        </button>

                        <small class="text-white">
                            Sudah memiliki akun ? 
                            <a href="/signin" class="text-indigo-500 hover:text-indigo-400">Sign in disini</a>
                        </small>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>