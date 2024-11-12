<!DOCTYPE html>
<html lang="en">
@includeIf('Dashboard::layouts.head')
<body>
@include('Dashboard::layouts.sidebar')

<div class="content">
    @includeIf('Dashboard::layouts.header')
    @includeIf('Dashboard::layouts.breadcrumb')

    {{----- Dynamic Content page -----}}
    <div class="main-content">
        @yield('content')
    </div>

</div>
</body>
@include('Dashboard::layouts.footer')
</html>
