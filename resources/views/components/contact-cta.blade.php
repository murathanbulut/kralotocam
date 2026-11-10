{{-- İletişim ve WhatsApp çağrı alanı --}}
@props(['title' => 'Aracınızın camı için doğru çözümü birlikte belirleyelim', 'text' => null])
<section class="kral-cta">
    <div class="container">
        <div class="kral-cta__box">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <p class="kral-eyebrow">Hasar Değerlendirmesi</p>
                    <h2 class="kral-cta__title">{{ $title }}</h2>
                    <p class="kral-cta__text">{{ $text ?? 'Cam hasarınızın fotoğrafını WhatsApp üzerinden gönderebilir veya bizi arayarak bilgi alabilirsiniz. Onarımın mümkün olup olmadığını ve uygun işlemi birlikte değerlendirelim.' }}</p>
                </div>
                <div class="col-lg-5">
                    <div class="kral-cta__actions">
                        <a class="kral-cta__btn kral-cta__btn--whatsapp" href="{{ whatsapp_url() }}" target="_blank" rel="noopener">
                            <span class="kral-cta__btn-icon"><i class="bi bi-whatsapp" aria-hidden="true"></i></span>
                            <span class="kral-cta__btn-text"><small>WhatsApp İletişim Hattı</small>{{ setting('phone_gsm') }}</span>
                            <i class="bi bi-arrow-up-right kral-cta__btn-arrow" aria-hidden="true"></i>
                        </a>
                        <a class="kral-cta__btn kral-cta__btn--phone" href="{{ phone_href(setting('phone_landline')) }}">
                            <span class="kral-cta__btn-icon"><i class="bi bi-telephone" aria-hidden="true"></i></span>
                            <span class="kral-cta__btn-text"><small>Telefon</small>{{ setting('phone_landline') }}</span>
                            <i class="bi bi-arrow-up-right kral-cta__btn-arrow" aria-hidden="true"></i>
                        </a>
                        <a class="kral-cta__link" href="{{ site_url('iletisim') }}#iletisim-formu">İletişim formunu doldurun <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
