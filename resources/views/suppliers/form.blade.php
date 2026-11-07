<form method="POST"
    action="{{ $supplier->exists ? route('supplier.update', $supplier) : route('supplier.store') }}"
    class="card card-body">
    @csrf
    @if ($supplier->exists)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="name" class="form-label">Nama Supplier</label>
        <input id="name" name="name" type="text" maxlength="100"
            value="{{ old('name', $supplier->name) }}"
            class="form-control @error('name') is-invalid @enderror" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Telepon</label>
        <input id="phone" name="phone" type="tel" inputmode="numeric" maxlength="15"
            pattern="[0-9]{10,15}" value="{{ old('phone', $supplier->phone) }}"
            class="form-control @error('phone') is-invalid @enderror" required>
        <div class="form-text">Masukkan 10–15 digit angka.</div>
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Alamat</label>
        <textarea id="address" name="address" rows="3"
            class="form-control @error('address') is-invalid @enderror">{{ old('address', $supplier->address) }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('supplier.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
