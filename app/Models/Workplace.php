<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workplace extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // protected $visible = [
    //     'id',
    //     'name',
    //     'workblocks',
    // ];



    public function workblocks() : HasMany{
        return $this->hasMany(Workblock::class)->orderBy('block_start');
    }
}
