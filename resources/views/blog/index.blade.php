<x-layouts.app :seo="$seo">
    <x-page-heading :title="$category ? $category->name : 'Blog'" :seo="$seo" />

    <section class="kral-section wptb-blog-grid-one">
        <div class="container">
            @if ($category?->description)
                <p class="kral-lead text-center mx-auto kral-narrow">{{ $category->description }}</p>
            @endif

            @if ($categories->where('posts_count', '>', 0)->isNotEmpty())
                <nav class="kral-filter" aria-label="Blog kategorileri">
                    <ul class="list-unstyled d-flex flex-wrap justify-content-center gap-2">
                        <li><a href="{{ site_url('blog') }}" @class(['active' => ! $category])>Tümü</a></li>
                        @foreach ($categories->where('posts_count', '>', 0) as $item)
                            <li><a href="{{ $item->url() }}" @class(['active' => $category?->is($item)])>{{ $item->name }} <span class="badge">{{ $item->posts_count }}</span></a></li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            @if ($posts->isEmpty())
                <div class="kral-empty">
                    <i class="bi bi-journal-text" aria-hidden="true"></i>
                    <p>Henüz yayınlanmış bir yazı bulunmuyor. Blog yazılarımız hazırlanıyor.</p>
                </div>
            @else
                <div class="row g-4">
                    @foreach ($posts as $post)
                        <div class="col-lg-4 col-sm-6"><x-blog-card :post="$post" /></div>
                    @endforeach
                </div>
                <div class="mt-5">{{ $posts->links() }}</div>
            @endif
        </div>
    </section>

    <x-contact-cta />
</x-layouts.app>
