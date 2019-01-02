@extends('layouts.admin')

@section('content')
  <div class="card">
      @include('admin.partials.card_title', ['title' => __('Permissions'), 'link' => url($links['index_permissions'])])
        <div class="card-body">
            @include('admin.permissions._form', ['form' => $form])
        </div>
    </div>
@endsection
