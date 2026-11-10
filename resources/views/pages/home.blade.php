<x-layouts.app :seo="$seo" body-class="is-home">

    {{-- 3. Hero / slider (referans: .wptb-slider.style2) --}}
    @if ($sliders->isNotEmpty())
        <section class="wptb-slider style2 kral-hero" aria-label="Öne çıkanlar">
            <div class="swiper-container wptb-swiper-slider-two swiper">
                <div class="swiper-wrapper">
                    @foreach ($sliders as $slide)
                        <div class="swiper-slide">
                            <div class="wptb-slider--item">
                                <div class="wptb-slider--image" style="background-image: url('{{ media_url($slide->image, 'images/seo/page-heading.jpg') }}');" role="img" aria-label="{{ $slide->image_alt ?: $slide->title }}"></div>
                                <div class="wptb-slider--inner">
                                    <div class="wptb-item-layer wptb-item-layer-one">
                                        <img src="{{ asset('images/theme/layer-3.png') }}" alt="" aria-hidden="true">
                                    </div>
                                    <div class="wptb-heading">
                                        <div class="wptb-item--inner">
                                            @if ($slide->subtitle)
                                                <p class="wptb-item--subtitle">{{ $slide->subtitle }}</p>
                                            @endif
                                            @if ($loop->first)
                                                <h1 class="wptb-item--title">{{ $slide->title }}</h1>
                                            @else
                                                <h2 class="wptb-item--title">{{ $slide->title }}</h2>
                                            @endif
                                            @if ($slide->description)
                                                <p class="wptb-item--description">{{ $slide->description }}</p>
                                            @endif
                                            @if ($slide->primary_button_text || $slide->secondary_button_text)
                                                <div class="wptb-item--button kral-hero__buttons">
                                                    @if ($slide->primary_button_text)
                                                        <x-theme-button :href="$slide->primaryUrl()">{{ $slide->primary_button_text }}</x-theme-button>
                                                    @endif
                                                    @if ($slide->secondary_button_text)
                                                        <x-theme-button :href="$slide->secondaryUrl()" variant="btn-two white" icon="bi-arrow-down-right">{{ $slide->secondary_button_text }}</x-theme-button>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="wptb-left-pane justify-content-center d-none d-lg-flex">
                <div class="logo"><p class="h6 m-0">Eskişehir · Teksan Sanayi Sitesi</p></div>
            </div>
            <div class="wptb-right-pane d-none d-lg-flex">
                <div class="kral-hero__side">
                    <a href="{{ whatsapp_url() }}" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="bi bi-whatsapp" aria-hidden="true"></i></a>
                    <a href="{{ phone_href(setting('phone_gsm')) }}" aria-label="Telefon"><i class="bi bi-telephone" aria-hidden="true"></i></a>
                </div>
            </div>

            @if ($sliders->count() > 1)
                <div class="wptb-bottom-pane justify-content-center">
                    <div class="wptb-swiper-dots style2"><div class="swiper-pagination"></div></div>
                    <div class="wptb-swiper-navigation style3">
                        <button type="button" class="wptb-swiper-arrow swiper-button-prev" aria-label="Önceki slayt"></button>
                        <button type="button" class="wptb-swiper-arrow swiper-button-next" aria-label="Sonraki slayt"></button>
                    </div>
                </div>
            @endif
        </section>
    @endif

    {{-- 4. Güven ve hizmet avantajları --}}
    <section class="kral-features" aria-labelledby="avantajlar-baslik">
        <div class="container">
            <h2 id="avantajlar-baslik" class="visually-hidden">Neden Kral Oto Cam?</h2>
            <div class="row g-4">
                @foreach ([
                    ['bi-search', 'Önce Doğru Tespit', 'Her çatlak cam değişimi gerektirmez. Hasarın boyutunu ve konumunu inceleyerek onarım ya da değişim kararını birlikte veriyoruz.'],
                    ['bi-car-front', 'Araca Uygun Cam', 'Cam seçimini aracınızın marka, model ve donanım özelliklerine göre yapıyoruz.'],
                    ['bi-camera-video', 'ADAS Kalibrasyon Desteği', 'Ön cam değişiminden sonra ön kamera ve sürüş destek sistemleri için kalibrasyon kontrolü yapıyoruz.'],
                    ['bi-whatsapp', 'Kolay İletişim', 'Hasarın fotoğrafını WhatsApp üzerinden göndererek ön bilgi alabilir, servis zamanını planlayabilirsiniz.'],
                ] as [$icon, $title, $text])
                    <div class="col-sm-6 col-xl-3">
                        <div class="kral-feature wow fadeInUp" style="--delay: {{ $loop->index * 100 }}ms">
                            <span class="kral-feature__icon"><img src="{{ media_url(setting('favicon'), 'images/theme/favicon.png') }}" alt="" width="56" height="56" loading="lazy"></span>
                            <h3 class="kral-feature__title">{{ $title }}</h3>
                            <p class="kral-feature__text">{{ $text }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 5. Kurumsal tanıtım (referans: .wptb-about-two) --}}
    <section class="wptb-about-two kral-about">
        <div class="container">
            <x-section-heading subtitle="Kurumsal" :title="setting('home_about_title')" deco />
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="wptb-image-single wow fadeInUp">
                        <div class="wptb-item--inner">
                            <div class="wptb-item--image position-relative">
                                <img src="{{ media_url(setting('home_about_image') ?: $about?->cover_image, 'images/seo/page-heading.jpg') }}" alt="Kral Oto Cam atölyesinde ön cam montajı" loading="lazy" width="1600" height="900">
                                <div class="wptb-item--button round-button">
                                    <x-theme-button :href="site_url('hakkimizda')">Devamı</x-theme-button>
                                </div>
                            </div>
                        </div>
                        <div class="wptb-item-layer wptb-item-layer-one both-version">
                            <img src="{{ asset('images/theme/light-2-light.png') }}" alt="" aria-hidden="true">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 ps-md-5 mt-5 mt-md-0">
                    <div class="wptb-about--text ps-md-4">
                        <h3>Eskişehir'de Oto Cam Servisi</h3>
                        <p>{{ setting('home_about_text') ?: $about?->excerpt }}</p>
                        <ul class="kral-check-list">
                            <li>Binek, ticari araç, traktör ve iş makinesi camları</li>
                            <li>Taş izi ve çatlaklar için onarım değerlendirmesi</li>
                            <li>ADAS ve ön kamera kalibrasyonu</li>
                            <li>Cam filmi, dikiz ve yan ayna uygulamaları</li>
                        </ul>
                        <x-theme-button :href="site_url('hakkimizda')" class="mt-4">Hakkımızda</x-theme-button>
                    </div>
                </div>
            </div>
        </div>
        <div class="wptb-item-layer wptb-item-layer-two" aria-hidden="true"><img src="{{ asset('images/theme/texture-5.png') }}" alt=""></div>
        <div class="wptb-item-layer wptb-item-layer-three" aria-hidden="true"><img src="{{ asset('images/theme/texture-4.png') }}" alt=""></div>
    </section>

    {{-- 6. Hizmetler --}}
    <section class="kral-services" id="hizmetler">
        <div class="container">
            <x-section-heading subtitle="Hizmetlerimiz" title="Oto Cam Hizmetlerimiz" center deco>
                Araç camı onarımından ADAS kalibrasyonuna kadar ihtiyacınız olan hizmetleri tek noktada sunuyoruz.
            </x-section-heading>
            {{-- KRAL_FX_SERVICE_IMAGES: görselli kartlar; kapalıysa mevcut ikonlu kartlar --}}
            <div @class(['row g-4 justify-content-center', 'kral-svc-grid' => feature('service_image_cards')])>
                @foreach ($services as $service)
                    <div class="col-md-6 col-lg-4 col-xl-3 d-flex">
                        @if (feature('service_image_cards'))
                            <x-service-image-card :service="$service" class="wow fadeInUp" style="--delay: {{ ($loop->index % 4) * 100 }}ms" />
                        @else
                            <x-service-card :service="$service" class="wow fadeInUp w-100" style="--delay: {{ ($loop->index % 4) * 100 }}ms" />
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 7. Cam onarımı ve değişimi bilgilendirme alanı --}}
    <section class="kral-info">
        <div class="container">
            {{-- Uzun dekor başlık tam genişlikte; yarım sütuna sığmıyor --}}
            <x-section-deco text="Onarım mı, Değişim mi?" />
            <div class="row g-4 g-lg-5 align-items-center">
                <div class="col-lg-6">
                    <x-section-heading title="Her Çatlak Cam Değişimi Gerektirmez" />
                    <p class="kral-lead">Ön camdaki taş izi veya küçük çatlaklar, hasarın türüne, büyüklüğüne ve konumuna bağlı olarak çoğu zaman onarılabilir. Hasar büyüdüğünde ya da sürücünün görüş alanını etkilediğinde ise güvenli sürüş için cam değişimi gerekebilir.</p>
                    <p>Hasar ne kadar erken değerlendirilirse onarım seçeneğinin korunma ihtimali o kadar artar. Isı farkları, sarsıntılar ve yol koşulları küçük bir çatlağın ilerlemesine neden olabilir.</p>
                </div>
                <div class="col-lg-6">
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="kral-info-card">
                                <span class="kral-info-card__icon"><i class="bi bi-tools" aria-hidden="true"></i></span>
                                <h3>Onarım Değerlendirilir</h3>
                                <ul>
                                    <li>Küçük taş izleri ve yüzeysel hasarlar</li>
                                    <li>Henüz ilerlememiş kısa çatlaklar</li>
                                    <li>Görüş alanı dışındaki hasarlar</li>
                                </ul>
                                <a href="{{ site_url('oto-cam-onarim-ve-tamiri') }}" class="kral-more-link">Oto Cam Onarımı <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="kral-info-card kral-info-card--accent">
                                <span class="kral-info-card__icon"><i class="bi bi-arrow-repeat" aria-hidden="true"></i></span>
                                <h3>Değişim Önerilir</h3>
                                <ul>
                                    <li>Uzun ya da ilerleyen çatlaklar</li>
                                    <li>Sürücü görüş alanındaki hasarlar</li>
                                    <li>Camın kenarına ulaşan kırıklar</li>
                                </ul>
                                <a href="{{ site_url('binek-arac-cam-degisimi') }}" class="kral-more-link">Araç Cam Değişimi <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                    <p class="kral-note"><i class="bi bi-info-circle" aria-hidden="true"></i> Kesin karar, hasar yerinde incelendikten sonra verilir.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 8. Hizmet süreçleri --}}
    <section class="kral-process">
        <div class="container">
            <x-section-heading subtitle="Nasıl Çalışıyoruz?" title="Hizmet Sürecimiz" center deco />
            <ol class="kral-process__list row g-4 list-unstyled">
                @foreach ([
                    ['bi-chat-dots', 'İletişim ve Ön Bilgi', 'Bizi arayın ya da hasarın fotoğrafını WhatsApp üzerinden gönderin; aracınızın bilgilerini alalım.'],
                    ['bi-search', 'Hasar Tespiti', 'Hasarı inceleyerek onarımın mümkün olup olmadığını ve aracınıza uygun cam seçeneklerini değerlendiririz.'],
                    ['bi-tools', 'Onarım veya Değişim', 'Belirlenen işlemi aracınızın özelliklerine uygun malzeme ve yöntemlerle uygularız.'],
                    ['bi-check2-circle', 'Kontrol ve Teslim', 'İşlem sonrası kontrolleri yapar, gerekli araçlarda ADAS kalibrasyonunu tamamlayarak aracınızı teslim ederiz.'],
                ] as [$icon, $title, $text])
                    <li class="col-sm-6 col-lg-3">
                        <div class="kral-step wow fadeInUp" style="--delay: {{ $loop->index * 120 }}ms">
                            <span class="kral-step__no" aria-hidden="true">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="kral-step__icon"><i class="bi {{ $icon }}" aria-hidden="true"></i></span>
                            <h3 class="kral-step__title">{{ $title }}</h3>
                            <p>{{ $text }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- 9. Galeri önizlemesi (referans: .wptb-project) --}}
    @if ($galleryImages->isNotEmpty())
        <section class="wptb-project kral-gallery-preview">
            <div class="container">
                <x-section-heading subtitle="Galeri" title="Atölyemizden Kareler" center deco />
                <div class="effect-gradient has-radius">
                    <div class="row g-3">
                        @foreach ($galleryImages as $image)
                            <div class="col-sm-6 col-lg-4">
                                <x-gallery-item :image="$image" group="anasayfa-galeri" :caption="$image->album->title" />
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="text-center mt-5">
                    <x-theme-button :href="site_url('galeri')">Tüm Galeri</x-theme-button>
                </div>
            </div>
        </section>
    @endif

    {{-- 10. Blog önizlemesi (referans: .wptb-blog-grid-one) --}}
    <section class="wptb-blog-grid-one kral-blog-preview">
        <div class="container">
            <div class="wptb-heading">
                <div class="wptb-item--inner">
                    <x-section-deco text="Blog" />
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h2 class="wptb-item--title mb-0">Oto Cam Rehberi</h2>
                        </div>
                        <div class="col-lg-6">
                            <p class="wptb-item--description">Araç camı bakımı, onarımı, değişimi ve ADAS kalibrasyonu hakkında bilgilendirici yazılar.</p>
                        </div>
                    </div>
                </div>
            </div>
            @if ($posts->isNotEmpty())
                <div class="wptb-blog--inner">
                    <div class="row g-4">
                        @foreach ($posts as $post)
                            <div class="col-lg-4 col-sm-6"><x-blog-card :post="$post" /></div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="kral-empty">
                    <i class="bi bi-journal-text" aria-hidden="true"></i>
                    <p>Blog yazılarımız hazırlanıyor. Sorularınız için bize WhatsApp üzerinden ulaşabilirsiniz.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- 11. İletişim ve WhatsApp çağrı alanı --}}
    <x-contact-cta />

</x-layouts.app>
