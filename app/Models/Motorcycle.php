<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class Motorcycle extends Eloquent
{
    
    protected $collection = 'vehicles';

    protected $fillable = ['motorcycle_machine', 'suspension_type', 'transmission_type', 'vehicle_id'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }
}
