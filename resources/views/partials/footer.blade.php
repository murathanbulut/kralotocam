@php($siteName = setting('site_name'))
<footer class="footer style1 kral-footer">
    <div class="footer-top">
        <div class="container">
            <div class="footer--inner">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-6 mb-5 mb-md-0">
                        <div class="footer-widget">
                            <h2 class="kral-footer-title">Hizmetlerimiz</h2>
                            <div class="footer-nav">
                                <ul>
                                    @foreach ($footerServices as $service)
                                        <li class="menu-item"><a href="{{ $service->url() }}">{{ $service->label() }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 mb-5 mb-md-0 order-1 order-md-0">
                        <div class="footer-widget text-center">
                            <div class="logo kral-footer-logo">
                                <a href="{{ site_url() }}"><img src="{{ media_url(setting('logo'), 'images/theme/logo.png') }}" alt="{{ $siteName }} logosu" width="553" height="100" loading="lazy"></a>
                            </div>
                            <p class="kral-footer-text">{{ setting('footer_text') }}</p>
                            <address class="kral-footer-contact">
                                <a href="{{ setting('map_link') }}" target="_blank" rel="noopener" class="kral-footer-address">
                                    <i class="bi bi-geo-alt" aria-hidden="true"></i>
                                    @foreach (address_lines(setting('address')) as $line)
                                        @if (! $loop->first)<br>@endif<span @class(['text-nowrap' => $loop->last && ! $loop->first])>{{ $line }}</span>
                                    @endforeach
                                </a>
                                <a href="{{ whatsapp_url() }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp" aria-hidden="true"></i> GSM / WhatsApp: {{ setting('phone_gsm') }}</a>
                                <a href="{{ phone_href(setting('phone_landline')) }}"><i class="bi bi-telephone" aria-hidden="true"></i> Sabit: {{ setting('phone_landline') }}</a>
                                <a href="mailto:{{ setting('email') }}"><i class="bi bi-envelope" aria-hidden="true"></i> {{ setting('email') }}</a>
                                @if (setting('working_hours'))
                                    <span><i class="bi bi-clock" aria-hidden="true"></i> {{ setting('working_hours') }}</span>
                                @endif
                            </address>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4 col-sm-6 mb-5 mb-md-0">
                        <div class="footer-widget text-md-end">
                            <h2 class="kral-footer-title">Kurumsal</h2>
                            <div class="footer-nav">
                                <ul>
                                    <li class="menu-item"><a href="{{ site_url('hakkimizda') }}">Hakkımızda</a></li>
                                    <li class="menu-item"><a href="{{ site_url('misyonumuz') }}">Misyonumuz</a></li>
                                    <li class="menu-item"><a href="{{ site_url('vizyonumuz') }}">Vizyonumuz</a></li>
                                    <li class="menu-item"><a href="{{ site_url('galeri') }}">Galeri</a></li>
                                    <li class="menu-item"><a href="{{ site_url('blog') }}">Blog</a></li>
                                    <li class="menu-item"><a href="{{ site_url('iletisim') }}">İletişim</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <div class="copyright">
                    <p>© {{ now()->year }} {{ $siteName }}. Tüm hakları saklıdır.</p>
                </div>
                <div class="kral-footer-bottom-right">
                    @include('partials.social')
                    <p class="kral-credit">
                        <a href="https://alphacreative.com.tr" target="_blank" rel="noopener">Alpha Creative</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
