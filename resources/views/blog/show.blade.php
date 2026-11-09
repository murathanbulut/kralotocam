<x-layouts.app :seo="$seo">
    <x-page-heading :title="$post->title" :seo="$seo" :image="$post->cover_image" />

    <section class="kral-section blog-details">
        <div class="container">
            @if ($isPreview)
                <div class="alert alert-warning kral-alert" role="status">
                    <i class="bi bi-eye" aria-hidden="true"></i>
                    Bu yazı <strong>taslak</strong> durumundadır ve yalnızca yönetici oturumunda önizlenir. Ziyaretçiler bu sayfayı göremez.
                </div>
            @endif
            <div class="row g-4 g-lg-5">
                <div class="col-lg-8">
                    <article class="kral-content">
                        <div class="kral-post-meta">
                            @if ($post->published_at)
                                <span><i class="bi bi-calendar3" aria-hidden="true"></i> <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->published_at->translatedFormat('d F Y') }}</time></span>
                            @endif
                            @if ($post->category)
                                <span><i class="bi bi-folder2" aria-hidden="true"></i> <a href="{{ $post->category->url() }}">{{ $post->category->name }}</a></span>
                            @endif
                        </div>

                        @if ($post->cover_image)
                            <figure class="kral-content__figure">
                                <img src="{{ media_url($post->cover_image) }}" alt="{{ $post->title }}" width="1600" height="900">
                            </figure>
                        @endif

                        @if ($post->excerpt)
                            <p class="kral-lead">{{ $post->excerpt }}</p>
                        @endif

                        {!! $post->content !!}

                        <div class="kral-content__actions">
                            <x-theme-button :href="site_url('iletisim').'#iletisim-formu'">Bize Danışın</x-theme-button>
                            <a class="kral-wa-link" href="{{ whatsapp_url() }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp" aria-hidden="true"></i> WhatsApp</a>
                        </div>
                    </article>
                </div>
                <aside class="col-lg-4">
                    <div class="sidebar kral-sidebar">
                        @if ($recent->isNotEmpty())
                            <div class="widget">
                                <h2 class="widget-title">Son Yazılar</h2>
                                <ul class="kral-side-nav">
                                    @foreach ($recent as $item)
                                        <li><a href="{{ $item->url() }}">{{ $item->title }} <i class="bi bi-chevron-right" aria-hidden="true"></i></a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if ($categories->where('posts_count', '>', 0)->isNotEmpty())
                            <div class="widget">
                                <h2 class="widget-title">Kategoriler</h2>
                                <ul class="kral-side-nav">
                                    @foreach ($categories->where('posts_count', '>', 0) as $item)
                                        <li><a href="{{ $item->url() }}">{{ $item->name }} ({{ $item->posts_count }}) <i class="bi bi-chevron-right" aria-hidden="true"></i></a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @include('partials.sidebar-contact')
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.app>
