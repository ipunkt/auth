@extends($extends['view'])

@section($extends['section'])
    <div class="row">
        <div class="col-sm-12">
            <h1>{{ trans('auth::form.register') }}</h1>
            @if ( isset($registerInfo) )
            <small> {{ trans('auth::form.register with', ['provider' => $registerInfo->getInfo('provider'),
                'user' => $registerInfo->getInfo('displayName')]) }}</small>
            @endif
        </div>
    </div>
    {{ Form::open(['route' => 'auth.user.store']) }}
    <div class="row">
        <div class="col-sm-12">
            {{ Form::label( 'email', trans('auth::user.email') ) }}
            @if ( isset($registerInfo) )
                {{ Form::text( 'email', $registerInfo->getInfo('email') ) }}
            @else
                {{ Form::text( 'email' ) }}
            @endif
            @if ( $errors->has( 'email' ) )
                <span class="text-danger">
                    {{ trans( 'auth::'.$errors->first( 'email' ) ) }}
                </span>
            @endif
        </div>
    </div>
    @foreach ($extra_fields as $extra_field)
    @if (! array_key_exists('not during register', $extra_field) )
    <div class="row">
        <div class="col-sm-12">
            {{ Form::label( $extra_field['name'], trans('auth::user.'.$extra_field['name']) ) }}

            <?PHP $fieldName = 'extraFields['.$extra_field['name'].']' ?>
            @if ( isset($registerInfo) )
                {{ Form::text( $fieldName, $registerInfo->getInfo($extra_field['name']) ) }}
            @else
                {{ Form::text( $fieldName ) }}
            @endif

            @if ( $errors->has( $extra_field['name'] ) )
                <span class="text-danger">
                    {{ trans( 'auth::'.$errors->first( $extra_field['name'] ) ) }}
                </span>
            @endif
        </div>
    </div>
    @endif
    @endforeach

    @if (!isset($registerInfo) || !$registerInfo->providesLogin())
    <div class="row">
        <div class="col-sm-12">
            {{ Form::label( 'password', trans('auth::user.password') ) }}
            {{ Form::password( 'password' ) }}

            @if ( $errors->has( 'password' ) )
                <span class="text-danger">
                    {{ trans( 'auth::'.$errors->first( 'password' ) ) }}
                </span>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            {{ Form::label( 'password_confirmation', trans('auth::user.password_confirmation') ) }}
            {{ Form::password('password_confirmation') }}
            @if ( $errors->has( 'password_confirmation' ) )
                <span class="text-danger">
                    {{  trans( 'auth::'. $errors->first( 'password_confirmation' ) )  }}
                </span>
            @endif
        </div>
    </div>
    @endif
    <div class="row">
        @if(class_exists('Ipunkt\SocialAuth\SocialAuth'))
            @foreach (Ipunkt\SocialAuth\SocialAuth::getProviders() as $provider)
                <div class="col-sm-2">
                    {{ $provider->registerLink($provider->getName()) }}
                </div>
            @endforeach
        @endif
    </div>
    <div class="row">
        <div class="col-sm-2">
            <a href="{{ route('auth.login') }}">{{ Form::button(trans('auth::form.back')) }}</a>
        </div>
        <div class="col-sm-10 text-right">
            {{ Form::submit(trans('auth::form.register')) }}
        </div>
    </div>
    {{ Form::close() }}
@stop
