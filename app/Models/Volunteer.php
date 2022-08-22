<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Volunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'mail',
        'phone',
        'shirt_type',
        'size',
    ];

    protected $visible = [
        'id',
        'first_name',
        'last_name',
        'mail',
        'phone',
        'shirt_type',
        'size',
        'workblocks',
    ];

    public function workblocks() : BelongsToMany {
        return $this->belongsToMany(Workblock::class);
    }


}
