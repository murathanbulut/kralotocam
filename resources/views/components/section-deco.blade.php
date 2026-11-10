{{-- Bölüm başlığı arkasındaki dekoratif kelime: açık renk harfler, arkada vurgu renginde bant.
     İç span, bandın (::before) üstünde çizilir; harflerin bant üzerindeki kısmı koyulaşır. --}}
@props(['text', 'center' => false])
<div @class(['kral-deco', 'kral-deco--center' => $center]) aria-hidden="true">
    <span style="--deco-len: {{ max(6, mb_strlen($text)) }}"><span class="kral-deco__text">{{ $text }}</span></span>
</div>
