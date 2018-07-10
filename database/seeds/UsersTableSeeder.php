<?php

use App\Roles\Role;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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

        $user = factory(User::class)->create([
            'role_id' => $this->admin_role->id,
            'email' => 'admin@ajt.com'
        ]);
    }
}
