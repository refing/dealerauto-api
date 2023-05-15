<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class Vehicle extends Eloquent
{
    protected $collection = 'vehicles';
    protected $primaryKey = '_id';

    protected $fillable = [
        'name', 'year', 'color', 'price', 'stock_qty', 'flagtype', 
        'motorcycle_machine', 'suspension_type', 'transmission_type',
        'car_machine', 'capacity', 'car_type'
    ];
}
