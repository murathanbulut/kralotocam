@props(['subtitle' => null, 'title', 'center' => false, 'tag' => 'h2', 'deco' => false])
<div {{ $attributes->merge(['class' => 'wptb-heading']) }}>
    <div @class(['wptb-item--inner', 'text-center' => $center])>
        @if ($deco && $subtitle)
            <x-section-deco :text="$subtitle" :center="$center" />
        @endif
        @if ($subtitle)
            <p class="wptb-item--subtitle h6"><span></span>{{ $subtitle }}</p>
        @endif
        <{{ $tag }} class="wptb-item--title">{{ $title }}</{{ $tag }}>
        @if (trim($slot))
            <div class="wptb-item--description kral-heading-desc">{{ $slot }}</div>
        @endif
    </div>
</div>
