<?php
namespace App\Repositories;
use App\Models\Vehicle;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
class VehicleRepository
{
    protected $model;

    public function __construct(Vehicle $model)
    {
        $this->model = $model;
    }

    public function createVehicle(Request $request)
    {
        $vehicle = new Vehicle();
        $vehicle->_id = "veh-" . Str::uuid();
        $vehicle->name = $request->input('name');
        $vehicle->year = $request->input('year');
        $vehicle->color = $request->input('color');
        $vehicle->price = $request->input('price');
        $vehicle->stock_qty = $request->input('stock_qty');
        $vehicle->flagtype = $request->input('flagtype');

        if ($request->input('flagtype') === 'Motor') {
            $vehicle->motorcycle_machine = $request->input('motorcycle_machine');
            $vehicle->suspension_type = $request->input('suspension_type');
            $vehicle->transmission_type = $request->input('transmission_type');
        } elseif ($request->input('flagtype') === 'Mobil') {
            $vehicle->car_machine = $request->input('car_machine');
            $vehicle->capacity = $request->input('capacity');
            $vehicle->car_type = $request->input('car_type');
        }
        $vehicle->save();
        return $vehicle;
    }

    public function getVehicles()
    {
        return Vehicle::get()->toArray();
    }

    public function getVehicleById($id)
    {
        return Vehicle::find($id);
    }

    public function updateVehicleById(Request $request, $id)
    {
        $vehicle = Vehicle::find($id);

        if ($request->input('name')) {
            $vehicle->name = $request->input('name');
        }
        if ($request->input('year')) {
            $vehicle->year = $request->input('year');
        }
        if ($request->input('color')) {
            $vehicle->color = $request->input('color');
        }
        if ($request->input('price')) {
            $vehicle->price = $request->input('price');
        }
        if ($request->input('stock_qty')) {
            $vehicle->stock_qty = $request->input('stock_qty');
        }
        if ($request->input('motorcycle_machine')) {
            $vehicle->motorcycle_machine = $request->input('motorcycle_machine');
        }
        if ($request->input('suspension_type')) {
            $vehicle->suspension_type = $request->input('suspension_type');
        }
        if ($request->input('transmission_type')) {
            $vehicle->transmission_type = $request->input('transmission_type');
        }
        if ($request->input('car_machine')) {
            $vehicle->car_machine = $request->input('car_machine');
        }
        if ($request->input('capacity')) {
            $vehicle->capacity = $request->input('capacity');
        }
        if ($request->input('car_type')) {
            $vehicle->car_type = $request->input('car_type');
        }
        $vehicle->save();
        return $vehicle;
    }

    public function deleteVehicleById($id)
    {
        $vehicle = Vehicle::find($id);
        $vehicle->delete();
        return $vehicle;
    }

    public function getVehicleStock()
    {
        $vehicles = Vehicle::raw(function($collection)
        {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => '$flagtype',
                        'type' => ['$first' => '$flagtype'],
                        'total' => ['$sum' => '$stock_qty'],
                        'vehicles' => [
                            '$push' => [
                                'name' => '$name',
                                'stock_qty' => '$stock_qty',
                            ],
                        ],
                    ],
                ],
                [
                    '$group' => [
                        '_id' => null,
                        'types' => [
                            '$push' => [
                                'type' => '$type',
                                'total' => '$total',
                                'vehicles' => '$vehicles',
                            ],
                        ],
                        'total' => [
                            '$sum' => '$total',
                        ],
                    ],
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'types' => 1,
                        'total' => 1,
                    ],
                ],
            ]);
        });
        return $vehicles;
    }
}