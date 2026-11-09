{{--
    D. Görselli hizmet kartı (KRAL_FX_SERVICE_IMAGES)
    Referans temadaki "Portföyümüz" kartının yapısı (grid-item / wptb-item--image / wptb-item--holder /
    wptb-item--meta / wptb-item--link) ve "effect-gradient" hover'ı esas alındı. Farkı: başlık her zaman görünür
    (dokunmatik ekranlarda da okunabilsin diye); açıklama hover/odakta kırmızı gradyanla açılır.
    Görsel: hizmetin panelden yönetilen "Kapak görseli". Görseli olmayan hizmette başka hizmetin fotoğrafı
    kullanılmaz; hizmet ikonu ile marka renklerinde bir yer tutucu gösterilir.
--}}
@props(['service', 'headingTag' => 'h3'])
<article {{ $attributes->merge(['class' => 'grid-item kral-svc-card'.($service->cover_image ? '' : ' kral-svc-card--no-image')]) }}>
    <div class="wptb-item--inner">
        <div class="wptb-item--image">
            @if ($service->cover_image)
                <img src="{{ media_url($service->cover_image) }}" alt="{{ $service->title }} - Kral Oto Cam" loading="lazy" width="800" height="800">
            @else
                <div class="kral-svc-card__placeholder" aria-hidden="true">
                    <x-service-icon :service="$service" />
                </div>
            @endif
            <span class="wptb-item--link" aria-hidden="true"><i class="bi bi-arrow-up-right"></i></span>
        </div>
        <div class="wptb-item--holder">
            <div class="wptb-item--meta">
                <{{ $headingTag }} class="kral-svc-card__title">
                    <a href="{{ $service->url() }}" class="kral-svc-card__link">{{ $service->label() }}</a>
                </{{ $headingTag }}>
                <div class="kral-svc-card__more">
                    @if ($service->excerpt)
                        <p>{{ $service->excerpt }}</p>
                    @endif
                    <span class="kral-svc-card__cta" aria-hidden="true">Detaylı Bilgi <i class="bi bi-arrow-right"></i></span>
                </div>
            </div>
        </div>
    </div>
</article>
