{{-- resources/views/register/index.blade.php --}}
<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <title> Halaman Signup</title>
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
                        <!-- name -->

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-100">
                                Name
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
                              <div class="text-red-500 text-sm mt-1">
                                {{ $message }}                        
                              </div>                              
                            @enderror
                        </div>                  

                        <!-- username -->

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-100">
                                Username
                            </label>

                            <input
                                id="username"
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autocomplete="name"
                                class="mt-1 block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white 
                                       outline -outline-offset-1 outline-white/10 
                                       placeholder:text-gray-500 
                                       focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 
                                       sm:text-sm @error('username') border border-red-500 @enderror"
                            />

                            @error('username')
                              <div class="text-red-500 text-sm mt-1">
                                {{ $message }}                        
                              </div>
                            @enderror
                        </div>
                      
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-100">
                                Email address
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
                              <div class="text-red-500 text-sm mt-1">
                                {{ $message }}                        
                              </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-100">
                                Password
                            </label>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                class="mt-1 block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white 
                                       outline -outline-offset-1 outline-white/10 
                                       placeholder:text-gray-500 
                                       focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 
                                       sm:text-sm @error('password') border border-red-500 @enderror"
                            />
                            @error('password')
                              <div class="text-red-500 text-sm mt-1">
                                {{ $message }}                        
                              </div>
                            @enderror
                        </div>

                        <!-- Submit -->
                        <button
                            type="submit"
                            class="flex w-full justify-center rounded-md bg-indigo-500 
                                   px-3 py-1.5 text-sm font-semibold text-white 
                                   hover:bg-indigo-400 
                                   focus-visible:outline-2 focus-visible:outline-offset-2 
                                   focus-visible:outline-indigo-500">
                           Register 
                        </button>

                        
                        
                      <small class="text-white"> Already have account ? <a href="/signin" class="text-indigo-500 hover:text-indigo-400"> Sign in here</a></small>

                    </form>
                </div>
            </div>

        </div>
    </main>
</body>
</html>