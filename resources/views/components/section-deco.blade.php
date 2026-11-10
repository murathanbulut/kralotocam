{{-- Bölüm başlığı üstündeki dekoratif kelime: düz beyaz, büyük harfli yazı (bant/çizgi yok). --}}
@props(['text', 'center' => false])
<div @class(['kral-deco', 'kral-deco--center' => $center]) aria-hidden="true">
    <span style="--deco-len: {{ max(6, mb_strlen($text)) }}"><span class="kral-deco__text">{{ $text }}</span></span>
</div>
