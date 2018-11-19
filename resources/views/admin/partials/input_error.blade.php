@if(count($errors->get($name)))
    @foreach ($errors->get($name) as $message)
    <span class="help-block has-error">
        <span class="glyphicon glyphicon-warning-sign"></span>
        {!! $message !!}
    </span>
    @endforeach
@endif
