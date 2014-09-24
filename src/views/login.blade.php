@extends($extends['view'])

@section($extends['section'])
    <div class="row">
        <div class="col-sm-12">
            <h1>{{ trans('auth::form.login') }}</h1>
        </div>
    </div>

    @if ( $errors->count() > 0 )
        @foreach($errors->all() as $error)
        <div class="row">
            <div class="col-sm-12 text-danger">
                {{ $error }}
            </div>
        </div>
        @endforeach
    @elseif ( Session::has('message') )
        <div class="row">
            <div class="col-sm-12 text-warning">
                {{ Session::get('message') }}
            </div>
        </div>
    @elseif ( Session::has('success') )
        <div class="row">
            <div class="col-sm-12 text-success">
                {{ Session::get('success') }}
            </div>
        </div>
    @endif

    {{ Form::open(['route' => 'auth.perform_login']) }}
    <div class="row">
        <div class="col-sm-12">
            {{ Form::label( Config::get('auth::user table.login through field'), trans('auth::user.'.Config::get('auth::user table.login through field')) ) }}
            {{ Form::text(Config::get('auth::user table.login through field')) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            {{ Form::label( 'password', trans('auth::user.password') ) }}
            {{ Form::password( 'password' ) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-2 col-sm-push-10 text-right">
            {{ Form::submit(trans('auth::form.login'), ['class' => 'btn btn-success']) }}
        </div>
        <div class="col-sm-2 col-sm-offset-3 text-center">
            <a href="{{ route('auth.remind') }}">{{ Form::button(trans('auth::form.forgotten'), ['class' => 'btn btn-info']) }}</a>
        </div>
        <div class="col-sm-2 col-sm-offset-3 col-sm-pull-10">
            <a href="{{ route('auth.user.create') }}">{{ Form::button(trans('auth::form.register'), ['class' => 'btn btn-primary']) }}</a>
        </div>
    </div>
    <div>
        <div class="row">
            @if(isset($socialauth_login_links))
                @foreach ($socialauth_login_links as $link)
                    <div class="col-sm-2">
                        {{ $link->getLink($link->getName()) }}
                    </div>
                @endforeach
            @endif
        </div>
    </div>
    {{ Form::close() }}
@stop
