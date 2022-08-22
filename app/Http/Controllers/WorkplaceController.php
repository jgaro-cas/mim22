<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkplaceRequest;
use App\Http\Requests\UpdateWorkplaceRequest;
use App\Models\Workplace;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class WorkplaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workplaces = Workplace::with(['workblocks' => function($query){
            $query->withCount('volunteers');
        }])->get();

        return Inertia::render('Workplaces',[
            'workplaces' => $workplaces
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreWorkplaceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkplaceRequest $request)
    {
        $workplace = Workplace::create([
            'name' => $request->name
        ]);

        return Redirect::route('admin.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Workplace  $workplace
     * @return \Illuminate\Http\Response
     */
    public function show(Workplace $workplace)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Workplace  $workplace
     * @return \Illuminate\Http\Response
     */
    public function edit(Workplace $workplace)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateWorkplaceRequest  $request
     * @param  \App\Models\Workplace  $workplace
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWorkplaceRequest $request, Workplace $workplace)
    {
        $workplace->name = $request->name;
        $workplace->save();
        return Redirect::route('admin.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Workplace  $workplace
     * @return \Illuminate\Http\Response
     */
    public function destroy(Workplace $workplace)
    {
        $workplace->delete();

        return Redirect::route('admin.index');
    }

    public function fullIndexJson(){
        $workplaces = Workplace::with(['workblocks' => function($query){
            $query->withCount('volunteers');
        }])->get();

        return $workplaces->toJson();
    }
}
