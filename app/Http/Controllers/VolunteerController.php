<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVolunteerRequest;
use App\Http\Requests\UpdateVolunteerRequest;
use App\Mail\VolunteerRegistered;
use App\Models\Volunteer;
use App\Models\Workplace;
use Carbon\Carbon;
use Illuminate\Support\Carbon as SupportCarbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
        /* Import Excel template */
        $inputFileName = storage_path('app/excel/Base.xlsx');
        $spreadsheet = IOFactory::load($inputFileName);

        /* Import all registrations */
        $workplaces = Workplace::with('workblocks.volunteers')->get();


        foreach ($workplaces as $workplace) {
            /* Create worksheet */
            $clonedWorksheet = clone $spreadsheet->getSheetByName('Workplace');
            $clonedWorksheet->setTitle(substr($workplace->name,0,30));
            $spreadsheet->addSheet($clonedWorksheet);
            $spreadsheet->getSheetByName($workplace->name)->setCellValue('A1', $workplace->name);
            $sheetContent = array();
            foreach ($workplace->workblocks as $block) {
                foreach ($block->volunteers as $volunteer) {
                    $lineContent = [
                        $volunteer->last_name,
                        $volunteer->first_name,
                        "=" . '"' . $volunteer->phone . '"',
                        $volunteer->mail,
                        Carbon::create($block->block_start)->toDateString(),
                        Carbon::create($block->block_start)->toTimeString(),
                        Carbon::create($block->block_stop)->toTimeString(),
                    ];
                    array_push($sheetContent, $lineContent);

                }
            }
            $spreadsheet->getSheetByName($workplace->name)->fromArray(
                $sheetContent,  // The data to set
                NULL,        // Array values with this value will not be set
                'A4'         // Top left coordinate of the worksheet range where
            );
        }

        /* Suppress Workplace template */
        $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Workplace'));
        $spreadsheet->removeSheetByIndex($sheetIndex);

        /* Add volunteers list */
        $volunteers = DB::table('volunteers')->orderBy('last_name')->get();
        $volunteerRowIndex = 2;
        $spreadsheet->getSheetByName('Liste de bénévoles');
        foreach ($volunteers as $volunteer) {
            $spreadsheet->getSheetByName('Liste de bénévoles')->fromArray(
                [$volunteer->last_name, $volunteer->first_name, "=" . '"' . $volunteer->phone . '"', $volunteer->mail],   // The data to set
                NULL,                                                                                   // Array values with this value will not be set
                "A{$volunteerRowIndex}"                                                                 // Top left coordinate of the worksheet range where
            );
            $volunteerRowIndex ++;
        }

        /* Save file. Not goot to store that in public path but actually no better solution*/
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save(storage_path('app/public/Liste_Benevoles.xlsx'));
        return (asset('storage/Liste_Benevoles.xlsx'));

    }
}
