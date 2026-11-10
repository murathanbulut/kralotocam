<x-layouts.app :seo="$seo">
    <x-page-heading title="Galeri" :seo="$seo" />

    <section class="kral-section wptb-project">
        <div class="container">
            @if ($albums->isEmpty())
                <div class="kral-empty"><i class="bi bi-images" aria-hidden="true"></i><p>Galeriye henüz görsel eklenmedi.</p></div>
            @else
                {{-- Yalnızca görseller: yazı ve filtre yok --}}
                <h2 class="visually-hidden">Uygulamalarımız</h2>
                <x-gallery-showcase
                    :images="$albums->flatMap(fn ($album) => $album->images->each(fn ($image) => $image->setRelation('album', $album)))"
                    group="galeri"
                    label="Tüm galeri görselleri" />
            @endif
        </div>
    </section>

    <x-contact-cta />
</x-layouts.app>
