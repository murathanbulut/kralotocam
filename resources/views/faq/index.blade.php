{{--
    Sıkça Sorulan Sorular. Akordeon: temanın .wptb-accordion1 görünümü, yerel <details>/<summary> ile
    (klavye: Tab ile odaklanır, Enter/Boşluk ile açılır-kapanır; JS gerektirmez).
--}}
<x-layouts.app :seo="$seo">
    <x-page-heading title="Sıkça Sorulan Sorular" :seo="$seo" />

    <section class="kral-section kral-faq">
        <div class="container">
            <div class="row g-4 g-lg-5">
                <div class="col-lg-8">
                    <p class="kral-lead">Kral Oto Cam'ın araç camı değişimi, onarımı, ADAS kalibrasyonu, cam filmi ve sigorta süreçleriyle ilgili sıkça sorulan sorularını bu sayfada bulabilirsiniz.</p>

                    @forelse ($groups as $key => $items)
                        <div class="kral-faq__group">
                            <h2 class="kral-faq__category">{{ \App\Models\Faq::CATEGORIES[$key] ?? 'Genel' }}</h2>
                            <div class="wptb-accordion1 kral-accordion">
                                @foreach ($items as $faq)
                                    <details class="wptb--item" id="soru-{{ $faq->id }}" data-faq-item>
                                        <summary class="wptb-item-title">
                                            <span>{{ $faq->question }}</span>
                                            <i class="bi bi-plus-lg plus" aria-hidden="true"></i>
                                            <i class="bi bi-dash-lg minus" aria-hidden="true"></i>
                                        </summary>
                                        <div class="wptb-item--content">
                                            <p>{{ $faq->answer }}</p>
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="kral-orgs__empty">Şu anda yayında soru bulunmuyor. Sorularınız için bizimle iletişime geçebilirsiniz.</p>
                    @endforelse
                </div>
                <aside class="col-lg-4">
                    <div class="sidebar kral-sidebar">
                        @if ($corporatePages->isNotEmpty())
                            <div class="widget">
                                <h2 class="widget-title">Kurumsal</h2>
                                <ul class="kral-side-nav">
                                    @foreach ($corporatePages as $item)
                                        <li><a href="{{ $item->url() }}">{{ $item->title }} <i class="bi bi-chevron-right" aria-hidden="true"></i></a></li>
                                    @endforeach
                                    <li class="active"><a href="{{ site_url('sikca-sorulan-sorular') }}" aria-current="page">Sıkça Sorulan Sorular <i class="bi bi-chevron-right" aria-hidden="true"></i></a></li>
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
