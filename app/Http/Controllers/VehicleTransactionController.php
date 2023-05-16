<?php

namespace App\Http\Controllers;

use App\Models\VehicleTransaction;
use App\Services\VehicleTransactionService;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class VehicleTransactionController extends Controller
{
    protected $user;
    private $vehicleTransactionService;
 
    public function __construct(VehicleTransactionService $vehicleTransactionService)
    {
        $this->vehicleTransactionService = $vehicleTransactionService;
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index()
    {
        $vehicleTransactions = $this->vehicleTransactionService->getVehicleTransactions();
        return response()->json([
            'success' => true,
            'message' => 'Vehicle transactions retrieved successfully',
            'data' => [
                'transactions' => $vehicleTransactions
            ]
        ], 200);

    }

    public function store(Request $request, $idvehicle)
    {
        $data = $request->only('qty');
        $validator = Validator::make($data, [
            'qty' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request',
                'error' => $validator->messages()
            ], 400);
        }

        $vehicleTransaction = $this->vehicleTransactionService->createVehicleTransaction($request, $idvehicle, $this->user->_id);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle transaction created successfully',
            'data' => $vehicleTransaction
        ], 201);
    }

    public function show($idtransaction)
    {
        $vehicleTransaction = $this->vehicleTransactionService->getVehicleTransactionById($idtransaction);
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
        
        $vehicleTransaction = $this->vehicleTransactionService->getVehicleTransactionReport();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle sales report retrieved successfully',
            'data' => [
                'vehiclesales' => $vehicleTransaction
            ]
        ], 200);

    }

}
