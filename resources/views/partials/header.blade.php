@php
    $logo = media_url(setting('logo'), 'images/theme/logo.png');
    $siteName = setting('site_name');
@endphp
<header class="header color-fixed" id="site-header">
    {{-- Üst iletişim bandı --}}
    <div class="header-top kral-topbar d-none d-md-block">
        <div class="container-fluid">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <ul class="info-list d-flex align-items-center flex-wrap m-0 p-0">
                    <li class="kral-topbar__address"><a href="{{ setting('map_link') }}" target="_blank" rel="noopener"><span class="icon"><i class="bi bi-geo-alt" aria-hidden="true"></i></span>{{ setting('address') }}</a></li>
                    <li class="d-none d-lg-inline-flex"><a href="mailto:{{ setting('email') }}"><span class="icon"><i class="bi bi-envelope" aria-hidden="true"></i></span>{{ setting('email') }}</a></li>
                </ul>
                <ul class="info-list d-flex align-items-center m-0 p-0">
                    <li><a href="{{ phone_href(setting('phone_landline')) }}"><span class="icon"><i class="bi bi-telephone" aria-hidden="true"></i></span>{{ setting('phone_landline') }}</a></li>
                    <li><a href="{{ whatsapp_url() }}" target="_blank" rel="noopener"><span class="icon"><i class="bi bi-whatsapp" aria-hidden="true"></i></span>{{ setting('phone_gsm') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="header-inner">
        <div class="container-fluid pe-0">
            <div class="d-flex align-items-center justify-content-between">
                <div class="header_left_part d-flex align-items-center">
                    <div class="logo">
                        <a href="{{ site_url() }}" class="light_logo" aria-label="{{ $siteName }} ana sayfa">
                            <img src="{{ $logo }}" alt="{{ $siteName }} logosu" width="553" height="100">
                        </a>
                    </div>
                </div>

                <nav class="header_center_part d-none d-xl-block" aria-label="Ana menü">
                    <div class="mainnav">
                        @include('partials.menu', ['menu' => $menu])
                    </div>
                </nav>

                <div class="header_right_part d-flex align-items-center">
                    <a href="{{ whatsapp_url() }}" class="btn kral-header-btn d-none d-sm-inline-flex" target="_blank" rel="noopener">
                        <i class="bi bi-whatsapp" aria-hidden="true"></i><span>WhatsApp</span>
                    </a>

                    <button type="button" class="aside_open wptb-element d-none d-xl-flex" aria-label="İletişim panelini aç" aria-controls="aside-info" aria-expanded="false">
                        <span class="aside-open--inner"><span></span><span></span><span></span></span>
                    </button>

                    <button type="button" class="mr_menu_toggle wptb-element d-xl-none" aria-label="Menüyü aç" aria-controls="mobile-menu" aria-expanded="false">
                        <i class="bi bi-list" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Mobil menü --}}
<div class="mr_menu" id="mobile-menu" aria-label="Mobil menü" aria-hidden="true">
    <button type="button" class="mr_menu_close" aria-label="Menüyü kapat"><i class="bi bi-x-lg" aria-hidden="true"></i></button>
    <div class="logo">
        <a href="{{ site_url() }}"><img src="{{ $logo }}" alt="{{ $siteName }} logosu" width="553" height="100"></a>
    </div>
    <nav class="mr_navmenu" aria-label="Mobil ana menü">
        @include('partials.menu', ['menu' => $menu, 'mobile' => true])
    </nav>
    <h6>İletişim</h6>
    <ul class="kral-contact-list">
        <li><i class="bi bi-whatsapp" aria-hidden="true"></i><a href="{{ whatsapp_url() }}" target="_blank" rel="noopener">{{ setting('phone_gsm') }}</a></li>
        <li><i class="bi bi-telephone" aria-hidden="true"></i><a href="{{ phone_href(setting('phone_landline')) }}">{{ setting('phone_landline') }}</a></li>
        <li><i class="bi bi-envelope" aria-hidden="true"></i><a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a></li>
        <li><i class="bi bi-geo-alt" aria-hidden="true"></i><span>{{ setting('address') }}</span></li>
    </ul>
</div>
<div class="kral-menu-backdrop" data-menu-close></div>

{{-- Masaüstü yan bilgi paneli (referans temadaki aside_info_wrapper) --}}
<div class="aside_info_wrapper" id="aside-info" aria-hidden="true">
    <button type="button" class="aside_close">Kapat <i class="bi bi-x-lg" aria-hidden="true"></i></button>
    <div class="aside_logo logo">
        <a href="{{ site_url() }}" class="light_logo"><img src="{{ $logo }}" alt="{{ $siteName }} logosu" width="553" height="100"></a>
    </div>
    <div class="aside_info_inner">
        <p class="kral-aside-text">{{ setting('footer_text') }}</p>

        <h6>// İletişim Bilgilerimiz</h6>
        <div class="wptb-icon-box1 style2">
            <div class="wptb-item--inner flex-start">
                <div class="wptb-item--icon"><i class="bi bi-whatsapp" aria-hidden="true"></i></div>
                <div class="wptb-item--holder"><p class="wptb-item--description"><a href="{{ whatsapp_url() }}" target="_blank" rel="noopener">GSM / WhatsApp: {{ setting('phone_gsm') }}</a></p></div>
            </div>
        </div>
        <div class="wptb-icon-box1 style2">
            <div class="wptb-item--inner flex-start">
                <div class="wptb-item--icon"><i class="bi bi-telephone" aria-hidden="true"></i></div>
                <div class="wptb-item--holder"><p class="wptb-item--description"><a href="{{ phone_href(setting('phone_landline')) }}">{{ setting('phone_landline') }}</a></p></div>
            </div>
        </div>
        <div class="wptb-icon-box1 style2">
            <div class="wptb-item--inner flex-start">
                <div class="wptb-item--icon"><i class="bi bi-envelope" aria-hidden="true"></i></div>
                <div class="wptb-item--holder"><p class="wptb-item--description"><a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a></p></div>
            </div>
        </div>
        <div class="wptb-icon-box1 style2">
            <div class="wptb-item--inner flex-start">
                <div class="wptb-item--icon"><i class="bi bi-geo-alt" aria-hidden="true"></i></div>
                <div class="wptb-item--holder"><p class="wptb-item--description"><a href="{{ site_url('iletisim') }}">{{ setting('address') }}</a></p></div>
            </div>
        </div>

        @include('partials.social', ['class' => 'social-box style-square'])
    </div>
</div>
