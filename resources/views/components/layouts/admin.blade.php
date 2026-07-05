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
    <header class="sticky top-0 z-40 border-b border-stone-800/80 bg-stone-950/90 backdrop-blur">
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
                        Admin Console
                    </div>
                </div>
            </a>

            <nav class="flex items-center gap-2 text-sm">
                <a
                    href="{{ route('library.index') }}"
                    wire:navigate
                    class="rounded-xl px-3 py-2 font-medium text-stone-300 transition hover:bg-stone-900 hover:text-stone-100"
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

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-xl px-3 py-2 font-medium text-stone-400 transition hover:bg-stone-900 hover:text-stone-100"
                    >
                        Log out
                    </button>
                </form>
            </nav>
        </div>

        <div class="border-t border-stone-800/70 bg-stone-950/70">
            <div class="mx-auto flex max-w-7xl gap-2 overflow-x-auto px-4 py-3 text-sm sm:px-6 lg:px-8">
                <a
                    href="{{ route('admin.dashboard') }}"
                    wire:navigate
                    @class([
                        'whitespace-nowrap rounded-xl px-3 py-2 font-medium transition',
                        'bg-amber-500 text-stone-950' => request()->routeIs('admin.dashboard'),
                        'text-stone-300 hover:bg-stone-900 hover:text-stone-100' => ! request()->routeIs('admin.dashboard'),
                    ])
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('admin.movements.index') }}"
                    wire:navigate
                    @class([
                        'whitespace-nowrap rounded-xl px-3 py-2 font-medium transition',
                        'bg-amber-500 text-stone-950' => request()->routeIs('admin.movements.*'),
                        'text-stone-300 hover:bg-stone-900 hover:text-stone-100' => ! request()->routeIs('admin.movements.*'),
                    ])
                >
                    Movements
                </a>

                <a
                    href="{{ route('admin.sequences.index') }}"
                    wire:navigate
                    @class([
                        'whitespace-nowrap rounded-xl px-3 py-2 font-medium transition',
                        'bg-amber-500 text-stone-950' => request()->routeIs('admin.sequences.*'),
                        'text-stone-300 hover:bg-stone-900 hover:text-stone-100' => ! request()->routeIs('admin.sequences.*'),
                    ])
                >
                    Phrase Builder
                </a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>
</div>

@livewireScripts
</body>
</html>
