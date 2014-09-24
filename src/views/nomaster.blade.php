<!doctype html>
<html lang="en">
@foreach($errors->all() as $message)
    {{ $message }}
@endforeach
<head>
    <meta charset="UTF-8">
    <title>Laravel PHP Framework</title>
    <style>
    </style>
    @yield('head')
</head>

<body>
@yield('content')
</body>
</html>
