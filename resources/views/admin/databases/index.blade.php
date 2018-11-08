@extends('layouts.admin')

@section('content')
	<div class="card">
        @include('admin.partials.card_title', ['title' => __('Databases')])
        <div class="card-body">
            @include('admin.partials.search_table', ['filter' => $filter, 'url' => '/admin/databases'])
            {!! $grid !!}
        </div>
    </div>
@endsection
