{{-- Bölüm başlığı arkasındaki dekoratif kelime: harfler zemin renginde, yalnızca kırmızı bant üzerinde görünür. --}}
@props(['text', 'center' => false])
<div @class(['kral-deco', 'kral-deco--center' => $center]) aria-hidden="true">
    <span style="--deco-len: {{ max(6, mb_strlen($text)) }}">{{ $text }}</span>
</div>
