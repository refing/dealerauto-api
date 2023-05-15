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
        // $vehicles = Vehicle::get()->toArray();
        $vehicles = $this->vehicleService->getVehicles();
        return response()->json([
            'data' => [
                'vehicles' => $vehicles
            ]
        ], 200);

    }

    public function store(Request $request)
    {

        $vehicle = $this->vehicleService->createVehicle($request);

        if (!$vehicle) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create vehicle',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Vehicle created successfully',
            'data' => [
                'vehicle' => $vehicle,
            ],
        ], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        // $vehicle = Vehicle::find($id);
        $vehicle = $this->vehicleService->getVehicleById($id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, vehicle with id ' . $id . ' cannot be found'
            ], 400);
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
         
        $vehicle = $this->vehicleService->updateVehicleById($request, $id);
        //Vehicle updated, return success response
        return response()->json([
            'success' => true,
            'message' => 'Vehicle updated successfully',
            'data' => $vehicle
        ], 200);
    }

    public function destroy($id)
    {
        // $vehicle = Vehicle::find($id);
        $vehicle = $this->vehicleService->deleteVehicleById($id);
        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, vehicle with id ' . $id . ' cannot be found'
            ], 400);
        }
        
        // $vehicle->delete();
        
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
