<nav {{ $attributes->class('rounded-3xl border border-stone-800 bg-stone-950/70 p-4') }}>
    <p class="mb-3 text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">
        Taxonomy Pages
    </p>

    <div class="flex flex-wrap gap-2">
        <a
            href="{{ route('taxonomy.gates') }}"
            class="{{ request()->routeIs('taxonomy.gates') ? 'fb-button-primary text-xs' : 'fb-button-secondary text-xs' }}"
        >
            Gates
        </a>

        <a
            href="{{ route('taxonomy.aspects') }}"
            class="{{ request()->routeIs('taxonomy.aspects') ? 'fb-button-primary text-xs' : 'fb-button-secondary text-xs' }}"
        >
            Aspects
        </a>

        <a
            href="{{ route('taxonomy.realms') }}"
            class="{{ request()->routeIs('taxonomy.realms') ? 'fb-button-primary text-xs' : 'fb-button-secondary text-xs' }}"
        >
            Realms
        </a>

        <a
            href="{{ route('taxonomy.orientations') }}"
            class="{{ request()->routeIs('taxonomy.orientations') ? 'fb-button-primary text-xs' : 'fb-button-secondary text-xs' }}"
        >
            Orientation
        </a>

        <a
            href="{{ route('taxonomy.layers') }}"
            class="{{ request()->routeIs('taxonomy.layers') ? 'fb-button-primary text-xs' : 'fb-button-secondary text-xs' }}"
        >
            Layers
        </a>

        <a
            href="{{ route('taxonomy.notes') }}"
            class="{{ request()->routeIs('taxonomy.notes') ? 'fb-button-primary text-xs' : 'fb-button-secondary text-xs' }}"
        >
            Notes
        </a>
    </div>
</nav>
