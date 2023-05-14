<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class VehicleTransaction extends Eloquent
{
    protected $collection = 'vehicle_transactions';
    protected $primaryKey = '_id';

    use HasFactory;
    protected $fillable = [
        'id_buyer', 'id_vehicle', 'qty', 'totalprice'
    ];
}
