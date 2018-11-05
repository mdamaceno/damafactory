@extends('layouts.admin')

@section('content')
  <div class="card">
        <div class="card-header header-elements-inline">
            <h5 class="card-title">{{ __('Users') }}</h5>
        </div>
        <div class="card-body">
            {!! $filter !!}
            {!! $grid !!}
        </div>
    </div>
@endsection
