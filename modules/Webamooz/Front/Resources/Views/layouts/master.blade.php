<!doctype html>
<html lang="fa">
@includeIf('Front::layouts.head')
<body>

@includeIf('Front::layouts.header')

@yield('content')

@include('Front::layouts.footer')

@include('Front::layouts.foot')  {{-- files js --}}
</body>
</html>
