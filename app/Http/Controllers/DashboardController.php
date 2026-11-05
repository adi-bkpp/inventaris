<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $judul = 'Dashboard Inventaris';
        $ringkasan = ['kategori' => 4, 'barang' => 28];
    
        return view('dashboard', compact('judul', 'ringkasan'));
    }
}
