@props(['title' => null])

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ $title ? $title . ' - ' : '' }}{{ config('floorbenders.name') }}
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="fb-atlas-bg antialiased">
<div class="min-h-screen">
    <header class="sticky top-0 z-40 border-b border-stone-800/80 bg-stone-950/85 backdrop-blur" x-data="{ mobileMenuOpen: false }" @click.outside="mobileMenuOpen = false">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" wire:navigate class="group flex items-center gap-3">
                <div>
                    <img src="/images/brand/fb-icon.png" class="h-13 w-13" alt="">
                </div>
                <div>
                    <div class="text-sm font-semibold tracking-wide text-stone-100">
                        {{ config('floorbenders.name') }}
                    </div>
                    <div class="text-xs text-stone-500">
                        Movement Atlas
                    </div>
                </div>
            </a>

            <nav class="hidden items-center gap-2 text-sm lg:flex">
                <a
                    href="{{ route('library.index') }}"
                    wire:navigate
                    @class([
                        'rounded-xl px-3 py-2 font-medium transition',
                        'bg-amber-500/10 text-amber-200' => request()->routeIs('library.*'),
                        'text-stone-300 hover:bg-stone-900 hover:text-stone-100' => ! request()->routeIs('library.*'),
                    ])
                >
                    Movement Atlas
                </a>

                <a
                    href="{{ route('sequences.index') }}"
                    wire:navigate
                    class="rounded-xl px-3 py-2 font-medium text-stone-300 transition hover:bg-stone-900 hover:text-stone-100"
                >
                    Phrases
                </a>

                <a
                    href="{{ route('taxonomy.gates') }}"
                    wire:navigate
                    class="rounded-xl px-3 py-2 font-medium text-stone-300 transition hover:bg-stone-900 hover:text-stone-100"
                >
                    Taxonomy
                </a>

                @auth
                    @if (auth()->user()->isAdmin())
                        <a
                            href="{{ route('admin.dashboard') }}"
                            wire:navigate
                            @class([
                                'rounded-xl px-3 py-2 font-medium transition',
                                'bg-amber-500/10 text-amber-200' => request()->routeIs('admin.*'),
                                'text-stone-300 hover:bg-stone-900 hover:text-stone-100' => ! request()->routeIs('admin.*'),
                            ])
                        >
                            Admin
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            class="rounded-xl px-3 py-2 font-medium text-stone-400 transition hover:bg-stone-900 hover:text-stone-100"
                        >
                            Log out
                        </button>
                    </form>
                @else
                    <a
                        href="{{ route('login') }}"
                        wire:navigate
                        class="rounded-xl px-3 py-2 font-medium text-stone-300 transition hover:bg-stone-900 hover:text-stone-100"
                    >
                        Log in
                    </a>
                @endauth
            </nav>

            <button
                type="button"
                @click="mobileMenuOpen = ! mobileMenuOpen"
                class="fb-nav-toggle lg:hidden"
                aria-label="Toggle navigation menu"
                :aria-expanded="mobileMenuOpen"
            >
                <flux:icon.bars-2 x-show="! mobileMenuOpen" class="size-6" />
                <flux:icon.x-mark x-show="mobileMenuOpen" x-cloak class="size-6" />
            </button>
        </div>

        <nav
            x-show="mobileMenuOpen"
            x-cloak
            x-transition
            class="border-t border-stone-800/80 px-4 py-3 text-sm lg:hidden"
        >
            <div class="flex flex-col gap-1">
                <a
                    href="{{ route('library.index') }}"
                    wire:navigate
                    @click="mobileMenuOpen = false"
                    @class([
                        'rounded-xl px-3 py-2 font-medium transition',
                        'bg-amber-500/10 text-amber-200' => request()->routeIs('library.*'),
                        'text-stone-300 hover:bg-stone-900 hover:text-stone-100' => ! request()->routeIs('library.*'),
                    ])
                >
                    Movement Atlas
                </a>

                <a
                    href="{{ route('sequences.index') }}"
                    wire:navigate
                    @click="mobileMenuOpen = false"
                    class="rounded-xl px-3 py-2 font-medium text-stone-300 transition hover:bg-stone-900 hover:text-stone-100"
                >
                    Phrases
                </a>

                <a
                    href="{{ route('taxonomy.gates') }}"
                    wire:navigate
                    @click="mobileMenuOpen = false"
                    class="rounded-xl px-3 py-2 font-medium text-stone-300 transition hover:bg-stone-900 hover:text-stone-100"
                >
                    Taxonomy
                </a>

                @auth
                    @if (auth()->user()->isAdmin())
                        <a
                            href="{{ route('admin.dashboard') }}"
                            wire:navigate
                            @click="mobileMenuOpen = false"
                            @class([
                                'rounded-xl px-3 py-2 font-medium transition',
                                'bg-amber-500/10 text-amber-200' => request()->routeIs('admin.*'),
                                'text-stone-300 hover:bg-stone-900 hover:text-stone-100' => ! request()->routeIs('admin.*'),
                            ])
                        >
                            Admin
                        </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button
                            type="submit"
                            @click="mobileMenuOpen = false"
                            class="w-full rounded-xl px-3 py-2 text-left font-medium text-stone-400 transition hover:bg-stone-900 hover:text-stone-100"
                        >
                            Log out
                        </button>
                    </form>
                @else
                    <a
                        href="{{ route('login') }}"
                        wire:navigate
                        @click="mobileMenuOpen = false"
                        class="rounded-xl px-3 py-2 font-medium text-stone-300 transition hover:bg-stone-900 hover:text-stone-100"
                    >
                        Log in
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>
</div>

@livewireScripts
</body>
</html>
