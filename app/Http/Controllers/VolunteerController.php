<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVolunteerRequest;
use App\Http\Requests\UpdateVolunteerRequest;
use App\Mail\VolunteerRegistered;
use App\Models\Volunteer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Spatie\SimpleExcel\SimpleExcelWriter;


class VolunteerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Volunteers', [
            'volunteers' => Volunteer::with('workblocks.workplaces')->get()
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
     * @param  \Illuminate\Http\StoreVolunteerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVolunteerRequest $request)
    {
        $volunteer = Volunteer::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'mail' => $request->email,
            'phone' => $request->phone,
        ]);

        foreach ($request->selection as $id){
            $volunteer->workblocks()->attach($id);
        }

        $maildata = Volunteer::with('workblocks.workplaces')->where('id', $volunteer->id)->get();

        Mail::to($request->email)->send(new VolunteerRegistered($maildata));

        return Redirect::route('thankyou', ['mail' => $request->email]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function show(Volunteer $volunteer)
    {
        return $volunteer->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function edit(Volunteer $volunteer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateVolunteerRequest  $request
     * @param  \App\Models\Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVolunteerRequest $request, Volunteer $volunteer)
    {
        $volunteer->update([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'mail' => $request->email,
            'phone' => $request->phone,
        ]);

        $volunteer->workblocks()->detach();

        foreach ($request->selection as $id){
            $volunteer->workblocks()->attach($id);
        }

        $volunteers = Volunteer::with('workblocks.workplaces')->get();
        //return response($volunteers, 200);
        return Redirect('/admin/volunteers');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Volunteer $volunteer)
    {
        $volunteer->delete();
        return Redirect('/admin/volunteers');
    }

    public function getExcel(){
        $volunteers = Volunteer::with('workblocks.workplaces')->get();
        $exportDatas = [];

        foreach ($volunteers as $volunteer) {
            foreach ($volunteer->workblocks as $workblock) {
                $datas = [
                    'Id' => $volunteer->id,
                    'Prénom' => $volunteer->first_name,
                    'Nom' => $volunteer->last_name,
                    'Mail' => $volunteer->mail,
                    'Téléphone' => $volunteer->phone,
                    'Place' => $workblock->workplaces->name,
                    'Start' => $workblock->readable_start,
                    'Stop' => $workblock->readable_stop,
                    'Créé le' => $volunteer->created_at,
                ];
                array_push($exportDatas, $datas);
            }

        }
        // return $exportDatas;

        $writer = SimpleExcelWriter::streamDownload('ExportBenevoles.csv');


        $writer->addRows($exportDatas);

        $writer->toBrowser();
        // return $volunteers->toArray();
    }
}
