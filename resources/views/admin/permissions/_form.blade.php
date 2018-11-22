@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
{!! $form->header !!}
<div class="form-group row">
    <div class="col-lg-4">
        {!! $form->render('name') !!}
        @include('admin.partials.input_error', ['name' => 'name'])
    </div>
    <div class="col-lg-4">
        {!! $form->render('active') !!}
        @include('admin.partials.input_error', ['name' => 'active'])
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-4">
        {!! $form->render('http_permission') !!}
        @include('admin.partials.input_error', ['name' => 'http_permission'])
    </div>
</div>
{!! $form->footer !!}
