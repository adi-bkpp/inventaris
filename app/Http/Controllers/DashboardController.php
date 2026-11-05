<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $judul = 'Dashboard Inventaris';
        $ringkasan = [
            'kategori' => Category::count(),
            'barang' => Item::count(),
        ];

        return view('dashboard', compact('judul', 'ringkasan'));
    }
}
