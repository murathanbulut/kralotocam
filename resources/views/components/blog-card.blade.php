{{-- Referans temadaki .wptb-blog-grid1 kartı --}}
@props(['post'])
<article class="wptb-blog-grid1 highlight wow fadeInUp">
    <div class="wptb-item--inner">
        <div class="wptb-item--image">
            <a href="{{ $post->url() }}" class="wptb-item-link" tabindex="-1" aria-hidden="true">
                @if ($post->cover_image)
                    <img src="{{ media_url($post->cover_image) }}" alt="{{ $post->title }}" loading="lazy" width="950" height="474">
                @else
                    <span class="kral-image-placeholder"><img src="{{ asset('images/theme/favicon.png') }}" alt="" width="64" height="64"></span>
                @endif
            </a>
        </div>
        <div class="wptb-item--holder">
            <div class="wptb-item--date">
                <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->translatedFormat('d F Y') }}</time>
                @if ($post->category)
                    · <a href="{{ $post->category->url() }}">{{ $post->category->name }}</a>
                @endif
            </div>
            <h3 class="wptb-item--title h4"><a href="{{ $post->url() }}">{{ $post->title }}</a></h3>
            @if ($post->excerpt)
                <p class="kral-blog-excerpt">{{ \Illuminate\Support\Str::limit($post->excerpt, 140) }}</p>
            @endif
        </div>
    </div>
</article>
