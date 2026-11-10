<x-layouts.app :seo="$seo">
    <x-page-heading title="Galeri" :seo="$seo" />

    <section class="kral-section wptb-project">
        <div class="container">
            @if ($albums->isEmpty())
                <div class="kral-empty"><i class="bi bi-images" aria-hidden="true"></i><p>Galeriye henüz görsel eklenmedi.</p></div>
            @else
                {{-- Albüm filtresi: her albüm kendi sayfasında kendi yatay görsel grubunu gösterir --}}
                <nav class="kral-filter" aria-label="Galeri albümleri">
                    <ul class="list-unstyled d-flex flex-wrap justify-content-center gap-2">
                        <li><a href="{{ site_url('galeri') }}" class="active" aria-current="page">Tümü <span class="badge">{{ $albums->sum('images_count') }}</span></a></li>
                        @foreach ($albums as $album)
                            <li><a href="{{ $album->url() }}">{{ $album->title }} <span class="badge">{{ $album->images_count }}</span></a></li>
                        @endforeach
                    </ul>
                </nav>

                {{-- Tüm albümlerin aktif görselleri tek vitrinde; her kartta albüm adı --}}
                <h2 class="visually-hidden">Uygulamalarımız</h2>
                <x-gallery-showcase
                    :images="$albums->flatMap(fn ($album) => $album->images->each(fn ($image) => $image->setRelation('album', $album)))"
                    group="galeri"
                    show-album
                    label="Tüm galeri görselleri" />
            @endif
        </div>
    </section>

    <x-contact-cta />
</x-layouts.app>
