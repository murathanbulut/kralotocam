{{--
    Galeri vitrini: büyük yatay görsel kartları içeren Swiper carousel.
    - Görseller yalnızca yönetim panelindeki aktif galeri kayıtlarıdır (GalleryImage).
    - Karta tıklayınca fslightbox açılır; $group aynı lightbox grubunu belirler.
    - JS: resources/js/features/gallery-showcase.js (JS çalışmazsa kartlar yatay kaydırılabilir şerit olarak kalır).
    Kart dili: referans temanın portföy kartı (wptb-item--image / wptb-item--holder / wptb-item--meta / wptb-item--link).
--}}
@props(['images', 'group' => 'galeri', 'showAlbum' => false, 'label' => 'Galeri görselleri'])
<div class="kral-showcase" data-gallery-showcase>
    <div class="kral-showcase__bar">
        <p class="kral-showcase__count">
            <span>{{ str_pad((string) $images->count(), 2, '0', STR_PAD_LEFT) }}</span> uygulama
        </p>
        <div class="kral-showcase__nav">
            <button type="button" class="kral-showcase__arrow" data-showcase-prev aria-label="Önceki görseller"><i class="bi bi-chevron-left" aria-hidden="true"></i></button>
            <button type="button" class="kral-showcase__arrow" data-showcase-next aria-label="Sonraki görseller"><i class="bi bi-chevron-right" aria-hidden="true"></i></button>
        </div>
    </div>

    <div class="swiper kral-showcase__swiper" aria-label="{{ $label }}">
        <div class="swiper-wrapper">
            @foreach ($images as $image)
                <div class="swiper-slide kral-showcase__slide">
                    <figure class="kral-showcase__card m-0">
                        <a class="wptb-item--image" href="{{ media_url($image->image) }}" data-fslightbox="{{ $group }}" data-type="image" aria-label="{{ $image->title }} görselini büyüt">
                            <img src="{{ media_url($image->image) }}" alt="{{ $image->alt() }}" loading="lazy" width="1600" height="1000" draggable="false">
                            <span class="wptb-item--link" aria-hidden="true"><i class="bi bi-arrows-angle-expand"></i></span>
                        </a>
                        <figcaption class="wptb-item--holder">
                            <div class="wptb-item--meta">
                                @if ($showAlbum && $image->album)
                                    <span class="kral-showcase__album">{{ $image->album->title }}</span>
                                @endif
                                <h3 class="kral-showcase__title">{{ $image->title }}</h3>
                                @if ($image->description)
                                    <p class="kral-showcase__desc">{{ $image->description }}</p>
                                @endif
                            </div>
                        </figcaption>
                    </figure>
                </div>
            @endforeach
        </div>
    </div>

    <div class="kral-showcase__progress" data-showcase-progress></div>
</div>
