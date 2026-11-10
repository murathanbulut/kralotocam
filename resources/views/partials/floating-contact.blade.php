{{-- Sabit telefon / WhatsApp butonları (canlı sitedeki .fgapi alanının karşılığı) --}}
<div class="kral-floating" aria-label="Hızlı iletişim">
    <a href="{{ phone_href(setting('phone_gsm')) }}" class="kral-floating__btn kral-floating__btn--phone" aria-label="Telefonla arayın: {{ setting('phone_gsm') }}">
        <i class="bi bi-telephone-fill" aria-hidden="true"></i>
        <span class="kral-floating__tip">Hemen Arayın</span>
    </a>
    <a href="{{ whatsapp_url() }}" class="kral-floating__btn kral-floating__btn--whatsapp" target="_blank" rel="noopener" aria-label="WhatsApp İletişim Hattı">
        <i class="bi bi-whatsapp" aria-hidden="true"></i>
        <span class="kral-floating__tip">WhatsApp</span>
    </a>
</div>
