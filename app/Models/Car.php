<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class Car extends Eloquent
{
    
    protected $collection = 'vehicles';

    protected $fillable = ['car_machine', 'capacity', 'car_type', 'vehicle_id'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
