<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pothole extends Model
{
    use HasFactory;

    protected $table='potholes';
    protected $primaryKey='id';
    protected $fillable=  
    [  
    'lat',  
    'lng',
    'reports',
    'remove reports',
    'user',
    'grid'
    ];  
}
