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
<div class="form-group row">
    <div class="col-lg-4">
        {!! $form->render('username') !!}
    </div>
    <div class="col-lg-4">
        @include('admin.partials.password_field', ['field' => 'password', 'old' => true])
    </div>
</div>
{!! $form->footer !!}
