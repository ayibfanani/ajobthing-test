@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Available Jobs</div>

                <available-jobs encoded_jobs="{{ json_encode($jobs) }}" encoded_auth="{{ json_encode($auth) }}"></available-jobs>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="pull-right">{{ $auth['points'] }} Points</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
