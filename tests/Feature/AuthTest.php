<?php

namespace Tests\Feature;

use App\Roles\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\BaseTest;

class AuthTest extends BaseTest
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->_seedRoles();
    }

    public function test_user_register_as_freelancer()
    {
        $data = [
            'firstname' => 'New',
            'lastname' => 'Freelancer',
            'email' => 'newfreelancer@test.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ];

        $this->post("/register", $data)
            ->assertRedirect('/home');

//      It means as a Freelancer
        $data['role_id'] = Role::where('key', 'freelancer')->first()->id;
        $data['points'] = 40;
        $this->assertDatabaseHas('users', array_only($data, [
            'firstname', 'lastname', 'email', 'points', 'role_id'
        ]));
    }
}
