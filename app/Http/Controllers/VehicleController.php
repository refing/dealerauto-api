<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\Motorcycle;
use App\Models\Car;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VehicleController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        //return all vehicles for rest api
        $vehicles = Vehicle::get()->toArray();
        return response()->json([
            'data' => $vehicles
        ], Response::HTTP_OK);

    }

    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'year', 'color', 'price', 'stock_qty', 'flagtype',
        'motorcycle_machine', 'suspension_type', 'transmission_type',
        'car_machine', 'capacity', 'car_type');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'year' => 'required|integer',
            'color' => 'required|string',
            'price' => 'required|numeric',
            'stock_qty' => 'required|integer',
            'flagtype' => 'required|in:Motor,Mobil',
            'motorcycle_machine' => 'required_if:flagtype,motor|string',
            'suspension_type' => 'required_if:flagtype,motor|string',
            'transmission_type' => 'required_if:flagtype,motor|string',
            'car_machine' => 'required_if:flagtype,mobil|string',
            'capacity' => 'required_if:flagtype,mobil|integer',
            'car_type' => 'required_if:flagtype,mobil|string',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $vehicle           = new Vehicle();
        $vehicle->_id     = "veh-" . Str::uuid();
        $vehicle->name     = $request->input('name');
        $vehicle->year     = $request->input('year');
        $vehicle->color    = $request->input('color');
        $vehicle->price    = $request->input('price');
        $vehicle->stock_qty    = $request->input('stock_qty');
        $vehicle->flagtype = $request->input('flagtype');
        $vehicle->posted_by = $this->user->_id;

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

        //Vehicles created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Vehicles created successfully',
            'data' => $vehicle
        ], Response::HTTP_OK);
    }

    public function show($id)
    {
        //get vehicle by id
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, vehicle with id ' . $id . ' cannot be found'
            ], 400);
        }

        return $vehicle;
        
    }

    public function update(Request $request, $id)
    {
         //Validate data
         $data = $request->only('name', 'year', 'color', 'price', 'stock_qty',
         'motorcycle_machine', 'suspension_type', 'transmission_type',
         'car_machine', 'capacity', 'car_type');
         $validator = Validator::make($data, [
            'name' => 'string',
            'year' => 'integer',
            'color' => 'string',
            'price' => 'numeric',
            'stock_qty' => 'integer',
            'motorcycle_machine' => 'string',
            'suspension_type' => 'string',
            'transmission_type' => 'string',
            'car_machine' => 'string',
            'capacity' => 'integer',
            'car_type' => 'string',
         ]);
 
         //Send failed response if request is not valid
         if ($validator->fails()) {
             return response()->json(['error' => $validator->messages()], 200);
         }
         $vehicle = Vehicle::find($id);

         //update vehicle only for available field inputted
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

         //Vehicle updated, return success response
         return response()->json([
             'success' => true,
             'message' => 'Vehicle updated successfully',
             'data' => $vehicle
         ], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, vehicle with id ' . $id . ' cannot be found'
            ], 400);
        }
        
        $vehicle->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully'
        ], Response::HTTP_OK);
    }

    public function showstock()
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

        return response()->json([
            'data' => $vehicles
        ], Response::HTTP_OK);

    }
}
