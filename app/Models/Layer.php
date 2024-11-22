<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layer extends Model
{
    protected $fillable = ['name', 'geometry'];

    // protected $casts = [
    //     'geometry' => 'array', // GeoJSON será armazenado como array
    // ];
}
