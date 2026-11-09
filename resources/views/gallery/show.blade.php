<x-layouts.app :seo="$seo">
    <x-page-heading :title="$album->title" :seo="$seo" :image="$album->coverPath()" />

    <section class="kral-section wptb-project">
        <div class="container">
            <nav class="kral-filter" aria-label="Galeri albümleri">
                <ul class="list-unstyled d-flex flex-wrap justify-content-center gap-2">
                    <li><a href="{{ site_url('galeri') }}">Tümü</a></li>
                    @foreach ($albums as $item)
                        <li><a href="{{ $item->url() }}" @class(['active' => $item->is($album)]) @if ($item->is($album)) aria-current="page" @endif>{{ $item->title }}</a></li>
                    @endforeach
                </ul>
            </nav>

            @if ($album->description)
                <p class="kral-lead text-center mx-auto kral-narrow">{{ $album->description }}</p>
            @endif

            @if ($images->isEmpty())
                <div class="kral-empty"><i class="bi bi-images" aria-hidden="true"></i><p>Bu albüme henüz görsel eklenmedi.</p></div>
            @else
                <div class="effect-gradient has-radius">
                    <div class="row g-3">
                        @foreach ($images as $image)
                            <div class="col-sm-6 col-lg-4">
                                <x-gallery-item :image="$image" :group="'album-'.$album->id" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <x-contact-cta />
</x-layouts.app>
