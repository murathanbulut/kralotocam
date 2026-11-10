{{--
    Anlaşmalı sigorta / filo kuruluşları listesi.
    $organizations yalnızca aktif VE doğrulanmış kayıtları içerir (SlugController → published()).
    Logo, kullanım hakkı onaylanmadıysa gösterilmez; yerine kurum adı yazılır.
--}}
@php
    $isInsurance = $template === \App\Models\Page::TEMPLATE_INSURANCE;
    $heading = $isInsurance ? 'Anlaşmalı Sigorta Şirketleri' : 'İş Birliği Yaptığımız Filo Kuruluşları';
@endphp
<section class="kral-orgs mt-5" aria-labelledby="kurumlar-baslik">
    <h2 id="kurumlar-baslik" class="kral-orgs__title">{{ $heading }}</h2>

    @if ($organizations && $organizations->isNotEmpty())
        <ul class="row g-3 list-unstyled m-0">
            @foreach ($organizations as $org)
                <li class="col-6 col-md-4" data-org-item>
                    <div class="kral-org">
                        @if ($logo = $org->publicLogo())
                            <img src="{{ $logo }}" alt="{{ $org->name }}" loading="lazy" width="160" height="64">
                        @else
                            <span class="kral-org__name">{{ $org->name }}</span>
                        @endif
                        @if ($org->description)
                            <p class="kral-org__text">{{ $org->description }}</p>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
        @if ($isInsurance)
            <p class="kral-orgs__note">Anlaşmalı kurumlar ve işlem koşulları değişebilir. Başvuru öncesinde güncel anlaşma durumunu ve poliçe kapsamınızı teyit ediniz.</p>
        @endif
    @else
        <p class="kral-orgs__empty" data-org-empty>
            {{ $isInsurance
                ? 'Güncel anlaşma durumu sigorta şirketine ve poliçenize göre değişebilir. Sigorta şirketinizle işlem yapılıp yapılamayacağını öğrenmek için bizimle iletişime geçin.'
                : 'Filo şirketinizin prosedürüne göre hizmet verilip verilemeyeceğini öğrenmek için bizimle iletişime geçin.' }}
        </p>
    @endif

    <div class="d-flex flex-wrap gap-3 mt-4">
        <x-theme-button :href="whatsapp_url()" external>WhatsApp İletişim Hattı</x-theme-button>
        <x-theme-button :href="site_url('iletisim')" variant="btn-two white">İletişime Geçin</x-theme-button>
    </div>
</section>
