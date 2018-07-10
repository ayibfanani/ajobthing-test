<?php

namespace App\Http\Controllers;

use App\Jobs\JobRepository;
use App\UserRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth = app(UserRepository::class)->getSingle(auth()->user()->id);
        $per_page = request('per_page') ?? 10;

        $jobs = $auth->jobs;

        $data = [
            'auth' => $auth,
            'api_token' => $auth->api_token,
            'role_key' => $auth->role->key,
            'posted_jobs' => app(JobRepository::class)->fetchAll()->toArray(),
            'submitted_jobs' => $jobs->where('pivot.status', 'pending')->toArray(),
            'active_jobs' => $jobs->whereIn('pivot.status', ['accepted', 'completed'])->toArray()
        ];

        return view('home', $data);
    }

    public function timeline()
    {
        $filters = request()->all();
        $auth = app(UserRepository::class)->getSingle(auth()->user()->id);
        $auth['job_ids'] = $auth->jobs->pluck('id')->toArray();

        $data = [
            'jobs' => app(JobRepository::class)->fetchAllTimeline($filters),
            'auth' => $auth->toArray()
        ];

        return view('timeline', $data);
    }

    public function upgrade()
    {
        $is_upgraded = app(UserRepository::class)->upgrade();

        if ($is_upgraded) {
            return redirect()->route('home');
        }

        return redirect()->back()->withErrors('Oops, looks like something went wrong!');
    }
}
