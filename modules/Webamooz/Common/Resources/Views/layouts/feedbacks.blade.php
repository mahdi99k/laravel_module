@if(session()->has('feedbacks'))
    @foreach(session()->get('feedbacks') as $message)
        $.toast({
        heading: "{{ $message['heading'] }}",
        text: "{{ $message['text'] }}",
        showHideTransition: 'slide',
        icon: "{{ $message['type'] }}"
        })
    @endforeach
@endif
