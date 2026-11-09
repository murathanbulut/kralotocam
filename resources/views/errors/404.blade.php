@php
    $seo = \App\Support\Seo::make('Sayfa Bulunamadı')->noindex()->breadcrumbs([['Sayfa Bulunamadı']]);
    $services = \App\Models\Service::query()->active()->ordered()->get();
@endphp
<x-layouts.app :seo="$seo">
    <x-page-heading title="Sayfa Bulunamadı" :seo="$seo" />

    <section class="kral-section">
        <div class="container text-center">
            <p class="kral-404">404</p>
            <p class="kral-lead kral-narrow mx-auto">Aradığınız sayfa taşınmış, kaldırılmış veya adresi hatalı yazılmış olabilir. Aşağıdaki bağlantılardan devam edebilirsiniz.</p>
            <div class="d-flex flex-wrap justify-content-center gap-3 my-4">
                <x-theme-button :href="site_url()">Ana Sayfa</x-theme-button>
                <x-theme-button :href="site_url('iletisim')" variant="btn-two white">İletişim</x-theme-button>
            </div>
            @if ($services->isNotEmpty())
                <h2 class="h4 mt-5 mb-3">Hizmetlerimiz</h2>
                <ul class="list-unstyled d-flex flex-wrap justify-content-center gap-2 kral-filter">
                    @foreach ($services as $service)
                        <li><a href="{{ $service->url() }}">{{ $service->label() }}</a></li>
                    @endforeach
                </ul>
            @endif
        </div>
    </section>
</x-layouts.app>
