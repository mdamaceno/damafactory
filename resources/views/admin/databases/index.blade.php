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
            <a href="{{ url('/admin/databases/new') }}" class="btn btn-sm btn-primary">{{__('New database')}}</a>
            @include('admin.partials.search_table', ['filter' => $filter, 'url' => '/admin/databases'])
            {!! $grid !!}
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $("a[title='Delete']").click(function (el) {
        el.preventDefault();
        swal({
            icon: 'warning',
            title: 'Quer mesmo remover este registro?',
            buttons: {
                cancel: {
                    text: 'Não',
                    visible: true,
                },
                confirm: {
                    text: 'Sim, eu quero!',
                    className: 'bg-warning',
                },
            }
        }).then((result) => {
            if (result) {
                window.location.href = el.currentTarget.href;
            }
        });
    });
</script>
@endsection
