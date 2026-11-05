@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Kategori</h1>
            <p class="text-muted mb-0">Daftar kategori barang inventaris.</p>
        </div>
    </div>

    <div class="btn-group mb-3" role="group">
        <button type="button" class="btn btn-primary">Tambah Kategori</button>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Nama Kategori</th>
                        <th scope="col">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category['id'] }}</td>
                            <td>{{ $category['name'] }}</td>
                            <td>{{ $category['description'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">
                                Belum ada kategori.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection