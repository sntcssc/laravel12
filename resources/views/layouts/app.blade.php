<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">
    <nav class="bg-white dark:bg-gray-800 shadow p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <a href="/" class="text-xl font-bold text-gray-900 dark:text-white">{{ config('app.name') }}</a>
            <div class="flex gap-4">
                @auth
                    <a href="{{ route('users.index') }}" class="text-gray-600 dark:text-gray-300">Users</a>
                    <a href="{{ route('roles.index') }}" class="text-gray-600 dark:text-gray-300">Roles</a>
                    <a href="{{ route('permissions.index') }}" class="text-gray-600 dark:text-gray-300">Permissions</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-600 dark:text-gray-300">Logout</button>
                    </form>
                @endauth
                <button onclick="toggleTheme()" class="text-gray-600 dark:text-gray-300">Toggle Theme</button>
            </div>
        </div>
    </nav>
    <nav class="bg-white dark:bg-gray-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex space-x-4">
                    <a href="/users" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ Route::is('users.*') ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-200' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}">Users</a>
                    @can('view-roles')
                        <a href="/roles" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ Route::is('roles.*') ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-200' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}">Roles</a>
                    @endcan
                    @can('view-permissions')
                        <a href="/permissions" class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ Route::is('permissions.*') ? 'bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-200' : 'hover:bg-gray-100 dark:hover:bg-gray-700' }}">Permissions</a>
                    @endcan
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm">{{ auth()->user()->name }}</span>
                    <button x-on:click="darkMode = !darkMode" class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        <span x-text="darkMode ? 'Light' : 'Dark'"></span>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    <main>
        {{ $slot }}
    </main>
    <script>
        function toggleTheme() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }
    </script>
    @livewireScripts
</body>
</html>