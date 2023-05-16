<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Services\VehicleService;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class VehicleController extends Controller
{
    protected $user;
    private $vehicleService;
 
    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $vehicles = $this->vehicleService->getVehicles();
        return response()->json([
            'data' => [
                'vehicles' => $vehicles
            ]
        ], 200);

    }

    public function store(Request $request)
    {

        
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

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request',
                'error' => $validator->messages()
            ], 400);
        }

        $vehicle = $this->vehicleService->createVehicle($request);

        if (!$vehicle) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create vehicle',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Vehicle created successfully',
            'data' => [
                'vehicle' => $vehicle,
            ],
        ], 201);
    }

    public function show($id)
    {
        $vehicle = $this->vehicleService->getVehicleById($id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, vehicle with id ' . $id . ' cannot be found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Vehicle retrieved successfully',
            'data' => [
                'vehicle' => $vehicle
            ]
        ], 200);;
        
    }

    public function update(Request $request, $id)
    {

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

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request',
                'error' => $validator->messages()
            ], 400);
        }
         
        $vehicle = $this->vehicleService->updateVehicleById($request, $id);
        return response()->json([
            'success' => true,
            'message' => 'Vehicle updated successfully',
            'data' => $vehicle
        ], 200);
    }

    public function destroy($id)
    {
        $vehicle = $this->vehicleService->deleteVehicleById($id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, vehicle with id ' . $id . ' cannot be found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully'
        ], 200);
    }

    public function showstock()
    {
        $vehicles = $this->vehicleService->getVehicleStock();

        return response()->json([
            'success' => true,
            'message' => 'Vehicles stock retrieved successfully',
            'data' => $vehicles
        ], 200);

    }
}
