<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class VehicleTransaction extends Eloquent
{
    protected $collection = 'vehicle_transactions';
    protected $primaryKey = '_id';

    protected $fillable = [
        'id_buyer', 'id_vehicle', 'qty', 'totalprice'
    ];
}
