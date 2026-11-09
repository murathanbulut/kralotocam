@php
    $socials = array_filter([
        'facebook' => setting('facebook_url'),
        'instagram' => setting('instagram_url'),
        'twitter-x' => setting('x_url'),
        'youtube' => setting('youtube_url'),
    ]);
    $labels = ['facebook' => 'Facebook', 'instagram' => 'Instagram', 'twitter-x' => 'X', 'youtube' => 'YouTube'];
@endphp
@if ($socials)
    @isset($title)<h6>{{ $title }}</h6>@endisset
    <div class="{{ $class ?? 'social-box style-oval' }}">
        <ul>
            @foreach ($socials as $icon => $url)
                <li><a href="{{ $url }}" target="_blank" rel="noopener" aria-label="{{ $labels[$icon] }}"><i class="bi bi-{{ $icon }}" aria-hidden="true"></i></a></li>
            @endforeach
        </ul>
    </div>
@endif
