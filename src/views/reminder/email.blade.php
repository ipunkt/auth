<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>{{ trans('auth::reminders.email.subject') }}</h2>

<div>
    {{ trans('auth::reminders.email.address') }}<br>
    {{ trans('auth::reminders.email.text', ['site' => 'temp', 'link' => route('auth.reset_password', $token) ]) }}
</div>
</body>
</html>
