<?php

namespace App\Jobs;

use Illuminate\Http\Request;

class JobRepository
{
    public function __construct(Job $model)
    {
        $this->model = $model;
    }

    public function fetchAllTimeline($filters = [])
    {
        $auth = auth()->user();
        $search = !empty($filters['search']) ? $filters['search'] :  '';

        $jobs = $this->model->where('status', 1)->when($search, function ($q) use ($search) {
            $q->where('title', 'LIKE', "%$search%")
                    ->orWhere('body', 'LIKE', "%$search%");
        })->get();

        return $jobs;
    }

    public function fetchAll($filters = [])
    {
        $auth = auth()->user();
        $search = !empty($filters['search']) ? $filters['search'] :  '';

        $jobs = $this->model->where('user_id', $auth->id)
            ->when($search, function ($q) use ($search) {
                $q->where('title', 'LIKE', "%$search%")
                    ->orWhere('body', 'LIKE', "%$search%");
            })->get();

        return $jobs;
    }

    public function getSingle($id)
    {
        return $this->model->with('user')->find($id);
    }

    public function create(array $data)
    {
        $job = $this->model->create(array_only($data, [
            'user_id', 'title', 'body', 'budget', 'status'
        ]));

        if (!empty($job)) {
            return $job->id;
        }

        return false;
    }

    public function apply(array $data)
    {
        return \DB::transaction(function () use ($data) {
            $job = $this->getSingle($data['job_id']);
            $job->applicants()->attach($data['user_id'], [
                'status' => 'pending',
                'fr_budget' => $data['fr_budget'],
                'completed_at' => $data['completed_at'],
                'messages' => $data['messages']
            ]);

            $auth = auth()->user();
            $points  = $auth->points;
            $auth->fill([
                'points' => $auth->points - 2
            ]);

            return $auth->save();
        });
    }

    public function accept(array $data)
    {
        return \DB::transaction(function () use ($data) {
            $job = $this->getSingle($data['job_id']);
            $job->applicants()->updateExistingPivot($data['user_id'], [
                'status' => 'accepted',
            ]);

            return true;
        });
    }

    public function markComplete(array $data)
    {
        return \DB::transaction(function () use ($data) {
            $job = $this->getSingle($data['job_id']);
            $job->applicants()->updateExistingPivot($data['user_id'], [
                'status' => 'completed',
            ]);

            return true;
        });
    }

    public function cancel(array $data)
    {
        return \DB::transaction(function () use ($data) {
            $job = $this->getSingle($data['job_id']);
            $job->applicants()->wherePivot('user_id', $data['user_id'])->detach();

            $auth = auth()->user();
            $points  = $auth->points;
            $auth->fill([
                'points' => $auth->points + 2
            ]);

            return true;
        });
    }

    public function storeValidation(Request $request)
    {
        $request->validate([
            'title' => 'required|max:150',
            'body' => 'required',
            'budget' => 'required|min:1',
            'status' => 'required|in:0,1'
        ]);
    }
}
