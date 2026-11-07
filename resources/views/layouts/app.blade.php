<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Inventaris')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            padding-bottom: 4rem !important;
        }
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    @include('layouts.navbar')
    <main class="container flex-grow-1 py-4 py-md-5">
        @yield('content')
    </main>

    <footer class="border-top bg-white mt-auto fixed-bottom shadow-sm">
        <div class="container py-3 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-1">
            <span class="text-muted small">&copy; {{ now()->year }} Inventaris</span>
            <span class="text-muted small">Sistem Informasi Inventaris</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>