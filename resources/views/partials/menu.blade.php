{{-- Ana menü: masaüstü (.mainnav) ve mobil (.mr_navmenu) için ortak işaretleme --}}
@php($mobile = $mobile ?? false)
<ul class="main-menu">
    @foreach ($menu as $item)
        @php($hasChildren = $item->children->isNotEmpty())
        <li @class(['menu-item-has-children' => $hasChildren, 'active' => $item->isCurrent()])>
            @if ($item->href() === '#')
                <a href="#" class="nav-link" @if ($hasChildren) role="button" aria-haspopup="true" aria-expanded="false" data-submenu-toggle @endif>{{ $item->title }}</a>
            @else
                <a href="{{ $item->href() }}" @if ($item->open_in_new_tab) target="_blank" rel="noopener" @endif @if ($item->isCurrent()) aria-current="page" @endif>{{ $item->title }}</a>
            @endif

            @if ($hasChildren)
                <ul class="sub-menu">
                    @foreach ($item->children as $child)
                        <li @class(['active' => $child->isCurrent()])>
                            <a href="{{ $child->href() }}" @if ($child->open_in_new_tab) target="_blank" rel="noopener" @endif @if ($child->isCurrent()) aria-current="page" @endif>{{ $child->title }}</a>
                        </li>
                    @endforeach
                </ul>
                @if ($mobile)
                    <button type="button" class="submenu_opener" aria-label="{{ $item->title }} alt menüsünü aç" aria-expanded="false"><i class="bi bi-chevron-right" aria-hidden="true"></i></button>
                @endif
            @endif
        </li>
    @endforeach
</ul>
