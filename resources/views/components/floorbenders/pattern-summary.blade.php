@props([
    'layerPattern' => null,
    'orientationPattern' => null,
    'aspectPattern' => null,
    'realmPattern' => null,
    'notePattern' => null,
])

<x-ui.panel soft padding="p-4">
    <div class="grid gap-4 text-sm md:grid-cols-2">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-400">
                Layer Pattern
            </p>

            <p class="mt-1 text-stone-200">
                {{ $layerPattern ?: 'No layer pattern yet' }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-300">
                Orientation Pattern
            </p>

            <p class="mt-1 text-stone-200">
                {{ $orientationPattern ?: 'No orientation pattern yet' }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-300">
                Aspect Pattern
            </p>

            <p class="mt-1 text-stone-200">
                {{ $aspectPattern ?: 'No aspect pattern yet' }}
            </p>
        </div>

        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-green-300">
                Realm Pattern
            </p>

            <p class="mt-1 leading-6 text-stone-200">
                {{ $realmPattern ?: 'No realm pattern yet' }}
            </p>
        </div>

        <div class="md:col-span-2 rounded-2xl border border-amber-500/20 bg-amber-500/10 p-4">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">
                Note Pattern
            </p>

            <p class="mt-2 text-lg font-semibold leading-7 text-amber-100">
                {{ $notePattern ?: 'No note pattern yet' }}
            </p>

            <p class="mt-2 text-xs leading-5 text-amber-100/70">
                Aspect + realm shorthand: Sky follows the white-key syllables, Earth uses the altered syllables.
            </p>
        </div>
    </div>
</x-ui.panel>
