@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
{!! $form->header !!}
<div class="form-group row">
    <div class="col-lg-4">
        {!! $form->render('email') !!}
    </div>
    <div class="col-lg-4">
        {!! $form->render('name') !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-4">
        {!! $form->render('role') !!}
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-4">
        @include('admin.partials.password_field', ['field' => 'password'])
    </div>
</div>
{!! $form->footer !!}
