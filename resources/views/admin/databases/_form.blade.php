@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
{!! $form->header !!}

<div class="form-group row">
    <div class="col-lg-4">
        {!! $form->render('label') !!}
        @include('admin.partials.input_error', ['name' => 'label'])
    </div>
    <div class="col-lg-4">
        {!! $form->render('host') !!}
        @include('admin.partials.input_error', ['name' => 'host'])
    </div>
    <div class="col-lg-4">
        {!! $form->render('driver') !!}
        @include('admin.partials.input_error', ['name' => 'driver'])
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-8">
        {!! $form->render('database') !!}
        @include('admin.partials.input_error', ['name' => 'database'])
    </div>
    <div class="col-lg-4">
        {!! $form->render('port') !!}
        @include('admin.partials.input_error', ['name' => 'port'])
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-4">
        {!! $form->render('charset') !!}
        @include('admin.partials.input_error', ['name' => 'charset'])
    </div>
    <div class="col-lg-4">
        {!! $form->render('prefix') !!}
        @include('admin.partials.input_error', ['name' => 'prefix'])
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-4">
        {!! $form->render('username') !!}
        @include('admin.partials.input_error', ['name' => 'username'])
    </div>
    <div class="col-lg-4">
        @include('admin.partials.password_field', ['field' => 'password', 'old' => true])
        @include('admin.partials.input_error', ['name' => 'password'])
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-8">
        {!! $form->render('token') !!}
        @include('admin.partials.input_error', ['name' => 'token'])
    </div>
    <div class="col-lg-4">
        {!! $form->render('update_token') !!}
        @include('admin.partials.input_error', ['name' => 'update_token'])
    </div>
</div>
{!! $form->footer !!}
