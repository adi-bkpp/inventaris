<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Inventaris</a>
        <div class="navbar-nav">
            <a class="nav-link{{ request()->routeIs('dashboard') ? ' active' : '' }}"
                href="{{ route('dashboard') }}">Dashboard</a>
            <a class="nav-link{{ request()->routeIs('category.*') ? ' active' : '' }}"
                href="{{ route('category.index') }}">Kategori</a>
        </div>
    </div>
</nav>