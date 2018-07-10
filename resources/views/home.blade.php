@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @if($role_key == 'client')
                <div class="card text-white bg-secondary mb-3">
                    <div class="card-header">
                        <div class="pull-left">
                            Posted Jobs
                        </div>

                        <div class="pull-right">
                            <a href="{{ route('job.add') }}" class="btn btn-link"><i class="fa fa-plus"></i> Add New</a>
                        </div>
                    </div>

                    <posted-jobs encoded_auth="{{ json_encode($auth) }}" encoded_jobs="{{ json_encode($posted_jobs) }}"></posted-jobs>
                </div>
            <br>
            @endif

            <div class="card">
                <div class="card-header">Submitted Jobs</div>

                <submitted-jobs api_token="{{ $api_token }}" encoded_auth="{{ json_encode($auth) }}" encoded_jobs="{{ json_encode($submitted_jobs) }}"></submitted-jobs>
            </div>
            <br>

            <div class="card">
                <div class="card-header">Active Jobs</div>

                <active-jobs encoded_auth="{{ json_encode($auth) }}" encoded_jobs="{{ json_encode($active_jobs) }}"></active-jobs>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="pull-right">{{ $auth['points'] }} Points</div>
                </div>
            </div>

            <br>

            @if($role_key == 'freelancer')
                <upgrade-box></upgrade-box>
            @endif
        </div>
    </div>
</div>
@endsection
