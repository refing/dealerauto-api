<?php

namespace App\Http\Controllers;

use App\Models\VehicleTransaction;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class VehicleTransactionController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return all vehicles for rest api
        $vehicletransactions = VehicleTransaction::get()->toArray();
        return response()->json([
            'data' => $vehicletransactions
        ], Response::HTTP_OK);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $idvehicle)
    {
        //Validate data
        $data = $request->only('qty');
        $validator = Validator::make($data, [
            'qty' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $vehicle = Vehicle::find($idvehicle);

        $vehicleTransaction           = new VehicleTransaction();
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
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get vehicle by id
        $vehicleTransaction = VehicleTransaction::find($id);
        if (!$vehicleTransaction) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, transaction with id ' . $id . ' cannot be found'
            ], 400);
        }

        return $vehicleTransaction;
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VehicleTransaction  $vehicleTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(VehicleTransaction $vehicleTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VehicleTransaction  $vehicleTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VehicleTransaction $vehicleTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VehicleTransaction  $vehicleTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete vehicle by id for mongodb rest api
        $vehicleTransaction = VehicleTransaction::find($id);
        if (!$vehicleTransaction) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, vehicle transaction with id ' . $id . ' cannot be found'
            ], 400);
        }
        
        $vehicleTransaction->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Vehicle transaction deleted successfully'
        ], Response::HTTP_OK);
    }
}
