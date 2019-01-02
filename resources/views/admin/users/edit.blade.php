@extends('layouts.admin')

@section('content')
  <div class="card">
        @include('admin.partials.card_title', ['title' => __('Users'), 'link' => url($links['index_users'])])
        <div class="card-body">
            @include('admin.users._form', ['form' => $form])
        </div>
    </div>
@endsection
