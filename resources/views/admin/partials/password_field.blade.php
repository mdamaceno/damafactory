<div id="div_{!! $form->field($field)->name !!}" class="@if ($form->field($field)->has_error) has-error @endif form-group form-group-feedback form-group-feedback-right">

        <label for="{!! $form->field($field)->name !!}">{!! $form->field($field)->label.$form->field($field)->star !!}</label>
        <input type="password" id="password" name="password" class="form-control" @if(isset($old) && (bool) @$old) value="{{ $form->field($field)->value }}" @endif>

        <div class="form-control-feedback">
            <i onclick="hidePwd(this, '{{ $field }}')" class="icon-eye-blocked"></i>
        </div>

        @if(count($form->field($field)->messages))
            @foreach ($form->field($field)->messages as $message)
            <span class="help-block">
                <span class="glyphicon glyphicon-warning-sign"></span>
                {!! $message !!}
            </span>
            @endforeach
        @endif

</div>
