@extends($extends['view'])

@section($extends['section'])
    <div class="row">
        <table>
            <tr>
                <th>Username</th>
                <th>Superuser</th>
                <th>Enabled</th>
            </tr>
            @foreach($users as $user)
            <tr>
                <td class="identifier"><a href="{{ route('auth.user.edit', $user->getid()) }} ">{{{ $user->getIdentifier() }}}</a></td>
                <td class="superuser">{{{ $user->isSuperuser() }}}</td>
                <td class="enabled">{{{ $user->isEnabled() }}}</td>
            </tr>
            @endforeach
        </table>
    </div>
@stop
