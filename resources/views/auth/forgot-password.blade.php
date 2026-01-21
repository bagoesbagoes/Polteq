<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <title>{{ $title }}</title>
</head>

<body class="h-full">
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">

                <!-- Header -->
                <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                    <h2 class="mt-10 text-center text-2xl font-bold tracking-tight text-white">
                        Lupa Password?
                    </h2>
                    <p class="mt-2 text-center text-sm text-gray-400">
                        Masukkan email dan NIDN/NUPTK Anda untuk mereset password
                    </p>
                </div>

                <!-- Form Container -->
                <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">

                    {{-- Error Messages --}}
                    @if($errors->any())
                        <div class="mb-6 rounded-md bg-red-900/20 p-4 border border-red-700">
                            <div class="flex">
                                <div class="shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-400">Terjadi kesalahan:</h3>
                                    <ul class="mt-2 text-sm text-red-300 list-disc list-inside space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('password.verify') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-100">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    required 
                                    autocomplete="email"
                                    value="{{ old('email') }}"
                                    placeholder="contoh@mail.com"
                                    class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline outline-white/10 placeholder:text-gray-500 
                                    focus:outline-2 focus:outline-indigo-500 @error('email') border-2 border-red-500 @enderror"
                                >
                            </div>
                            <p class="mt-1 text-xs text-gray-400">Email yang Anda gunakan saat mendaftar</p>
                        </div>

                        <!-- NIDN/NUPTK -->
                        <div>
                            <label for="nidn_nuptk" class="block text-sm font-medium text-gray-100">
                                NIDN/NUPTK <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input 
                                    id="nidn_nuptk" 
                                    name="nidn_nuptk" 
                                    type="text" 
                                    required 
                                    minlength="10"
                                    maxlength="16"
                                    pattern="[0-9]{10,16}"
                                    value="{{ old('nidn_nuptk') }}"
                                    placeholder=" 10 / 16 digit angka"
                                    class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline outline-white/10 placeholder:text-gray-500 
                                    focus:outline-2 focus:outline-indigo-500 @error('nidn_nuptk') border-2 border-red-500 @enderror"
                                >
                            </div>
                            <p class="mt-1 text-xs text-gray-400">nidn :10 digit | nuptk : 16 digit .angka tanpa spasi atau tanda baca</p>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <button 
                                type="submit"
                                class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm font-semibold text-white 
                                hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                            >
                                Verifikasi Identitas
                            </button>
                        </div>

                        <!-- Back to Login -->
                        <div class="text-center">
                            <a href="{{ route('signin') }}" class="text-sm text-indigo-400 hover:text-indigo-300">
                                ← Kembali ke Login
                            </a>
                        </div>
                    </form>

                    <!-- Info Box -->
                    <div class="mt-6 rounded-md bg-blue-900/20 p-4 border border-blue-700">
                        <div class="flex">
                            <div class="shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-400">Informasi Keamanan</h3>
                                <div class="mt-2 text-sm text-blue-300">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Email dan NIDN/NUPTK harus sesuai dengan data pendaftaran Anda</li>
                                        <li>Token reset password berlaku selama 1 jam</li>
                                        <li>Maksimal 3 kali percobaan dalam 10 menit</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
</body>
</html>