{{-- Referans temadaki .wptb-project grid öğesi; tıklandığında fslightbox açılır --}}
@props(['image', 'group' => 'galeri', 'caption' => null])
<figure class="kral-gallery-item wptb-item--inner m-0">
    <div class="wptb-item--image">
        <a href="{{ media_url($image->image) }}" data-fslightbox="{{ $group }}" data-type="image" aria-label="{{ $image->title }} görselini büyüt">
            <img src="{{ media_url($image->image) }}" alt="{{ $image->alt() }}" loading="lazy" width="1600" height="900">
            <span class="wptb-item--link" aria-hidden="true"><i class="bi bi-arrows-angle-expand"></i></span>
        </a>
    </div>
    <figcaption class="wptb-item--holder">
        <div class="wptb-item--meta">
            <h3 class="h4">{{ $image->title }}</h3>
            <p>{{ $caption ?? $image->description }}</p>
        </div>
    </figcaption>
</figure>
