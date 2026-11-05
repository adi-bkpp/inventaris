<form method="POST"
    action="{{ $category->exists ? route('category.update', $category) : route('category.store') }}"
    class="card card-body">
    @csrf
    @if ($category->exists)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="name" class="form-label">Nama Kategori</label>
        <input id="name" name="name" type="text" maxlength="100"
            value="{{ old('name', $category->name) }}"
            class="form-control @error('name') is-invalid @enderror" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea id="description" name="description" rows="3"
            class="form-control @error('description') is-invalid @enderror">{{ old('description', $category->description) }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('category.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
