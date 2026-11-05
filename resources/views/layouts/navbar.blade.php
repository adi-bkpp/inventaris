
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
    <div class="container">

        <a class="navbar-brand" href="{{ route('dashboard') }}">
            Inventaris
        </a>

        <!-- Tombol garis tiga untuk mobile -->
        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu"
            aria-controls="navbarMenu"
            aria-expanded="false"
            aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu Navbar -->
        <div class="collapse navbar-collapse" id="navbarMenu">
            <div class="navbar-nav ms-auto">

                <a class="nav-link{{ request()->routeIs('category.*') ? ' active' : '' }}"
                    href="{{ route('category.index') }}">
                    Kategori
                </a>

                <a class="nav-link{{ request()->routeIs('item.*') ? ' active' : '' }}"
                    href="{{ route('item.index') }}">
                    Item
                </a>

                @auth
                    <form method="POST" action="{{ route('logout') }}" class="d-flex ms-lg-2">
                        @csrf
                        <button type="submit" class="btn btn-dark btn-sm">Logout</button>
                    </form>
                @endauth

            </div>
        </div>

    </div>
</nav>
