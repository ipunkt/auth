@extends($extends['view'])

@section($extends['section'])
    <div class="row">
        <div class="col-sm-12">
            <h1>{{ trans('auth::form.reset') }}</h1>
        </div>
        <div class="row">
            <div class="col-sm-12">
                @if (Session::has('error'))
                <div class="text-danger">
                    {{ trans('auth::'.Session::get('error')) }}
                </div>
                @elseif (Session::has('status'))
                <div class="text-success">
                    {{ trans('auth::'.Session::get('status')) }}
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            {{ Form::open(['route' => 'auth.perform_reset_password']) }}
            {{ Form::hidden( 'token' , $token) }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            {{ Form::label( 'email', trans('auth::user.email') ) }}
            {{ Form::text('email') }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            {{ Form::label( 'password', trans('auth::user.password') ) }}
            {{ Form::password('password') }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            {{ Form::label( 'password_confirmation', trans('auth::user.password_confirmation') ) }}
            {{ Form::password('password_confirmation') }}
        </div>
    </div>
    <div class="row">
        <div class="col-sm-1">
            {{ Form::submit(trans('auth::form.reset')) }}
        </div>
        <div class="col-sm-1 col-sm-offset-10">
            <a href="{{ route('auth.login') }}">{{ trans('auth::form.back') }}</a>
        </div>
    </div>
    {{ Form::close() }}
@stop
