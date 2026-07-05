<section {{ $attributes->class('fb-panel') }}>
    <div class="flex flex-col p-5 gap-5 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <p class="fb-heading-kicker">
                Taxonomy Key
            </p>

            <h2 class="mt-3 text-2xl font-semibold tracking-tight text-stone-100">
                A quick guide to the Floorbenders language.
            </h2>

            <p class="mt-3 max-w-3xl text-sm leading-6 text-stone-400">
                These terms describe how each movement is organized, connected, and read inside the atlas.
            </p>
        </div>
    </div>

    <div class="p-5 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <a href="{{ route('taxonomy.gates') }}" class="group rounded-2xl border border-stone-800 bg-stone-950/60 p-5 transition hover:border-amber-500/40 hover:bg-stone-900/70">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-400">
                Gate
            </p>

            <p class="mt-2 text-sm leading-6 text-stone-300">
                The transitional pathway the mover takes between realms.
            </p>

            <p class="mt-3 text-xs font-semibold text-amber-300 opacity-80 transition group-hover:opacity-100">
                Learn gates →
            </p>
        </a>

        <a href="{{ route('taxonomy.aspects') }}" class="group rounded-2xl border border-stone-800 bg-stone-950/60 p-5 transition hover:border-blue-500/40 hover:bg-stone-900/70">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-300">
                Aspects
            </p>

            <p class="mt-2 text-sm leading-6 text-stone-300">
                Whether the head and face are oriented toward the sky above or the earth below.
            </p>

            <p class="mt-3 text-xs font-semibold text-blue-300 opacity-80 transition group-hover:opacity-100">
                Learn aspects →
            </p>
        </a>

        <a href="{{ route('taxonomy.realms') }}" class="group rounded-2xl border border-stone-800 bg-stone-950/60 p-5 transition hover:border-green-500/40 hover:bg-stone-900/70">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-green-300">
                Realms
            </p>

            <p class="mt-2 text-sm leading-6 text-stone-300">
                The six core positions that organize the movement atlas.
            </p>

            <p class="mt-3 text-xs font-semibold text-green-300 opacity-80 transition group-hover:opacity-100">
                Learn realms →
            </p>
        </a>

        <a href="{{ route('taxonomy.orientations') }}" class="group rounded-2xl border border-stone-800 bg-stone-950/60 p-5 transition hover:border-teal-500/40 hover:bg-stone-900/70">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-teal-300">
                Orientation
            </p>

            <p class="mt-2 text-sm leading-6 text-stone-300">
                Whether the body is horizontal to the ground or perpendicular to the ground.
            </p>

            <p class="mt-3 text-xs font-semibold text-teal-300 opacity-80 transition group-hover:opacity-100">
                Learn orientation →
            </p>
        </a>

        <a href="{{ route('taxonomy.layers') }}" class="group rounded-2xl border border-stone-800 bg-stone-950/60 p-5 transition hover:border-orange-500/40 hover:bg-stone-900/70">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-orange-300">
                Layers
            </p>

            <p class="mt-2 text-sm leading-6 text-stone-300">
                The six positions grouped by body grounded, lifted, or supported unconventionally.
            </p>

            <p class="mt-3 text-xs font-semibold text-orange-300 opacity-80 transition group-hover:opacity-100">
                Learn layers →
            </p>
        </a>

        <a href="{{ route('taxonomy.notes') }}" class="group rounded-2xl border border-stone-800 bg-stone-950/60 p-5 transition hover:border-amber-500/40 hover:bg-stone-900/70">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">
                Notes
            </p>

            <p class="mt-2 text-sm leading-6 text-stone-300">
                Shorthand for aspect + realm, mainly useful as a pattern in phrase building.
            </p>

            <p class="mt-3 text-xs font-semibold text-amber-300 opacity-80 transition group-hover:opacity-100">
                Learn notes →
            </p>
        </a>
    </div>
</section>
