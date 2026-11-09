<x-layouts.app :seo="$seo">
    <x-page-heading :title="$service->title" :seo="$seo" :image="$service->cover_image" />

    <section class="kral-section">
        <div class="container">
            <div class="row g-4 g-lg-5">
                <div class="col-lg-8">
                    <article class="kral-content">
                        @if ($service->cover_image)
                            <figure class="kral-content__figure">
                                <img src="{{ media_url($service->cover_image) }}" alt="{{ $service->title }} - Kral Oto Cam" width="1600" height="900">
                            </figure>
                        @endif

                        @if ($service->excerpt)
                            <p class="kral-lead">{{ $service->excerpt }}</p>
                        @endif

                        {!! $service->content !!}

                        @if (! empty($service->gallery_images))
                            <h2>Uygulamalardan Görseller</h2>
                            <div class="row g-3 kral-service-gallery">
                                @foreach ($service->gallery_images as $path)
                                    <div class="col-sm-6">
                                        <a href="{{ media_url($path) }}" data-fslightbox="hizmet-{{ $service->id }}" data-type="image" class="kral-thumb" aria-label="Görseli büyüt">
                                            <img src="{{ media_url($path) }}" alt="{{ $service->title }} uygulaması" loading="lazy" width="800" height="450">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="kral-content__actions">
                            <x-theme-button :href="site_url('iletisim').'?hizmet='.$service->slug.'#iletisim-formu'">Bilgi ve Randevu Talebi</x-theme-button>
                            <a class="kral-wa-link" href="{{ whatsapp_url('Merhaba, '.$service->title.' hizmeti hakkında bilgi almak istiyorum.') }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp" aria-hidden="true"></i> WhatsApp ile sorun</a>
                        </div>
                    </article>
                </div>

                <aside class="col-lg-4">
                    <div class="sidebar kral-sidebar">
                        <div class="widget">
                            <h2 class="widget-title">Hizmetlerimiz</h2>
                            <ul class="kral-side-nav">
                                @foreach ($services as $item)
                                    <li @class(['active' => $item->is($service)])>
                                        <a href="{{ $item->url() }}" @if ($item->is($service)) aria-current="page" @endif>{{ $item->label() }} <i class="bi bi-chevron-right" aria-hidden="true"></i></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @include('partials.sidebar-contact')
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="kral-section kral-related pt-0">
            <div class="container">
                <x-section-heading subtitle="Diğer Hizmetler" title="İlgili Hizmetler" />
                <div class="row g-4">
                    @foreach ($related as $item)
                        <div class="col-md-4 d-flex"><x-service-card :service="$item" class="w-100" /></div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-contact-cta />
</x-layouts.app>
