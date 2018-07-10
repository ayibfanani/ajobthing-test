@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">Freelancer Submit</div>

                <applier-lists api_token="{{ $api_token }}" job_id="{{ $job['id'] }}" encoded_applier="{{ json_encode($applier) }}"></applier-lists>
            </div>
        </div>
    </div>
</div>
@endsection
