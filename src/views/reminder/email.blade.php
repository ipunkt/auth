<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>{{ trans('auth::reminders.email.header') }}</h2>

<div>
    {{ trans('auth::reminders.email.address') }}<br>
    {{ trans('auth::reminders.email.text', ['site' => 'temp', 'link' => URL::to('auth/reset', $token) ]) }}
</div>
</body>
</html>
