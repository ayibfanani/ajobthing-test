@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-md-center">
        <div class="col-md-8">
        <apply-form api_token="{{ $api_token }}"
            encoded_auth="{{ json_encode($auth) }}"
            encoded_job="{{ json_encode($job) }}"
            is_applied_string="{{ $is_applied }}"
        ></apply-form>
        </div>
    </div>
</div>
@endsection
