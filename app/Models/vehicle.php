<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class Vehicle extends Eloquent
{
    protected $collection = 'vehicles';
    protected $primaryKey = '_id';

    use HasFactory;
    protected $fillable = [
        'name', 'year', 'color', 'price', 'flagtype', 'posted_by'
    ];
}
