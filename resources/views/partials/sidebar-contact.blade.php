<div class="widget kral-side-contact">
    <h2 class="widget-title">Bize Ulaşın</h2>
    <p>Cam hasarınızla ilgili bilgi almak için bizi arayabilir veya WhatsApp üzerinden fotoğraf gönderebilirsiniz.</p>
    <a class="kral-side-contact__btn kral-side-contact__btn--wa" href="{{ whatsapp_url() }}" target="_blank" rel="noopener"><i class="bi bi-whatsapp" aria-hidden="true"></i> {{ setting('phone_gsm') }}</a>
    <a class="kral-side-contact__btn" href="{{ phone_href(setting('phone_landline')) }}"><i class="bi bi-telephone" aria-hidden="true"></i> {{ setting('phone_landline') }}</a>
    <a class="kral-side-contact__link" href="{{ site_url('iletisim') }}#iletisim-formu">İletişim formu <i class="bi bi-arrow-right" aria-hidden="true"></i></a>
</div>
