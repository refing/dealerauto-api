<?php
namespace App\Repositories;
use App\Models\VehicleTransaction;
use App\Models\Vehicle;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
class VehicleTransactionRepository
{
    protected $model;

    public function __construct(VehicleTransaction $model)
    {
        $this->model = $model;
    }

    public function createVehicleTransaction(Request $request, $idvehicle, $iduser)
    {
        $vehicle = Vehicle::find($idvehicle);

        $vehicleTransaction           = new VehicleTransaction();
        $vehicleTransaction->_id     = "tsc-" . Str::uuid();
        $vehicleTransaction->qty     = $request->input('qty');
        $vehicleTransaction->totalprice     = $vehicle->price * $request->input('qty');
        $vehicleTransaction->id_vehicle    = $vehicle->_id;
        $vehicleTransaction->id_buyer = $iduser;
        $vehicleTransaction->save();

        $vehicle->stock_qty = $vehicle->stock_qty - $request->input('qty');
        $vehicle->save();
        return $vehicleTransaction;
    }

    public function getVehicleTransactions()
    {
        return VehicleTransaction::get()->toArray();
    }

    public function getVehicleTransactionById($idtransaction)
    {
        return VehicleTransaction::find($idtransaction);;
    }

    public function getVehicleTransactionReport()
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
        return $vehicleTransaction;
    }
}