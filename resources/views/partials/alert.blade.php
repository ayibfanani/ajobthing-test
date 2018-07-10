<div id="alert-container">
    @if (! empty(session('message')))
        <div class="notif alert alert-info alert-dismissible animated bounceIn">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><span aria-hidden="true">&times;</span></button>
            @if (is_array(session('message')))
                @if(session('message.title')) <h4>{!! session('message.title') !!}</h4> @endif
                {!! session('message.content') !!}
            @else
                {!! session('message') !!}
            @endif
        </div>
    @endif

    @if (session()->has('status'))
        <div class="notif alert alert-success alert-dismissible animated bounceIn">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"><span aria-hidden="true">&times;</span></button>
            <p>{!! session()->get('status') !!}</p>
        </div>
    @endif

    @if (count($errors) > 0)
        <div class="notif alert alert-danger alert-dismissible animated bounceIn">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <p><strong>Whoops! There were some problem(s):</strong></p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>