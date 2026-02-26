{{-- resources/views/components/navbar.blade.php --}}
<nav class="bg-gray-800/50">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <div class="flex items-center space-x-1">
                        {{-- Logo Image --}}
                        <img src="{{ asset('image/LogoNavbar.png') }}" 
                            alt="PRIMA Logo"
                            class="h-20 w-20 rounded-lg object-cover shadow-md">
                        {{-- Brand Text --}}
                        <h1 class="text-white font-TimesNewRoman text-xl font-bold">PRIMA</h1>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4  " >
                        <!-- Current: "bg-gray-950/50 text-white", Default: "text-gray-300 hover:bg-white/5 hover:text-white" -->
                        <x-nav-link href="/UsulanPenelitian" :active="request()->is('UsulanPenelitian')">Usulan Penelitian</x-nav-link>
                        
                        @if (Auth::check() && in_array(Auth::user()->role, ['admin', 'publisher']))
                            <x-nav-link href="/LaporanPenelitian" :active="request()->is('LaporanPenelitian')">Laporan Penelitian</x-nav-link>
                        @endif

                        <x-nav-link href="/UsulanPKM" :active="request()->is('UsulanPKM')">Usulan PKM</x-nav-link>
                
                        @if (Auth::check() && in_array(Auth::user()->role, ['admin', 'publisher']))
                            <x-nav-link href="/LaporanPKM" :active="request()->is('LaporanPKM')">Laporan PKM</x-nav-link>
                        @endif
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <!-- Profile dropdown -->
                    <el-dropdown class="relative ml-3">
                        <button class="relative flex max-w-xs items-center gap-2 rounded-full focus-visible:outline-2 cursor-pointer focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                            
                            <span class="sr-only">Open user menu</span>
                            
                            
                            {{-- Nama User --}}
                            <span class="hidden md:block text-sm font-medium text-white">
                                {{ Auth::user()->name }}
                            </span>
                            
                            {{-- Dropdown Icon --}}
                            <svg class="size-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <el-menu anchor="bottom end" popover class="w-48 origin-top-right rounded-md bg-gray-800 py-1 outline-1 -outline-offset-1 outline-white/10 transition transition-discrete [--anchor-gap:--spacing(2)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in">
                            <a href="/profile" class="block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:outline-hidden">Your profile</a>
                            <form method="POST" action="{{ route('signout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-white/5 focus:outline-hidden">
                                    Sign out
                                </button>
                            </form>
                        </el-menu>
                    </el-dropdown>
                </div>
            </div>

            <div class="-mr-2 flex md:hidden">
                <!-- Mobile menu button -->
                <button type="button" command="--toggle" commandfor="mobile-menu" class="relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-white/5 hover:text-white focus:outline-2 focus:outline-offset-2 focus:outline-indigo-500">
                    <span class="absolute -inset-0.5"></span>
                    <span class="sr-only">Open main menu</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 in-aria-expanded:hidden">
                            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon" aria-hidden="true" class="size-6 not-in-aria-expanded:hidden">
                            <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                </button>
            </div>
        </div>
        </div>

        <el-disclosure id="mobile-menu" hidden class="block md:hidden">
            <div class="space-y-1 px-1 pt-1 pb-3 sm:px-3">
                <!-- Current: "bg-gray-950/50 text-white", Default: "text-gray-300 hover:bg-white/5 hover:text-white" -->
                    
                    <x-nav-link href="/UsulanPenelitian" :active="request()->is('UsulanPenelitian')">ManajemenUsulan</x-nav-link>
                    <x-nav-link href="/LaporanPenelitian" :active="request()->is('LaporanPenelitian')">Laporan Penelitian</x-nav-link>
                    <x-nav-link href="/UsulanPKM" :active="request()->is('UsulanPKM')">Usulan PKM</x-nav-link>
                    <x-nav-link href="/LaporanPKM" :active="request()->is('LaporanPKM')">Laporan PKM</x-nav-link>
            </div>
            <div class="border-t border-white/10 pt-4 pb-3">
                <div class="border-t border-white/10 pt-4 pb-3">
                {{-- User Info Header (BARU!) --}}
                    <div class="ml-3">
                        <div class="text-base/5 font-medium text-white">{{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>

                {{-- Menu Items --}}
                <div class="mt-3 space-y-1 px-2">
                    <a href="/profile" class="block px-4 py-2 text-sm text-gray-300 focus:bg-white/5 focus:outline-hidden">Your profile</a>
                    {{-- Fix: Sign out di mobile pakai form POST juga! --}}
                    <form method="POST" action="{{ route('signout') }}" class="block">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-white/5 focus:outline-hidden">
                            Sign out
                        </button>
                    </form>
                </div>

            </div>
        </el-disclosure>
    </nav>