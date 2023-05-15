<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class VehicleTransaction extends Eloquent
{
    use HasFactory;
    
    protected $collection = 'vehicle_transactions';
    protected $primaryKey = '_id';

    protected $fillable = [
        'id_buyer', 'id_vehicle', 'qty', 'totalprice'
    ];
}
