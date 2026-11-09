{{-- Referans temadaki .btn.btn-two animasyonlu buton --}}
@props(['href', 'icon' => 'bi-arrow-up-right', 'variant' => 'btn-two', 'external' => false])
<a {{ $attributes->merge(['class' => 'btn '.$variant]) }} href="{{ $href }}" @if ($external) target="_blank" rel="noopener" @endif>
    <span class="btn-wrap">
        <span class="text-first">{{ $slot }}</span>
        <span class="text-second" aria-hidden="true"><i class="bi {{ $icon }}"></i> <i class="bi {{ $icon }}"></i></span>
    </span>
</a>
