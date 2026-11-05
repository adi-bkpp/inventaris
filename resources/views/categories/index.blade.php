@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Kategori</h1>
            <p class="text-muted mb-0">Daftar kategori barang inventaris.</p>
        </div>
        <a href="{{ route('category.create') }}" class="btn btn-primary align-self-md-center">
            + Tambah Kategori
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('category.index') }}" class="row g-2">
                <div class="col">
                    <label for="search" class="visually-hidden">Cari kategori</label>
                    <input id="search" name="search" type="search" value="{{ $search }}"
                        class="form-control" placeholder="Cari nama atau deskripsi kategori...">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary">Cari</button>
                    @if ($search !== '')
                        <a href="{{ route('category.index') }}" class="btn btn-outline-secondary">Reset</a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Nama Kategori</th>
                        <th scope="col">Deskripsi</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td class="text-muted">{{ $categories->firstItem() + $loop->index }}</td>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td class="text-muted">{{ $category->description ?: '—' }}</td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('category.edit', $category) }}"
                                        class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form method="POST" action="{{ route('category.destroy', $category) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">
                                @if ($search !== '')
                                    Tidak ada kategori yang cocok dengan pencarian "{{ $search }}".
                                @else
                                    Belum ada kategori.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mt-3">
        <small class="text-muted">
            Menampilkan {{ $categories->firstItem() ?? 0 }}–{{ $categories->lastItem() ?? 0 }}
            dari {{ $categories->total() }} kategori
        </small>
        {{ $categories->links() }}
    </div>
@endsection