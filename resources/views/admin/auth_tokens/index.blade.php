@extends('layouts.admin')

@section('content')
	<div class="card">
        @include('admin.partials.card_title', ['title' => __('Auth Tokens')])
        <div class="card-body">
            @include('admin.partials.search_table', ['filter' => $filter, 'url' => '/admin/auth-tokens'])
            {!! $grid !!}
        </div>
    </div>
@endsection
