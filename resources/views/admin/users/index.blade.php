@extends('layouts.admin')

@section('content')
	<div class="card">
        @include('admin.partials.card_title', ['title' => __('Users')])
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <a href="{{ url('/admin/users/new') }}" class="btn btn-sm btn-primary">{{__('New user')}}</a>
            @include('admin.partials.search_table', ['filter' => $filter, 'url' => '/admin/users'])
            {!! $grid !!}
        </div>
    </div>
@endsection
