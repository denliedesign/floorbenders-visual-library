@php
    $realms = \App\Enums\Realm::cases();

    $noteFor = function (string $aspect, string $realm): string {
        return match ($aspect . ':' . $realm) {
            'sky:insect' => 'do',
            'earth:insect' => 'di',

            'sky:reptile' => 're',
            'earth:reptile' => 'ri',

            'sky:mammal' => 'mi',
            'earth:mammal' => 'my',

            'sky:amphibian' => 'fa',
            'earth:amphibian' => 'fi',

            'sky:bird' => 'so',
            'earth:bird' => 'si',

            'sky:fish' => 'la',
            'earth:fish' => 'li',

            default => '—',
        };
    };
@endphp

<div class="note-map-scroll">
    <div {{ $attributes->class('note-map overflow-hidden rounded-3xl border border-amber-500/20 bg-stone-950/80') }}>
        <div class="grid grid-cols-[96px_repeat(6,minmax(88px,1fr))] border-b border-amber-500/20 text-xs font-semibold uppercase tracking-[0.14em] text-amber-100/60">
            <div class="border-r border-amber-500/20 p-3">
                Aspect
            </div>

            @foreach ($realms as $realm)
                <div class="border-r border-amber-500/10 p-3 last:border-r-0">
                    {{ $realm->label() }}
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-[96px_repeat(6,minmax(88px,1fr))] border-b border-amber-500/10 text-sm">
            <div class="border-r border-amber-500/20 p-3 font-semibold text-blue-200">
                Sky
            </div>

            @foreach ($realms as $realm)
                <div class="border-r border-amber-500/10 p-3 last:border-r-0">
                <span class="inline-flex rounded-full border border-blue-400/30 bg-blue-400/10 px-3 py-1 font-semibold text-blue-100">
                    {{ $noteFor('sky', $realm->value) }}
                </span>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-[96px_repeat(6,minmax(88px,1fr))] text-sm">
            <div class="border-r border-amber-500/20 p-3 font-semibold text-amber-200">
                Earth
            </div>

            @foreach ($realms as $realm)
                <div class="border-r border-amber-500/10 p-3 last:border-r-0">
                <span class="inline-flex rounded-full border border-amber-400/30 bg-amber-400/10 px-3 py-1 font-semibold text-amber-100">
                    {{ $noteFor('earth', $realm->value) }}
                </span>
                </div>
            @endforeach
        </div>
    </div>
</div>
