@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<h1 class="h3 mb-4">{{ $judul }}</h1>
<div class="row g-3">
    <div class="col-md-6">
        <div class="card card-body">
            <p class="text-muted mb-1">Kategori</p>
            <p class="fs-2 fw-bold">{{ $ringkasan['kategori'] }}</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-body">
            <p class="text-muted mb-1">Barang</p>
            <p class="fs-2 fw-bold">{{ $ringkasan['barang'] }}</p>
        </div>
    </div>
</div>
@endsection