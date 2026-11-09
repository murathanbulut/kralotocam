<x-layouts.app :seo="$seo">
    <x-page-heading :title="$page->title" :seo="$seo" :image="$page->cover_image" />

    <section class="kral-section">
        <div class="container">
            <div class="row g-4 g-lg-5">
                <div class="col-lg-8">
                    <article class="kral-content">
                        @if ($page->excerpt)
                            <p class="kral-lead">{{ $page->excerpt }}</p>
                        @endif
                        {!! $page->content !!}
                    </article>
                </div>
                <aside class="col-lg-4">
                    <div class="sidebar kral-sidebar">
                        @if ($corporatePages->isNotEmpty())
                            <div class="widget">
                                <h2 class="widget-title">Kurumsal</h2>
                                <ul class="kral-side-nav">
                                    @foreach ($corporatePages as $item)
                                        <li @class(['active' => $item->is($page)])>
                                            <a href="{{ $item->url() }}" @if ($item->is($page)) aria-current="page" @endif>{{ $item->title }} <i class="bi bi-chevron-right" aria-hidden="true"></i></a>
                                        </li>
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

    <x-contact-cta />
</x-layouts.app>
