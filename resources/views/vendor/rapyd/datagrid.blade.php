<div class="rpd-datagrid">
    @include('rapyd::toolbar', array('label'=>$label, 'buttons_right'=>$buttons['TR']))
    
    <div class="table-responsive">
        <table{!! $dg->buildAttributes() !!}>
            <thead>
            <tr>
                @foreach ($dg->columns as $column)
                    <th{!! $column->buildAttributes() !!}>
                        {!! $column->label !!}
                        @if ($column->orderby)
                            @if ($dg->onOrderby($column->orderby_field, 'asc'))
                                <span style="font-size:10px" class="glyphicon glyphicon-chevron-up"></span>
                            @else
                                <a href="{{ $dg->orderbyLink($column->orderby_field,'asc') }}">
                                    <span style="font-size:10px" class="glyphicon glyphicon-chevron-up"></span>
                                </a>
                            @endif
                            @if ($dg->onOrderby($column->orderby_field, 'desc'))
                                <span style="font-size:10px" class="glyphicon glyphicon-chevron-down"></span>
                            @else
                                <a href="{{ $dg->orderbyLink($column->orderby_field,'desc') }}">
                                    <span style="font-size:10px" class="glyphicon glyphicon-chevron-down"></span>
                                </a>
                            @endif
                        @endif
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @if (count($dg->rows) == 0)
                <tr><td colspan="{!! count($dg->columns) !!}">{!! trans('rapyd::rapyd.no_records') !!}</td></tr>
            @endif
            @foreach ($dg->rows as $row)
                <tr{!! $row->buildAttributes() !!}>
                    @foreach ($row->cells as $cell)
                        <td{!! $cell->buildAttributes() !!}>{!! $cell->value !!}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="btn-toolbar" role="toolbar">
        @if ($dg->havePagination())
            <div class="pull-left">
                {!! $dg->links() !!}
            </div>
            <div class="pull-right rpd-total-rows">
                @if($dg->totalRows() > 1)
                Showing {!! $dg->totalRows() !!} entries
                @elseif($dg->totalRows() > 0)
                Showing {!! $dg->totalRows() !!} entry
                @endif
            </div>
        @endif
    </div>
</div>

