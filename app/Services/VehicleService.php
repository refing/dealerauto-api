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
            return null;
        }

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
            return null;
        }
       
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