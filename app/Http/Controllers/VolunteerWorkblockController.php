<?php

namespace App\Http\Controllers;

use App\Models\VolunteerWorkblock;
use GuzzleHttp\Psr7\Request;

class VolunteerWorkblockController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Volunteer  $volunteer
     * @return \Illuminate\Http\Response
     */
    public function destroy($wbId, $volId)
    {
        $volunteerWorkblock = VolunteerWorkblock::where('workblock_id', $wbId)->where('volunteer_id', $volId)->first();
        $volunteerWorkblock->delete();
        return Redirect('/admin/volunteers');
    }
}
