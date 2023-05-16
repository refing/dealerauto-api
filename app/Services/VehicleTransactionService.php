<?php
namespace App\Services;
use App\Repositories\VehicleTransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleTransactionService
{
    private $vehicleTransactionRepository;

    public function __construct(VehicleTransactionRepository $vehicleTransactionRepository)
    {
        $this->vehicleTransactionRepository = $vehicleTransactionRepository;
    }

    public function createVehicleTransaction(Request $request, $idvehicle, $iduser)
    {
        return $this->vehicleTransactionRepository->createVehicleTransaction($request, $idvehicle, $iduser);                                                                                     
    }
    public function getVehicleTransactions()
    {
        return $this->vehicleTransactionRepository->getVehicleTransactions();
    }

    public function getVehicleTransactionById($idtransaction)
    {
        return $this->vehicleTransactionRepository->getVehicleTransactionById($idtransaction);
    }

    public function getVehicleTransactionReport()
    {
        return $this->vehicleTransactionRepository->getVehicleTransactionReport();
    }
}