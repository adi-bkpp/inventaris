<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // pencarian
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ]);
        $search = $validated['search'] ?? '';

        $items = Item::query()
            ->with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('name') //diurutkan berdasarkan nama
            ->paginate(10) //maksimal 10 data dalam 1 halaman
            ->withQueryString();

        return view('items.index', compact('items', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('items.create', [
            'item' => new Item,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'extensions:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('items', 'public');

            if ($photoPath === false) {
                throw new RuntimeException('Foto item gagal disimpan.');
            }

            $validated['photo'] = $photoPath;
        }

        Item::create($validated);

        return redirect()
            ->route('item.index')
            ->with('success', 'Item berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item): View
    {
        return view('items.edit', [
            'item' => $item,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'extensions:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $previousPhoto = $item->photo;

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('items', 'public');

            if ($photoPath === false) {
                throw new RuntimeException('Foto item gagal disimpan.');
            }

            $validated['photo'] = $photoPath;
        } else {
            unset($validated['photo']);
        }

        $item->update($validated);

        if ($previousPhoto !== null && $item->photo !== $previousPhoto) {
            Storage::disk('public')->delete($previousPhoto);
        }

        return redirect()
            ->route('item.index')
            ->with('success', 'Item berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item): RedirectResponse
    {
        $photo = $item->photo;
        $item->delete();

        if ($photo !== null) {
            Storage::disk('public')->delete($photo);
        }

        return redirect()
            ->route('item.index')
            ->with('success', 'Item berhasil dihapus.');
    }
}
