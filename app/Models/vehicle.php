<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
class Vehicle extends Eloquent
{

    protected $collection = 'vehicles';
    protected $primaryKey = '_id';

    protected $fillable = [
        'name', 'year', 'color', 'price', 'stock_qty', 'flagtype', 'posted_by'
    ];

    public function subtype()
    {
        $type = $this->getAttribute('flagtype');
        switch ($type) {
            case 'Motor':
                return $this->hasOne(Motorcycle::class, 'vehicle_id');
            case 'Mobil':
                return $this->hasOne(Car::class, 'vehicle_id');
            default:
                return null;
        }
    }
}
