<x-layouts.app :seo="$seo">
    <x-page-heading title="Hizmetlerimiz" :seo="$seo" />

    <section class="kral-section kral-services">
        <div class="container">
            <x-section-heading subtitle="Kral Oto Cam" title="Eskişehir Oto Cam Hizmetleri" center>
                Aracınızın türüne ve hasarın durumuna göre uygun hizmeti seçin; detay sayfasında süreç ve dikkat edilmesi gerekenler hakkında bilgi bulabilirsiniz.
            </x-section-heading>
            <div class="row g-4 justify-content-center">
                @foreach ($services as $service)
                    <div class="col-md-6 col-lg-4 d-flex">
                        <x-service-card :service="$service" heading-tag="h2" class="wow fadeInUp w-100" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <x-contact-cta />
</x-layouts.app>
