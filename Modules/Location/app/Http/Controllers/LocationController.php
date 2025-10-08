<?php

namespace Modules\Location\Http\Controllers;

use App\Http\Controllers\Controller;
use Dornica\PanelKit\BladeLayout\Facade\BladeLayout;
use Illuminate\Http\Request;
use Modules\Bank\Models\Location;
use Modules\Location\Generators\Tables\LocationTable;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        BladeLayout::table(LocationTable::class);
        return view('location::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('location::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('location::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Location $location)
    {
        return view('location::edit',compact($location));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
