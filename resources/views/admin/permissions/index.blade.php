@extends('layouts.admin')

@section('content')
	<div class="card">
        @include('admin.partials.card_title', ['title' => __('Permissions')])
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <a href="{{ url($links['new_permissions']) }}" class="btn btn-sm btn-primary">{{__('New permission')}}</a>
            @include('admin.partials.search_table', ['filter' => $filter, 'url' => $links['index_permissions']])
            {!! $grid !!}
        </div>
    </div>
@endsection
