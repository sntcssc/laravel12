<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            {{-- Users --}}
            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Users Managment')" expandable :expanded="request()->routeIs('users.index', 'users.create')">
                    <flux:navlist.item icon="user-group" :href="route('users.index')" :current="request()->routeIs('users.index')" wire:navigate>{{ __('View Users') }}</flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('users.create')" :current="request()->routeIs('users.create')" wire:navigate>{{ __('Add New User') }}</flux:navlist.item>
                </flux:navlist.group>

                 {{-- Roles and Permission --}}
                <flux:navlist.group :heading="__('Roles and Permissions')" expandable :expanded="request()->routeIs('roles.index', 'permissions.create')">
                    <flux:navlist.item icon="user-group" :href="route('roles.index')" :current="request()->routeIs('roles.index')" wire:navigate>{{ __('Roles') }}</flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('permissions.create')" :current="request()->routeIs('permissions.create')" wire:navigate>{{ __('Permissions') }}</flux:navlist.item>
                </flux:navlist.group>

                 {{-- Academics --}}
                <flux:navlist.group :heading="__('Academics')" expandable :expanded="request()->routeIs('programmes.index', 'batches.index', 'sections.index' )">
                    <flux:navlist.item icon="user-group" :href="route('programmes.index')" :current="request()->routeIs('programmes.index')" wire:navigate>{{ __('Programmes') }}</flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('batches.index')" :current="request()->routeIs('batches.index')" wire:navigate>{{ __('Batch') }}</flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('sections.index')" :current="request()->routeIs('sections.index')" wire:navigate>{{ __('Sections') }}</flux:navlist.item>
                </flux:navlist.group>

                 {{-- Students --}}
                <flux:navlist.group :heading="__('Students')" expandable :expanded="request()->routeIs('students.index', 'enrollments.index', 'alumni.index', 'attendances.index', 'leaves.index' )">
                    <flux:navlist.item icon="user-group" :href="route('students.index')" :current="request()->routeIs('students.index')" wire:navigate>{{ __('Students') }}</flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('enrollments.index')" :current="request()->routeIs('enrollments.index')" wire:navigate>{{ __('Enrolments') }}</flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('alumni.index')" :current="request()->routeIs('alumni.index')" wire:navigate>{{ __('Alumni') }}</flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('attendances.index')" :current="request()->routeIs('attendances.index')" wire:navigate>{{ __('Students Attendance') }}</flux:navlist.item>
                    <flux:navlist.item icon="user-group" :href="route('leaves.index')" :current="request()->routeIs('leaves.index')" wire:navigate>{{ __('Leaves') }}</flux:navlist.item>
                </flux:navlist.group>

                {{-- Single Item --}}
                <flux:navlist.item href="#" icon="list-bullet">Transactions</flux:navlist.item>

            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
