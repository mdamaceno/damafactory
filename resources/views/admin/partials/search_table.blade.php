{!! $filter->open !!}
<div class="form-group form-group-feedback form-group-feedback-right">
    {!! $filter->field('src') !!}
    <div class="form-control-feedback-right">
        <a href="javascript:void(0);" class="btn btn-default" onclick="$(this).closest('form').submit();">
            <span class="glyphicon glyphicon-search"></span>
        </a>
        <a href="{{ url($url) }}" class="btn btn-default">
            <span class="glyphicon glyphicon-remove"></span>
        </a>
    </div>
</div>
{!! $filter->close !!}
