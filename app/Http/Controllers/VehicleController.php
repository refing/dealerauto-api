<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
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
        $vehicles = Vehicle::get()->toArray();
        return response()->json([
            'data' => $vehicles
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
    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'year', 'color', 'price', 'flagtype');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'year' => 'required',
            'color' => 'required',
            'price' => 'required',
            'flagtype' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $vehicle           = new Vehicle();
        $vehicle->name     = $request->input('name');
        $vehicle->year     = $request->input('year');
        $vehicle->color    = $request->input('color');
        $vehicle->price    = $request->input('price');
        $vehicle->flagtype = $request->input('flagtype');
        $vehicle->posted_by = $this->user->_id;
        $vehicle->save();

        // return $vehicle;

        //Vehicles created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Vehicles created successfully',
            'data' => $vehicle
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
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, vehicle with id ' . $id . ' cannot be found'
            ], 400);
        }

        return $vehicle;
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(Vehicle $vehicle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         //Validate data
         $data = $request->only('name', 'year', 'color', 'price', 'flagtype');
         $validator = Validator::make($data, [
            'name' => 'required|string',
            'year' => 'required',
            'color' => 'required',
            'price' => 'required',
            'flagtype' => 'required',
         ]);
 
         //Send failed response if request is not valid
         if ($validator->fails()) {
             return response()->json(['error' => $validator->messages()], 200);
         }
         $vehicle = Vehicle::find($id);
         //Request is valid, update vehicle
         $vehicle = $vehicle->update([
            'name' => $request->name,
            'year' => $request->year,
            'color' => $request->color,
            'price' => $request->price,
            'flagtype' => $request->flagtype
         ]);
 
         //Vehicle updated, return success response
         return response()->json([
             'success' => true,
             'message' => 'Vehicle updated successfully',
             'data' => $vehicle
         ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete vehicle by id for mongodb rest api
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
}
