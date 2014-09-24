@extends($extends['view'])

@section($extends['section'])
        <div class="row">
            <div class="col-sm-12">
                <h1>{{ trans('auth::form.reminder') }}</h1>
            </div>
        </div>
        {{ Form::open(['route' => 'auth.perform_remind']) }}
        <div class="row">
            <div class="col-sm-12">
                {{ Form::label( 'email', trans('auth::user.email')) }}
                {{ Form::text('email') }}
            </div>
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
        <div class="row">
            <div class="col-sm-1">
                {{ Form::submit(trans('auth::form.request')) }}
            </div>
            {{ Form::close() }}
            <div class="col-sm-1 col-sm-offset-10">
                <a href="{{ route('auth.login') }}"> {{ Form::button(trans('auth::form.back')) }}</a>
            </div>
        </div>

@stop
