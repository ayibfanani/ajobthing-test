<?php

namespace App;

use App\Jobs\Job;
use App\Roles\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'firstname', 'lastname', 'email', 'password', 'points', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function jobs()
    {
        return $this->belongsToMany(Job::class, 'job_user')->withPivot(['fr_budget', 'completed_at', 'status', 'messages'])->withTimestamps();
    }
}
