<?php

namespace App\Models;

use App\CustomServices\DateFormater;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workblock extends Model
{
    use HasFactory;

    protected $fillable = [
        'block_start',
        'block_stop',
        'workplace_id',
        'volunteer_number',
    ];

    // protected $visible = [
    //     'id',
    //     'block_start',
    //     'block_stop',
    //     'workplace_id',
    //     'volunteer_number',
    //     'volunteers_count',
    //     'readable_start',
    //     'readable_stop',
    //     'workplaces',
    // ];

    protected $appends = ['readable_start', 'readable_stop'];

    protected function getReadableStartAttribute(){
        $date = new DateFormater();
        return $date->toFrenchReadable(new Carbon($this->block_start));
    }


    protected function getReadableStopAttribute(){
        $date = new DateFormater();
        return $date->toFrenchReadable(new Carbon($this->block_stop));
    }

    public function workplaces() : BelongsTo {
        return $this->belongsTo(Workplace::class, 'workplace_id');
    }

    public function volunteers() : BelongsToMany {
        return $this->belongsToMany(Volunteer::class);
    }

}
