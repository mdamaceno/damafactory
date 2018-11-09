@extends('layouts.admin')

@section('content')
  <div class="card">
        @include('admin.partials.card_title', ['title' => __('Databases')])
        <div class="card-body">
            @include('admin.databases._form', ['form' => $form])
        </div>
    </div>
@endsection
