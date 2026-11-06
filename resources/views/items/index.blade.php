@extends('layouts.app')

@section('title', 'Daftar Item')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Daftar Item</h1>
            <p class="text-muted mb-0">Daftar barang inventaris beserta kategori dan stoknya.</p>
        </div>
        <a href="{{ route('item.create') }}" class="btn btn-primary align-self-md-center">
            + Tambah Item
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('item.index') }}" class="row g-2">
                <div class="col">
                    <label for="search" class="visually-hidden">Cari item</label>
                    <input id="search" name="search" type="search" value="{{ $search }}"
                        class="form-control" placeholder="Cari nama item atau kategori...">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-primary">Cari</button>
                    @if ($search !== '')
                        <a href="{{ route('item.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                        <th scope="col">Foto</th>
                        <th scope="col">Nama Item</th>
                        <th scope="col">Kategori</th>
                        <th scope="col" class="text-end">Stok</th>
                        <th scope="col" class="text-end">Harga</th>
                        <th scope="col" class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td class="text-muted">{{ $items->firstItem() + $loop->index }}</td>
                            <td>
                                @if ($item->photo)
                                    <img src="{{ Storage::disk('public')->url($item->photo) }}"
                                        alt="Foto {{ $item->name }}" class="img-thumbnail"
                                        style="width: 56px; height: 56px; object-fit: cover;">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $item->name }}</td>
                            <td><span class="badge text-bg-light border">{{ $item->category->name }}</span></td>
                            <td class="text-end">
                                <span @class([
                                    'badge text-bg-success' => $item->stock >= 5,
                                    'badge text-dark' => $item->stock < 5,
                                ]) @if ($item->stock <= 5) style="background-color: #fd7e14" @endif>
                                    {{ number_format($item->stock, 0, ',', '.') }}
                                </span>
                            </td>
                            <td class="text-end fw-semibold">
                                Rp{{ number_format((float) $item->price, 2, ',', '.') }}
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('item.edit', $item) }}"
                                        class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form method="POST" action="{{ route('item.destroy', $item) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus item ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                @if ($search !== '')
                                    Tidak ada item yang cocok dengan pencarian "{{ $search }}".
                                @else
                                    Belum ada item.
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
            Menampilkan {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }}
            dari {{ $items->total() }} item
        </small>
        {{ $items->links() }}
    </div>
@endsection
