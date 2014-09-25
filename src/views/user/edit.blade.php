@extends($extends['view'])

@section($extends['section'])
    <div class="row">
        <div class="col-sm-12">
            <h1>{{{ $user->getIdentifier() }}} {{ trans('auth::form.edit') }}</h1>
        </div>
    </div>

    @if ( Session::has('message') )
    <div class="row">
        <div class="col-sm-12">
            {{ trans('auth::'.Session::get('message') ) }}
        </div>
    </div>
    @endif
    @if ( $errors->has('error') )
    <div class="row">
        <div class="col-sm-12 text-danger">
            {{ $errors->first('error') }}
        </div>
    </div>
    @endif

    {{ Form::open(['route' => ['auth.user.update', $user->getKey()], 'method' => 'put']) }}
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group {{ $errors->has('email') ? 'has-error has-feedback' : '' }} {{ Session::has('email') ? 'has-success has-feedback' : '' }}">
                {{ Form::label( 'email', trans('auth::user.email'), ['class' => 'control-label'] ) }}
                {{ Form::text( 'email', $user->getEmail(), ['class' => 'form-control']) }}
                @if ( $errors->has( 'email' ) )
                    <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                    <span class="text-danger">
                        {{ trans( 'auth::'.$errors->first( 'email' ) ) }}
                    </span>
                @elseif ( Session::has( 'email' ) )
                    <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                    <span class="text-success">
                        {{ trans( 'auth::'.Session::get('email') ) }}
                    </span>
                @endif
            </div>
        </div>
    </div>
    @if(! Auth::user()->isSuperuser())
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group {{ $errors->has('old_password') ? 'has-error has-feedback' : '' }} }}">
                {{ Form::label( 'old_password', trans('auth::user.old_password'), ['class' => 'control-label'] ) }}
                {{ Form::password( 'old_password', null, ['class' => 'form-control'] ) }}

                @if ( $errors->has( 'old_password' ) )
                    <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                    <span class="text-danger">
                        {{ trans( 'auth::'.$errors->first('old_password') ) }}
                    </span>
                @endif
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group {{ $errors->has('password') ? 'has-error has-feedback' : '' }} {{ Session::has('password') ? 'has-success has-feedback' : '' }} }}">
                {{ Form::label( 'password', trans('auth::user.new_password'), ['class' => 'control-label'] ) }}
                {{ Form::password( 'password', null, ['class' => 'form-control']) }}

                @if ( $errors->has( 'password' ) )
                    <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                    <span class="text-danger">
                        {{ trans( 'auth::'.$errors->first('password') ) }}
                    </span>
                @elseif ( Session::has( 'password' ) )
                    <span class="glyphicon glyphicon-ok form-control-feedback"></span>
                    <span class="text-success">
                        {{ trans( 'auth::'.Session::get('password') ) }}
                    </span>
                @endif
            </div>
        </div>
    </div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error has-feedback' : '' }} }}">
            {{ Form::label( 'password_confirmation', trans('auth::user.password_confirmation'), ['class' => 'control-label'] ) }}
            {{ Form::password('password_confirmation', null, ['class' => 'form-control']) }}
            @if ( $errors->has( 'password_confirmation' ) )
                <span class="text-danger">
                    {{  trans( 'auth::'. $errors->first( 'password_confirmation' ) )  }}
                </span>
            @endif
        </div>
    </div>
</div>
@foreach ($extra_fields as $extra_field)
<div class="row">
    <div class="col-sm-12">
        {{ Form::label( $extra_field['name'], trans($extra_field['name']) ) }}
        {{ Form::input($extra_field['form_type'], $extra_field['name'], $user->getExtra($extra_field['name']) ) }}
        @if ( $errors->has( $extra_field['name'] ) )
                    <span class="text-danger">
                        {{  trans( 'auth::'. $errors->first( $extra_field['name'] ) )  }}
                    </span>
        @endif
    </div>
</div>
@endforeach
@if ($can_enable  )
<div class="row">
    <div class="col-sm-12">
        <div class="form-group {{ $errors->has('enabled') ? 'has-error has-feedback' : '' }} {{ Session::has('enabled') ? 'has-success has-feedback' : '' }} }}">
            <div class="row">
                <div class="col-sm-12">
                    {{ Form::label( 'enabled' , trans('auth::user.enabled', ['class' => 'form-control']) ) }}
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label class="radio-inline">
                        {{ trans('auth::form.yes') }} {{ Form::radio( 'enabled', '1', $user->isEnabled() ) }}
                    </label>
                    <label class="radio-inline">
                        {{ trans('auth::form.no') }} {{ Form::radio( 'enabled', '0', !$user->isEnabled() ) }}
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    @if ( $errors->has( 'enabled' ) )
                    <span class="text-danger">
                        {{ trans( 'auth::'.$errors->first('enabled') ) }}
                    </span>
                        @elseif ( Session::has( 'enabled' ) )
                    <span class="text-success">
                        {{ trans( 'auth::'.Session::get('enabled') ) }}
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<div class="row">
    <div class="col-sm-2 col-sm-push-10 text-right">
        {{ Form::submit(trans('auth::form.save'), ['class' => 'btn btn-success']) }}
    </div>
    {{ Form::close() }}
    <div class="col-sm-2 col-sm-push-3 text-center">
            {{ Form::open(['route' => ['auth.user.destroy', $user->getAuthIdentifier()], 'method' => 'delete']) }}
            {{ Form::submit(trans('auth::form.delete'), ['class' => 'btn btn-danger']) }}
            {{ Form::close() }}
        </div>
        <div class="col-sm-1 col-sm-pull-4">
            <a href="{{ Request::header('referer') }}">{{ Form::button(trans('auth::form.back'), ['class' => 'btn btn-primary']) }}</a>
        </div>
    </div>
@stop
