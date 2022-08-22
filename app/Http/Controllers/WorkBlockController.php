<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkblockRequest;
use App\Http\Requests\UpdateWorkblockRequest;
use App\Models\Workblock;
use App\Models\Workplace;
use DateTime;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class WorkblockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $workbloks = Workblock::with(['volunteers' => function ($query) {
        //     $query->count();
        // }]);

        $workblocks = Workblock::withCount('volunteers')->get();
//        dd($workblocks);
        //$workblocks = Workblock::all();
        $workplaces = Workplace::all();

        return Inertia::render('Workblocks', [
            'workblocks' => $workblocks,
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
     * @param  \App\Http\Requests\StoreWorkblockRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWorkblockRequest $request)
    {
        $workblock = Workblock::create([
            'block_start' => $request->block_start,
            'block_stop' => $request->block_stop,
            'workplace_id' => $request->workplace_id,
            'volunteer_number' => $request->volunteer_number
        ]);

        return Redirect::route('admin.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkBlock  $workblock
     * @return \Illuminate\Http\Response
     */
    public function show(Workblock $workblock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkBlock  $workblock
     * @return \Illuminate\Http\Response
     */
    public function edit(Workblock $workblock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateWorkblockRequest  $request
     * @param  \App\Models\Workblock  $workblock
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateWorkblockRequest $request, Workblock $workblock)
    {
        $workblock->update([
            'block_start' => new DateTime($request->block_start),
            'block_stop' => new DateTime($request->block_stop),
            'workplace_id' => $request->workplace_id,
            'volunteer_number' => $request->volunteer_number,
        ]);

        $newWB = Workblock::find($workblock->id);
        $workplaces = Workplace::all();

        // return Inertia::render('WorkblockDetail',[
        //     'workblock' => $workblock,
        //     'workplaces' => $workplaces,
        // ]);
        //return Redirect::route('workblocks.index');
        return response($newWB, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Workblock  $workblock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Workblock $workblock)
    {
        $workblock->delete();

        return Redirect::route('admin.index');

    }
}
