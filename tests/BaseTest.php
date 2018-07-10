<?php

namespace Tests;

use App\Roles\Role;
use App\User;

class BaseTest extends TestCase
{
    public function _seedRoles()
    {
        $this->admin_role = factory(Role::class)->create([
            'key' => 'admin',
            'name' => 'Admin'
        ]);
        $this->freelancer_role = factory(Role::class)->create();
        $this->client_role = factory(Role::class)->create([
            'key' => 'client',
            'name' => 'Client'
        ]);
    }

    public function _seedUsers()
    {
        $this->admin = factory(User::class)->create([
            'role_id' => $this->admin_role->id,
            'email' => 'admin@test.com',
            'points' => 1000
        ]);

        $this->freelancer = factory(User::class)->create([
            'role_id' => $this->freelancer_role->id,
            'email' => 'freelancer@test.com',
            'points' => 20
        ]);

        $this->client = factory(User::class)->create([
            'role_id' => $this->client_role->id,
            'email' => 'client@test.com',
            'points' => 40
        ]);
    }
}
