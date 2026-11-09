<x-layouts.app :seo="$seo">
    <x-page-heading title="Galeri" :seo="$seo" />

    <section class="kral-section wptb-project">
        <div class="container">
            @if ($albums->isEmpty())
                <div class="kral-empty"><i class="bi bi-images" aria-hidden="true"></i><p>Galeriye henüz görsel eklenmedi.</p></div>
            @else
                <nav class="kral-filter" aria-label="Galeri albümleri">
                    <ul class="list-unstyled d-flex flex-wrap justify-content-center gap-2">
                        @foreach ($albums as $album)
                            <li><a href="{{ $album->url() }}">{{ $album->title }} <span class="badge">{{ $album->images_count }}</span></a></li>
                        @endforeach
                    </ul>
                </nav>

                @foreach ($albums as $album)
                    <div class="kral-album">
                        <div class="kral-album__head">
                            <h2 class="kral-album__title"><a href="{{ $album->url() }}">{{ $album->title }}</a></h2>
                            @if ($album->description)
                                <p>{{ $album->description }}</p>
                            @endif
                        </div>
                        <div class="effect-gradient has-radius">
                            <div class="row g-3">
                                @foreach ($album->images as $image)
                                    <div class="col-sm-6 col-lg-4">
                                        <x-gallery-item :image="$image" :group="'album-'.$album->id" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>

    <x-contact-cta />
</x-layouts.app>
