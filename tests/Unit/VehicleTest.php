<?php

namespace Tests\Unit;
use Tests\TestCase;
use App\Models\Vehicle;
use App\Models\VehicleTransaction;
use App\Models\User;
use JWTAuth;
class VehicleTest extends TestCase
{
    protected $user;
    protected $token;
    protected $headers;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'user test',
            'email' => 'usertest@example.com',
            'password' => bcrypt('secret'),
            'role' => 'user'
        ]);
        $this->token = JWTAuth::fromUser($this->user);

        $this->headers = ['Authorization' => "Bearer {$this->token}"];

        $this->vehicle = Vehicle::create([
            "_id" => "veh-903b9e6b-a4fb-45e0-8ef1-557708edf458",
            "name" => "Honda Scoopy",
            "year" => "2023",
            "color" => "red",
            "price" => "21000000",
            "stock_qty" => 234,
            "flagtype" => "Motor",
            "motorcycle_machine" => "L23",
            "suspension_type" => "Coil",
            "transmission_type" => "Automatic"
        ]);

        $this->transaction = VehicleTransaction::create([
            "_id"=> "tsc-ced23d23-4d57-3697-954f-7c41f4bc0fab",
            "qty"=> 1,
            "totalprice"=> 8000000000,
            "id_vehicle"=> "veh-28a9392f-a859-49fe-88f0-a630ee51f487",
            "id_buyer"=> "user-c59820d6-4652-4f5e-831a-9574d2799a5f",
        ]);
    }

    public function testGetVehicles()
    {
        $response = $this->json('GET', '/api/vehicles', [], $this->headers);

        $response->assertStatus(200);
    }

    public function testGetVehiclesById()
    {
        $response = $this->json('GET', "/api/vehicles/id/{$this->vehicle->_id}", [], $this->headers);

        $response->assertStatus(200);
    }

    public function testCreateVehicle()
    {
        $response = $this->json('POST', '/api/vehicles/create', [
            "name" => "Honda honda",
            "year" => "2013",
            "color" => "white",
            "price" => "1000000",
            "stock_qty" => 24,
            "flagtype" => "Motor",
            "motorcycle_machine" => "W43",
            "suspension_type" => "Coil",
            "transmission_type" => "Automatic"
        ], $this->headers);

        $response->assertStatus(200);
    }

    public function testUpdateVehicle()
    {
        $response = $this->json('PUT', "/api/vehicles/update/{$this->vehicle->_id}", [
            'name' => 'Updated Vehicle',
            'year' => 2022
        ], $this->headers);

        $response->assertStatus(200);
    }

    public function testDeleteVehicle()
    {

        $response = $this->json('DELETE', "/api/vehicles/delete/{$this->vehicle->id}", [], $this->headers);

        $response->assertStatus(200);
    }

    public function testGetStock()
    {

        $response = $this->json('GET', "/api/vehicles/stock", [], $this->headers);

        $response->assertStatus(200);
    }

    public function testGetTransaction()
    {
        $response = $this->json('GET', '/api/transaction', [], $this->headers);

        $response->assertStatus(200);
    }

    public function testGetTransactionById()
    {
        $response = $this->json('GET', "/api/transaction/id/{$this->transaction->_id}", [], $this->headers);

        $response->assertStatus(200);
    }

    public function testCreateTransaction()
    {
        $response = $this->json('POST', "/api/transaction/buy/{$this->vehicle->_id}", [], $this->headers);

        $response->assertStatus(200);
    }

    public function testGetReport()
    {
        $response = $this->json('GET', "/api/transaction/report", [], $this->headers);

        $response->assertStatus(200);
    }
}