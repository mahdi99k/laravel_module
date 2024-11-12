<!doctype html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/style.css?number={{ uniqid() }}">  {{-- ?v={{ uniqid() }} ورژن = هر سری تغییر کنه تا کش نکنه مرورگر --}}
    <link rel="stylesheet" href="/css/font/font.css">
    <title>@yield('title')</title>
    @yield('css')
</head>
<body>
<main>

    <div class="account">
        @yield('content')
    </div>

</main>
@yield('js')
</body>
</html>
