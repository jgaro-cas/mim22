<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class VolunteerWorkblock extends Pivot
{
    use HasFactory;

    protected $table = 'volunteer_workblock';
}
