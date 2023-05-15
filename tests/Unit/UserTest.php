<?php

namespace Tests\Unit;
use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    public function testCreateUser()
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johnd@example.com',
            'password' => bcrypt('secret'),
            'role' => 'user'
        ]);

        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('johnd@example.com', $user->email);
        $this->assertTrue(\Hash::check('secret', $user->password));
    }


}