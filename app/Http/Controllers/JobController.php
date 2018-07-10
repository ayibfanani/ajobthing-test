<?php

namespace App\Http\Controllers;

use App\Jobs\JobRepository;
use App\UserRepository;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function __construct()
    {
        $api_methods = ['cancel', 'markComplete', 'accept', 'apply', 'index', 'store', 'update', 'delete'];
        $this->middleware('auth:api')->only($api_methods);
        $this->middleware('auth')->except($api_methods);

        $this->middleware('checkrole:client')->only(['create', 'markComplete', 'accept', 'store', 'update', 'delete']);

        $this->jobs = app(JobRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $per_page = request('per_page') ?? 10;
        $jobs = $this->jobs->fetchAll();

        return response()->json($jobs->toArray());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auth = auth()->user();

        $data = [
            'api_token' => $auth->api_token,
            'job' => []
        ];

        return view('jobs.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->jobs->storeValidation($request);

        $request['user_id'] = auth()->user()->id;
        $job_id = $this->jobs->create($request->all());

        if (!empty($job_id)) {
            return response()->json($job_id);
        }

        return response()->json(false, 400);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $auth = auth()->user();
        $job = $this->jobs->getSingle($id);

        $data = [
            'api_token' => $auth->api_token,
            'job' => $job->toArray()
        ];

        return view('jobs.add', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getApply($id)
    {
        $auth = app(UserRepository::class)->getSingle(auth()->user()->id);
        $job = $this->jobs->getSingle($id);
        $is_applied = false;

        if (in_array($job->id, $auth->jobs->pluck('id')->toArray())) {
            $is_applied = true;
        }

        $data = [
            'auth' => $auth->toArray(),
            'api_token' => $auth->api_token,
            'job' => $job->toArray(),
            'is_applied' => $is_applied
        ];

        return view('jobs.apply', $data);
    }

    public function apply(Request $request, $id)
    {
        $auth = auth()->user();
        $now = now()->toDateTimeString();
        $is_applied = false;

        if (in_array($id, $auth->jobs->pluck('id')->toArray())) {
            $is_applied = true;
        }

        if (($auth->points - 2) >= 0 && !$is_applied) {
//          Validation
            $request['job_id'] = $id;
            $request->validate([
                'job_id' => 'required|exists:jobs,id',
                'fr_budget' => 'required|min:1',
                'messages' => 'required',
                'completed_at' => "required|after_or_equal:{$now}"
            ]);
            $request['user_id'] = $auth->id;

            $is_applied = $this->jobs->apply($request->all());

            if ($is_applied) {
                return response()->json(true);
            }
        }

        return response()->json(false, 400);
    }

    public function getApplier($id)
    {
        $auth = app(UserRepository::class)->getSingle(auth()->user()->id);
        $job = $this->jobs->getSingle($id);
        $applier = $job->applicants;

        $data = [
            'api_token' => $auth->api_token,
            'job' => $job->toArray(),
            'applier' => $applier->toArray()
        ];

        return view('jobs.applier', $data);
    }

    public function accept(Request $request, $id)
    {
//          Validation
        $request['job_id'] = $id;
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $is_accepted = $this->jobs->accept($request->all());

        if ($is_accepted) {
            return response()->json(true);
        }

        return response()->json(false, 400);
    }

    public function markComplete(Request $request, $id)
    {
//          Validation
        $request['job_id'] = $id;
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $is_completed = $this->jobs->markComplete($request->all());

        if ($is_completed) {
            return response()->json(true);
        }

        return response()->json(false, 400);
    }

    public function cancel(Request $request, $id)
    {
//          Validation
        $request['job_id'] = $id;
        $request['user_id'] = auth()->user()->id;
        $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $is_canceled = $this->jobs->cancel($request->all());

        if ($is_canceled) {
            return response()->json(true);
        }

        return response()->json(false, 400);
    }
}
