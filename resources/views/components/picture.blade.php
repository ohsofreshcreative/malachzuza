@props([
    'image',
    'class' => '',
    'figureClass' => '',
])

@php
    $id = is_array($image)
        ? ($image['ID'] ?? $image['id'] ?? null)
        : (is_numeric($image) ? (int) $image : null);

    $alt         = is_array($image) ? ($image['alt'] ?? '') : '';
    $fallbackUrl = is_array($image) ? ($image['url'] ?? '') : '';

    // img-sm  640px  — < md  (default / mobile)
    // img-md  900px  — >= md (768px+)
    // img-lg  1200px — >= lg (1024px+)
    // img-xl  1440px — >= xl (1280px+)
    $sm = $id ? wp_get_attachment_image_src($id, 'img-sm') : null;
    $md = $id ? wp_get_attachment_image_src($id, 'img-md') : null;
    $lg = $id ? wp_get_attachment_image_src($id, 'img-lg') : null;
    $xl = $id ? wp_get_attachment_image_src($id, 'img-xl') : null;

    $imgSrc = $sm[0] ?? $fallbackUrl;
@endphp

@if ($id || $fallbackUrl)
    <figure @class(['c-picture', $figureClass => filled($figureClass)])>
        <picture>
            @if ($xl)
                <source media="(min-width: 1280px)" srcset="{{ $xl[0] }}">
            @endif
            @if ($lg)
                <source media="(min-width: 1024px)" srcset="{{ $lg[0] }}">
            @endif
            @if ($md)
                <source media="(min-width: 768px)" srcset="{{ $md[0] }}">
            @endif
            <img
                src="{{ $imgSrc }}"
                alt="{{ $alt }}"
                @class([$class => filled($class)])
                {{ $attributes }}
            >
        </picture>
        {{ $slot }}
    </figure>
@endif
