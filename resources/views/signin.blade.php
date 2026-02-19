<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-950">
<head>
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Sansation:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Zalando+Sans+Expanded:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <title>Sign In</title>
</head>

<body class="h-full bg-linear-to-br from-gray-950 via-gray-900 to-gray-950">
    <main class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            
            {{-- Header Title --}}
            <div class="text-center mb-1">
                <h1 class="text-2xl md:text-3xl font-bold text-white tracking-wide leading-tight" style="font-family: 'Sansation'">
                    <span class="block">Platform Riset, Inovasi &</span>
                    <span class="block">Pengabdian Masyarakat</span>
                </h1>
            </div>

            {{-- Logo --}}
            <div class="flex justify-center mb-1">
                    <img src="{{ asset('image/Logo4.png') }}"  
                         class="h-64 w-64 object-contain mb-1 rounded-lg">
            </div>

            {{-- PRIMA Brand --}}
            <h2 class="text-center text-2xl font-bold text-white tracking-[0.5em] mb-1" style="font-family: 'Sansation', sans-serif;">
                PRIMA
            </h2>

            {{-- Error Alert --}}
            @if (session()->has('loginError'))
                <div class="mb-6 rounded-lg bg-red-900/20 p-4 border border-red-700">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-red-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm text-red-300">{{ session('loginError') }}</p>
                        </div>
                        <button type="button" 
                                class="text-red-400 hover:text-red-300"
                                onclick="this.parentElement.parentElement.remove()">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            {{-- Success Alert --}}
            @if(session()->has('success'))
                <div class="mb-6 rounded-lg bg-green-900/20 p-4 border border-green-700">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-green-300">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Form --}}
            <form action="/signin" method="POST" class="space-y-6">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-200 mb-2">
                        Email address
                    </label>
                    <input 
                        id="email" 
                        name="email" 
                        type="email" 
                        required 
                        autofocus
                        autocomplete="email"
                        value="{{ old('email') }}"
                        class="mt-1 block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white 
                                       outline -outline-offset-1 outline-white/10 
                                       placeholder:text-gray-500 
                                       focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 
                                       sm:text-sm @error('email') border border-red-500 @enderror"  
                        placeholder="nama@email.com"
                    >
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-medium text-gray-200">
                            Password
                        </label>
                        <a href="{{ route('password.request') }}" 
                           class="text-sm font-medium text-indigo-400 hover:text-indigo-300 transition-colors">
                            Lupa password?
                        </a>
                    </div>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        required 
                        autocomplete="current-password"
                        class="mt-1 block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white 
                                       outline -outline-offset-1 outline-white/10 
                                       placeholder:text-gray-500 
                                       focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 
                                       sm:text-sm @error('password') border border-red-500 @enderror"  
                        placeholder="••••••••"
                    >
                </div>

                {{-- Submit Button --}}
                <div>
                    <button 
                        type="submit"
                        class="mt-1 block w-full rounded-md bg-indigo-500 px-3 py-1.5 text-base text-white 
                                       outline -outline-offset-1 outline-indigo-500 hover:bg-indigo-400  
                                       focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 ">  
                        Sign in
                    </button>
                </div>
            </form>

            {{-- Footer --}}
            <p class="mt-6 text-center text-sm text-gray-400">
                Tidak punya akun? 
                <a href="/signup" class="font-medium text-indigo-400 hover:text-indigo-300 transition-colors">
                    Daftar disini
                </a>
            </p>
        </div>
    </main>
</body>
</html>