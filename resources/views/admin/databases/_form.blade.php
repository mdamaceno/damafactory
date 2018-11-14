@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
{!! $form->header !!}
<div class="form-group row">
    <div class="col-lg-4">
        {!! $form->render('label') !!}
    </div>
    <div class="col-lg-4">
        {!! $form->render('host') !!}
    </div>
    <div class="col-lg-4">
        {!! $form->render('driver') !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-8">
        {!! $form->render('database') !!}
    </div>
    <div class="col-lg-4">
        {!! $form->render('port') !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-4">
        {!! $form->render('charset') !!}
    </div>
    <div class="col-lg-4">
        {!! $form->render('prefix') !!}
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        {!! $form->render('username') !!}
    </div>
    <div class="col-lg-4">
        <div id="div_{!! $form->field('password')->name !!}" class="@if ($form->field('password')->has_error) has-error @endif form-group form-group-feedback form-group-feedback-right">

                <label for="{!! $form->field('password')->name !!}">{!! $form->field('password')->label.$form->field('password')->star !!}</label>
                <input type="password" id="password" name="password" value="{!! $form->field('password')->value !!}" class="form-control">

                <div class="form-control-feedback">
                    <i onclick="hidePwd(this, 'password')" class="icon-eye-blocked"></i>
                </div>

                @if(count($form->field('password')->messages))
                    @foreach ($form->field('password')->messages as $message)
                    <span class="help-block">
                        <span class="glyphicon glyphicon-warning-sign"></span>
                        {!! $message !!}
                    </span>
                    @endforeach
                @endif

        </div>
    </div>
</div>
{!! $form->footer !!}
