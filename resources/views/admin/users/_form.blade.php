@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
{!! $form->header !!}
<div class="form-group row">
    <div class="col-lg-4">
        {!! $form->render('email') !!}
        @include('admin.partials.input_error', ['name' => 'email'])
    </div>
    <div class="col-lg-4">
        {!! $form->render('name') !!}
        @include('admin.partials.input_error', ['name' => 'name'])
    </div>
</div>
<div class="form-group row">
    <div class="col-lg-4">
        {!! $form->render('role') !!}
        @include('admin.partials.input_error', ['name' => 'role'])
    </div>
    <div class="col-lg-4">
        @include('admin.partials.password_field', ['field' => 'password'])
        @include('admin.partials.input_error', ['name' => 'password'])
    </div>
</div>
<div class="form-group row" id="el__db-permission">
    <div class="col-lg-4">
        {!! $form->render('db_permission') !!}
        @include('admin.partials.input_error', ['name' => 'db_permission'])
    </div>
</div>
{!! $form->footer !!}

@section('scripts')
<script>
let divPermissionSelectEl = $('#el__db-permission');
let selectRoleEl = $('#role');

let selectPermission = function () {
    if (selectRoleEl.val() === 'master') {
        divPermissionSelectEl.css('display', 'none');
    } else {
        divPermissionSelectEl.css('display', 'block');
    }
}

selectPermission()

$(selectRoleEl).change(selectPermission);
</script>
@endsection
