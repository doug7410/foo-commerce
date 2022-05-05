<a href="{{ route($routeName) }}" class="nav-link {{ request()->routeIs($routeName) ? 'active' : 'text-white' }}">
    {{ $slot }}
</a>