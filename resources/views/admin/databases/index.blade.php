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
            <a href="{{ url($links['new_database']) }}" class="btn btn-sm btn-primary">{{__('New database')}}</a>
            @include('admin.partials.search_table', ['filter' => $filter, 'url' => $links['index_database']])
            {!! $grid !!}
        </div>
    </div>
@endsection
