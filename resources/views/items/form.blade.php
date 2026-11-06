<form method="POST"
    action="{{ $item->exists ? route('item.update', $item) : route('item.store') }}"
    class="card card-body">
    @php
        $priceValue = old('price', $item->price);
        if ($item->exists && is_numeric($priceValue)) {
            $priceValue = rtrim(rtrim(number_format((float) $priceValue, 2, '.', ''), '0'), '.');
        }
    @endphp

    @csrf
    @if ($item->exists)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="name" class="form-label">Nama Item</label>
        <input id="name" name="name" type="text" maxlength="100"
            value="{{ old('name', $item->name) }}"
            class="form-control @error('name') is-invalid @enderror" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="category_id" class="form-label">Kategori</label>
        <select id="category_id" name="category_id"
            class="form-select @error('category_id') is-invalid @enderror" required>
            <option value="">Pilih kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(old('category_id', $item->category_id) == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="stock" class="form-label">Stok</label>
        <input id="stock" name="stock" type="number" min="0" step="1"
            value="{{ old('stock', $item->stock ?? 0) }}"
            class="form-control @error('stock') is-invalid @enderror" required>
        @error('stock')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
    <label for="price" class="form-label">Harga (Rp)</label>
    <input id="price" name="price" type="number" min="0" max="9999999999.99"
        step="0.01"
        value="{{ $priceValue }}"
        class="form-control @error('price') is-invalid @enderror" required>
    @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('item.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
