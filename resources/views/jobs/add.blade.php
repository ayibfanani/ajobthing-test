@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <add-job-form api_token="{{ $api_token }}" encoded_job="{{ json_encode($job) }}"></add-job-form>
        </div>
    </div>
</div>
@endsection