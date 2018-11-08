@extends('layouts.admin')

@section('content')
  <div class="card">
        @include('admin.partials.card_title', ['title' => __('Databases')])
        <div class="card-body">
            {!! $form !!}
        </div>
    </div>
@endsection
