<x-layouts.public title="Atlas Notes">
    <div class="space-y-8">
        <section class="fb-panel p-5">
            <x-floorbenders.taxonomy-page-nav />

            <div class="mt-8">
                <p class="fb-heading-kicker">
                    Floorbenders Taxonomy
                </p>

                <h1 class="mt-3 text-4xl font-semibold tracking-tight text-stone-100 md:text-5xl">
                    Atlas Notes
                </h1>

                <p class="mt-5 max-w-4xl text-base leading-7 text-stone-400">
                    Notes are shorthand for aspect + realm. They are most useful as a phrase-building pattern because they let
                    you read a movement sequence quickly without repeating every full taxonomy label.
                </p>
            </div>
        </section>

        <section class="fb-panel p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">
                The note map
            </p>

            <h2 class="mt-3 text-2xl font-semibold text-stone-100">
                Sky acts like the white-key side. Earth acts like the black-key side.
            </h2>

            <p class="mt-4 max-w-4xl text-sm leading-7 text-stone-400">
                The system is not meant to be a perfect piano. It borrows the usefulness of musical shorthand so movement
                patterns can be seen, spoken, and remembered more easily.
            </p>

            <div class="mt-6 overflow-x-auto">
                <x-floorbenders.note-map />
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-3">
            <article class="fb-panel p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">
                    Example 1
                </p>

                <h2 class="mt-3 text-2xl font-semibold text-stone-100">
                    do → re → mi
                </h2>

                <p class="mt-4 text-sm leading-7 text-stone-400">
                    This reads as Sky Insect → Sky Reptile → Sky Mammal. The note pattern makes the phrase easier to remember,
                    while the full taxonomy explains what the body is doing.
                </p>
            </article>

            <article class="fb-panel p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">
                    Example 2
                </p>

                <h2 class="mt-3 text-2xl font-semibold text-stone-100">
                    do → mi → so
                </h2>

                <p class="mt-4 text-sm leading-7 text-stone-400">
                    This reads as Sky Insect → Sky Mammal → Sky Bird. It reveals a beautiful structure: all Sky, all Horizontal,
                    and all three layers.
                </p>
            </article>

            <article class="fb-panel p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">
                    Example 3
                </p>

                <h2 class="mt-3 text-2xl font-semibold text-stone-100">
                    di → ri → my
                </h2>

                <p class="mt-4 text-sm leading-7 text-stone-400">
                    This reads as Earth Insect → Earth Reptile → Earth Mammal. It uses the Earth side of the same first three
                    realm families.
                </p>
            </article>
        </section>

        <section class="fb-panel p-5">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-amber-300">
                Why notes matter
            </p>

            <h2 class="mt-3 text-2xl font-semibold text-stone-100">
                Notes make phrase patterns visible.
            </h2>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="font-semibold text-stone-100">
                        Faster to say
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        “do → mi → so” is easier to speak than “Sky Insect → Sky Mammal → Sky Bird.”
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="font-semibold text-stone-100">
                        Faster to see
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        A phrase builder can display note patterns so the structure is visible at a glance.
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="font-semibold text-stone-100">
                        Better for memory
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        Notes turn movement sequences into short phrases that can be repeated, remembered, and compared.
                    </p>
                </div>

                <div class="rounded-3xl border border-stone-800 bg-stone-950/70 p-5">
                    <p class="font-semibold text-stone-100">
                        Better for pattern discovery
                    </p>

                    <p class="mt-3 text-sm leading-6 text-stone-400">
                        Notes help reveal hidden relationships between aspect, realm, orientation, and layer.
                    </p>
                </div>
            </div>
        </section>
    </div>
</x-layouts.public>
