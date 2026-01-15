{{-- resources/views/profile.blade.php --}}
<x-layout>
  <x-slot:title>{{ $title }}</x-slot>

<html lang="en" class="h-full bg-gray-900">

<body class="h-full">

  <div class="min-h-full">
    <main>
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="px-4 sm:px-0">
    <h3 class="text-base/7 font-semibold text-white">Informasi personel</h3>
  </div>

<!-- PROFIL PENGGUNA -->
  <div class="mt-6 border-t border-white/10">
    <dl class="divide-y divide-white/10">
      <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <dt class="text-sm/6 font-medium text-gray-100">name</dt>
        <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                    {{ $user->name }}  
        </dd>
      </div>

      <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <dt class="text-sm/6 font-medium text-gray-100">Username</dt>
        <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                    {{ $user->username }}  
        </dd>
      </div>
      
      <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
        <dt class="text-sm/6 font-medium text-gray-100">Email address</dt>
        <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                    {{ $user->email }}  
        </dd>
      </div>

      {{-- Role --}}
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-100">Role</dt>
                <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                        {{ $user->role === 'admin' ? 'bg-purple-700 text-purple-200' : '' }}
                        {{ $user->role === 'reviewer' ? 'bg-blue-700 text-blue-200' : '' }}
                        {{ $user->role === 'publisher' ? 'bg-green-700 text-green-200' : '' }}">
                        {{ ucfirst($user->role) }} 
                    </span>
                </dd>
            </div>

            {{-- Member Since --}}
            <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                <dt class="text-sm/6 font-medium text-gray-100">Bergabung sejak</dt>
                <dd class="mt-1 text-sm/6 text-gray-400 sm:col-span-2 sm:mt-0">
                    {{ $user->created_at->format('d F Y') }} 
                </dd>
            </div>

            {{-- Tombol Edit Profile --}}
            <div class="mt-6 flex justify-end">
                <a href="{{ route('profile.edit') }}"  {{-- 👈 Pakai route name --}}
                  class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                    Edit Profile
                </a>
            </div>
    </dl>
  </div>
</x-layout>