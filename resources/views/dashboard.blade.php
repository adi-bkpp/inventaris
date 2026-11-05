@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <section class="rounded-4 bg-dark text-white p-4 p-md-5 mb-4 shadow-sm">
        <div class="row align-items-center g-4">
            <div class="col-lg">
                <span class="badge rounded-pill text-bg-light text-dark mb-3">RINGKASAN INVENTARIS</span>
                <h1 class="display-6 fw-bold mb-2">{{ $judul }}</h1>
                <p class="lead mb-0 text-white-50">
                    Pantau data kategori dan barang inventaris Anda dalam satu tempat.
                </p>
            </div>
            <div class="col-lg-auto">
                <a href="{{ route('item.index') }}" class="btn btn-light btn-lg text-dark fw-semibold">
                    Lihat daftar item
                </a>
            </div>
        </div>
    </section>

    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-end gap-2 mb-3">
        <div>
            <h2 class="h5 fw-bold mb-1">Ringkasan data</h2>
            <p class="text-muted mb-0">Jumlah data yang tersimpan saat ini.</p>
        </div>
    </div>

    <div class="row g-3 g-lg-4">
        <div class="col-md-6">
            <a href="{{ route('category.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 border-start border-4 border-dark shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div>
                            <p class="text-muted fw-semibold mb-2">Total Kategori</p>
                            <p class="display-5 fw-bold text-dark mb-2">{{ $ringkasan['kategori'] }}</p>
                            <span class="text-dark fw-semibold">Kelola kategori <span aria-hidden="true">&rarr;</span></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6">
            <a href="{{ route('item.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 border-start border-4 border-dark shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div>
                            <p class="text-muted fw-semibold mb-2">Total Item</p>
                            <p class="display-5 fw-bold text-dark mb-2">{{ $ringkasan['barang'] }}</p>
                            <span class="text-dark fw-semibold">Kelola item <span aria-hidden="true">&rarr;</span></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endsection