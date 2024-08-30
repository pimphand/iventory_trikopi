<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('master.kategori');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $category =  QueryBuilder::for(Category::class)
            ->allowedFilters([
                AllowedFilter::exact('name'),
            ])
            ->paginate(10);

        return CategoryResource::collection($category);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        Category::updateOrCreate(['id' => $request->id], [
            'name' => $request->name
        ]);

        return [
            'message' => 'Berhasil menambahkan data'
        ];
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
        Category::destroy($id);

        return [
            'message' => 'Data berhasil dihapus'
        ];
    }
}
