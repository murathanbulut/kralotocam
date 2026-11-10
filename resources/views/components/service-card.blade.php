{{-- Referans temadaki .wptb-icon-box6 hizmet kartı. İkon: marka arması (favicon), tüm kartlarda aynı. --}}
@props(['service', 'headingTag' => 'h3'])
<div {{ $attributes->merge(['class' => 'wptb-icon-box6 kral-service-card']) }}>
    <div class="wptb-item--inner">
        <div class="wptb-item--icon">
            <img src="{{ asset('images/theme/favicon.png') }}" alt="" width="56" height="56" loading="lazy">
        </div>
        <div class="wptb-item--holder">
            <{{ $headingTag }} class="wptb-item--title h4"><a href="{{ $service->url() }}">{{ $service->label() }}</a></{{ $headingTag }}>
            <p class="wptb-item--description">{{ $service->excerpt }}</p>
            <a class="kral-more-link" href="{{ $service->url() }}" aria-label="{{ $service->title }} hakkında detaylı bilgi">Detaylı Bilgi <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
        </div>
    </div>
</div>
