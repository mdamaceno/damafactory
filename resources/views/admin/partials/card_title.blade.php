<div class="card-header header-elements-inline">
    @if (isset($link))
        <h5 class="card-title"><a href="{{$link}}">{{$title}}</a></h5>
    @else
        <h5 class="card-title">{{ $title }}</h5>
    @endif
</div>
