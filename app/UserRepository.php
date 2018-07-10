<?php

namespace App;

use App\Roles\Role;

class UserRepository
{
    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function getSingle($id)
    {
        return $this->model->with('role')->find($id);
    }

    public function upgrade()
    {
        $auth = auth()->user();
        $client_role = Role::where('key', 'client')->first();

        $auth->fill(['role_id' => $client_role->id]);

        if ($auth->save()) {
            return true;
        }

        return false;
    }
}
