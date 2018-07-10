<?php

namespace App\Jobs;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'user_id', 'title', 'body', 'budget', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function applicants()
    {
        return $this->belongsToMany(User::class, 'job_user')->withPivot(['fr_budget', 'completed_at', 'status', 'messages'])->withTimestamps();
    }
}
