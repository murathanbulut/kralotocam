@props(['service'])
@php($icon = $service->icon ?: 'bi-car-front')
@if (\Illuminate\Support\Str::endsWith($icon, ['.svg', '.png', '.jpg', '.jpeg', '.webp']))
    <img src="{{ media_url($icon) }}" alt="" aria-hidden="true" width="64" height="64" loading="lazy">
@else
    <i class="bi {{ $icon }} kral-service-glyph" aria-hidden="true"></i>
@endif
