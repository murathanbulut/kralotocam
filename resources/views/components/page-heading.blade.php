@props(['title', 'seo', 'image' => null])
@php($bg = media_url($image, 'images/seo/page-heading.jpg'))
<section class="wptb-page-heading">
    <div class="wptb-item--inner" style="background-image: url('{{ $bg }}');">
        <div class="wptb-item-layer wptb-item-layer-one">
            <img src="{{ asset('images/theme/layer-3.png') }}" alt="" aria-hidden="true">
        </div>
        <h1 class="wptb-item--title">{{ $title }}</h1>
        @if (count($seo->breadcrumbs) > 1)
            <nav aria-label="Sayfa konumu">
                <ol class="wptb-breadcrumb list-unstyled d-flex justify-content-center">
                    @foreach ($seo->breadcrumbs as $crumb)
                        @if (! $loop->last && $crumb['url'])
                            <li><a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a></li>
                        @elseif (! $loop->last)
                            <li><span class="kral-crumb-plain">{{ $crumb['label'] }}</span></li>
                        @else
                            <li><span aria-current="page">{{ $crumb['label'] }}</span></li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        @endif
    </div>
</section>
