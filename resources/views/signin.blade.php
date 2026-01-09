<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

    <title>Halaman Signin</title>
</head>

<body class="h-full">
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">

                  @if (session()->has('loginError'))
                    <div class="bg-red-500 text-white px-4 py-2 rounded mb-4">
                        {{ session('loginError') }}
                        <button type="button" 
                                class="float-right" 
                                aria-label="Close" 
                                onclick="this.parentElement.style.display='none';">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                  @endif


                <!-- Title -->
                <div class="sm:mx-auto sm:w-full sm:max-w-sm">
                    <h1 class="mt-10 text-center text-2xl font-bold tracking-tight text-white">
                        Sign in to your account
                    </h1>
                </div>

                <!-- Form Container -->
                <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">

                    @if(session()->has('success'))
                        <div class="bg-green-500 text-white p-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="/signin" method="POST" class="space-y-6">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-100">Email address</label>
                            <div class="mt-2">
                                <input 
                                    id="email" 
                                    name="email" 
                                    type="email" 
                                    required 
                                    autocomplete="email"
                                    class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline outline-white/10 placeholder:text-gray-500 
                                    focus:outline-2 focus:outline-indigo-500"
                                >
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-100">Password</label>
                            <div class="mt-2">
                                <input 
                                    id="password" 
                                    name="password" 
                                    type="password" 
                                    required 
                                    autocomplete="current-password"
                                    class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline outline-white/10 placeholder:text-gray-500 
                                    focus:outline-2 focus:outline-indigo-500"
                                >
                            </div>
                        </div>

                        <!-- Button -->
                        <div>
                            <button 
                                type="submit"
                                class="flex w-full justify-center rounded-md bg-indigo-500 px-3 py-1.5 text-sm font-semibold text-white 
                                hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500"
                            >
                                Sign in
                            </button>
                        </div>
                    </form>

                    <small class="text-white">
                        Tidak punya akun ?
                        <a href="/signup" class="text-indigo-500 hover:text-indigo-400">Daftar disini</a>
                    </small>
                </div>

            </div>
        </div>
    </main>
</body>
</html>
