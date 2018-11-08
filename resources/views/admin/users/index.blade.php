@extends('layouts.admin')

@section('content')
	<div class="card">
        @include('admin.partials.card_title', ['title' => __('Users')])
        <div class="card-body">
            @include('admin.partials.search_table', ['filter' => $filter, 'url' => '/admin/users'])
            {!! $grid !!}
        </div>
    </div>
@endsection
