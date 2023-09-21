<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roadblock extends Model
{
    use HasFactory;

    protected $table='roadblocks';
    protected $primaryKey='id';
    protected $fillable=  
    [  
    'lat',  
    'lng',
    'user',
    'grid'
    ];  
}
