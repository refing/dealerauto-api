<?php

namespace App\Http\Controllers;

use App\Models\VehicleTransaction;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VehicleTransactionController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        //return all vehicles for rest api
        $vehicletransactions = VehicleTransaction::get()->toArray();
        return response()->json([
            'success' => true,
            'message' => 'Vehicle transactions retrieved successfully',
            'data' => [
                'transactions' => $vehicletransactions
            ]
        ], 200);

    }

    public function store(Request $request, $idvehicle)
    {
        //Validate data
        $data = $request->only('qty');
        $validator = Validator::make($data, [
            'qty' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request',
                'error' => $validator->messages()
            ], 200);
        }

        $vehicle = Vehicle::find($idvehicle);

        $vehicleTransaction           = new VehicleTransaction();
        $vehicleTransaction->_id     = "tsc-" . Str::uuid();
        $vehicleTransaction->qty     = $request->input('qty');
        $vehicleTransaction->totalprice     = $vehicle->price * $request->input('qty');
        $vehicleTransaction->id_vehicle    = $vehicle->_id;
        $vehicleTransaction->id_buyer = $this->user->_id;
        $vehicleTransaction->save();

        //decrease the quantity of the vehicle
        $vehicle->stock_qty = $vehicle->stock_qty - $request->input('qty');
        $vehicle->save();

        //Vehicles created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Vehicle transaction created successfully',
            'data' => $vehicleTransaction
        ], 200);
    }

    public function show($idtransaction)
    {
        //get vehicle by id
        $vehicleTransaction = VehicleTransaction::find($idtransaction);
        if (!$vehicleTransaction) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, transaction with id ' . $idtransaction . ' cannot be found',
                'error' => 'Data not found'
            ], 400);
        }
        return response()->json([
            'success' => true,
            'message' => 'Vehicle transaction retrieved successfully',
            'data' => [
                'transaction' => $vehicleTransaction
            ]
        ], 200);
        
    }

    public function showreport()
    {
        $vehicleTransaction = VehicleTransaction::raw(function($collection)
        {
            return $collection->aggregate([
                [
                    '$lookup' => [
                        'from' => 'vehicles',
                        'localField' => 'id_vehicle',
                        'foreignField' => '_id',
                        'as' => 'vehicle'
                    ]
                ],
                [
                    '$unwind' => '$vehicle'
                ],
                [
                    '$group' => [
                        '_id' => '$vehicle.name',
                        'totalprice' => [
                            '$sum' => '$totalprice'
                        ]
                    ]
                ],
                [
                    '$project' => [
                        'name' => '$_id',
                        'totalsales' => '$totalprice',
                        '_id' => 0
                    ]
                ]
            ]);
        });
        

        return response()->json([
            'success' => true,
            'message' => 'Vehicle sales report retrieved successfully',
            'data' => [
                'vehiclesales' => $vehicleTransaction
            ]
        ], 200);

    }

}
