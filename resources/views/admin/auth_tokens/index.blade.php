@extends('layouts.admin')

@section('content')
	<div class="card">
        @include('admin.partials.card_title', ['title' => __('Auth tokens')])
        <div class="card-body">
            @include('admin.partials.search_table', ['filter' => $filter, 'url' => "$locale/admin/auth-tokens"])
            {!! $grid !!}
        </div>
    </div>
@endsection
