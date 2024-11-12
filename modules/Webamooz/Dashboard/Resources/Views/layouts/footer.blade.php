<script src="{{ asset('panel/js/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('/js/jquery.toast.min.js') }}"></script>
<script src="/panel/js/js.js?v={{ uniqid() }}"></script>
<script src="{{ asset('panel/js/tagsInput.js') }}"></script>
<script>
    @include('Common::layouts.feedbacks')
</script>
@yield('js')
