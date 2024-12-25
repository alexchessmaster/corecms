<!-- Menu -->
<nav id="menu">
    <header class="major">
        <h2>Menu</h2>
    </header>
    <ul>
        @foreach ($data->menus as $menu)
            <li>
                @if(empty($menu->children))
                    <a href="{{ $menu->link }}">{{ $menu->name }}</a>
                @else
                    <span class="opener">{{ $menu->name }}</span>
                    <ul>
                        @foreach ($menu->children as $child)
                            <li><a href="{{ $child->link }}">{{ $child->name }}</a></li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</nav>