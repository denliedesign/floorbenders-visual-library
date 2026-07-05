@props([
    'src' => null,
    'alt' => '',
    'fallback' => null,
])

@php
    $publicPath = $src ? public_path(ltrim($src, '/')) : null;
    $imageExists = $publicPath && file_exists($publicPath);
@endphp

@if ($imageExists)
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        {{ $attributes->class('h-5 w-5 object-contain') }}
    >
@elseif ($fallback)
    <span {{ $attributes->class('flex h-5 w-5 items-center justify-center rounded-full border border-current/30 text-[0.6rem] font-bold uppercase') }}>
        {{ $fallback }}
    </span>
@endif
