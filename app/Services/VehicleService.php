<?php
namespace App\Services;
use App\Repositories\VehicleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehicleService
{
    private $vehicleRepository;

    public function __construct(VehicleRepository $vehicleRepository)
    {
        $this->vehicleRepository = $vehicleRepository;
    }

    public function createVehicle(Request $request)
    {
        return $this->vehicleRepository->createVehicle($request);                                                                                     
    }
    public function getVehicles()
    {
        return $this->vehicleRepository->getVehicles();
    }

    public function getVehicleById($id)
    {
        return $this->vehicleRepository->getVehicleById($id);
    }

    public function updateVehicleById(Request $request, $id)
    {
        return $this->vehicleRepository->updateVehicleById($request, $id);
    }

    public function deleteVehicleById($id)
    {
        return $this->vehicleRepository->deleteVehicleById($id);
    }

    public function getVehicleStock()
    {
        return $this->vehicleRepository->getVehicleStock();
    }
}