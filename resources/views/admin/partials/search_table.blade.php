{!! $filter->open !!}
<div class="col-md-4">
    <div class="form-group form-group-feedback form-group-feedback-right">
        {!! $filter->field('src') !!}
        <div class="form-control-feedback">
            <a href="javascript:void(0);" class="btn btn-default" onclick="$(this).closest('form').submit();">
                <span class="glyphicon glyphicon-search"></span>
            </a>
            <a href="{{ url($url) }}" class="btn btn-default">
                <span class="glyphicon glyphicon-remove"></span>
            </a>
        </div>
    </div>
</div>
{!! $filter->close !!}
