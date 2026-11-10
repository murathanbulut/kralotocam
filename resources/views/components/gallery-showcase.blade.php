{{--
    Galeri vitrini: yalnızca görsellerden oluşan, durmadan sağdan sola akan yatay şerit (marquee).
    - Görsellerin üzerinde yazı yoktur; açıklama için yalnızca ALT metni kullanılır (erişilebilirlik).
    - Görseller yalnızca yönetim panelindeki aktif galeri kayıtlarıdır (GalleryImage).
    - Karta tıklayınca fslightbox açılır; $group aynı lightbox grubunu belirler.
    - JS: resources/js/features/gallery-showcase.js şeridi kesintisiz döngü için çoğaltır
      (JS çalışmazsa kartlar elle kaydırılabilir yatay şerit olarak kalır).
--}}
@props(['images', 'group' => 'galeri', 'label' => 'Galeri görselleri'])
<div class="kral-showcase" data-gallery-showcase data-group="{{ $group }}" role="region" aria-label="{{ $label }}">
    <div class="kral-showcase__viewport">
        <div class="kral-showcase__track" data-showcase-track>
            @foreach ($images as $image)
                <figure class="kral-showcase__item m-0" data-showcase-item>
                    <a class="kral-showcase__card wptb-item--image" href="{{ media_url($image->image) }}" data-fslightbox="{{ $group }}" data-type="image" aria-label="{{ $image->title }} görselini büyüt">
                        <img src="{{ media_url($image->image) }}" alt="{{ $image->alt() }}" loading="lazy" width="1600" height="1000" draggable="false">
                        <span class="wptb-item--link" aria-hidden="true"><i class="bi bi-arrows-angle-expand"></i></span>
                    </a>
                </figure>
            @endforeach
        </div>
    </div>
</div>
