<div class="slideshow">
    @foreach($slides as $slide)
        <div class="slide">
            <a href="{{ $slide->link }}">
                <img style="height: 405px !important;width: 1025px" src="{{ $slide->media->getUrl('original') }}" alt="" title="{{ $slide->link }}">
            </a>
        </div>
    @endforeach

    <a class="prev" onclick="move(-1)"><span>&#10095</span></a>
    <a class="next" onclick="move(1)"><span>&#10094</span></a>

    <div class="items">
        @foreach ($slides as $slide)
            <div class="item">
                <div class="item-inner"></div>
            </div>
        @endforeach
    </div>
</div>
