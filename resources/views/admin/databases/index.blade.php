@extends('layouts.admin')

@section('content')
	<div class="card">
        @include('admin.partials.card_title', ['title' => __('Databases')])
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @include('admin.partials.search_table', ['filter' => $filter, 'url' => '/admin/databases'])
            {!! $grid !!}
        </div>
    </div>
@endsection
