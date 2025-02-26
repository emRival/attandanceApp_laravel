@php
    $brandName = 'Mafatih Smart Absen';
    $brandLogo =
        'https://mafatihislamicschool.sch.id/wp-content/uploads/2022/04/cropped-cropped-Cap-Logo-Mafatih-2.png';
    $brandLogoHeight = filament()->getBrandLogoHeight() ?? '1.5rem';
    $darkModeBrandLogo = filament()->getDarkModeBrandLogo();
    $hasDarkModeBrandLogo = filled($darkModeBrandLogo);

    $getLogoClasses = fn(bool $isDarkMode): string => \Illuminate\Support\Arr::toCssClasses([
        'fi-logo',
        'flex' => !$hasDarkModeBrandLogo,
        'flex dark:hidden' => $hasDarkModeBrandLogo && !$isDarkMode,
        'hidden dark:flex' => $hasDarkModeBrandLogo && $isDarkMode,
    ]);

    $logoStyles = "height: {$brandLogoHeight}";
@endphp

@capture($content, $logo, $isDarkMode = false)
    @if ($logo instanceof \Illuminate\Contracts\Support\Htmlable)
        <div
            {{ $attributes->class([$getLogoClasses($isDarkMode)])->style([$logoStyles]) }}>
            {{ $logo }}
        </div>
    @elseif (filled($logo))
        <div class="flex items-center">
            <img alt="{{ __('filament-panels::layout.logo.alt', ['name' => $brandName]) }}" src="{{ $logo }}"
            {{ $attributes->class([$getLogoClasses($isDarkMode)])->style([$logoStyles]) }} />
            <div
            {{ $attributes->class([
                $getLogoClasses($isDarkMode),
                'ml-2 text-xl font-bold leading-5 tracking-tight text-gray-950 dark:text-white',
            ]) }}>
            {{ $brandName }}
            </div>
        </div>
    @else
        <div
            {{ $attributes->class([
                $getLogoClasses($isDarkMode),
                'text-xl font-bold leading-5 tracking-tight text-gray-950 dark:text-white',
            ]) }}>
            {{ $brandName }}
        </div>
    @endif
@endcapture

{{ $content($brandLogo) }}

@if ($hasDarkModeBrandLogo)
    {{ $content($darkModeBrandLogo, isDarkMode: true) }}
@endif
