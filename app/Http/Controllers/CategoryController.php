<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Elektronik',
                'description' => 'Perangkat elektronik dan aksesorinya',
            ],
            [
                'id' => 2,
                'name' => 'Furniture',
                'description' => 'Meja, kursi, lemari, dan perlengkapan ruangan',
            ],
            [
                'id' => 3,
                'name' => 'Alat Tulis',
                'description' => 'Perlengkapan tulis dan kebutuhan administrasi',
            ],
            [
                'id' => 4,
                'name' => 'Peralatan Kebersihan',
                'description' => 'Peralatan untuk menjaga kebersihan lingkungan',
            ],
        ];

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
