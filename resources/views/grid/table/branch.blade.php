@if(!isset($branch['children']))
    <tr {!! $row->getHtmlAttributes() !!} @if($grid->isOrderable()) data-id="{{ $row->id()}}" data-sort="{{ $row->__get('order') }}" @endif>
        <td><input type="checkbox" class="grid-item" data-id="{{ $row->id() }}" /></td>
        @foreach($grid->columnNames as $name)
        <td>{!! $row->column($name) !!}</td>
        @endforeach

        @if($grid->isOrderable())
            <td>
                <div class="btn-group">
                    <button type="button" class="btn btn-xs btn-info grid-order-up" data-id="{{ $row->id() }}"><i class="fa fa-caret-up fa-fw"></i></button>
                    <button type="button" class="btn btn-xs btn-default grid-order-down" data-id="{{ $row->id() }}"><i class="fa fa-caret-down fa-fw"></i></button>
                </div>
            </td>
        @endif

        @if($grid->allowActions())
            <td>
                {!! $row->actions() !!}
            </td>
        @endif
    </tr>
@else
    <li class="dd-item" data-id="{{ $branch['id'] }}">
        <div class="dd-handle">
            <strong>{{ $branch['title'] }}</strong>
            <span class="pull-right action dd-nodrag" data-field-name="_edit">
                <a href="/{{ $path }}/{{ $branch['id'] }}/edit"><i class="fa fa-edit"></i></a>
                <a href="javascript:void(0);" data-id="{{ $branch['id'] }}" class="_delete"><i class="fa fa-trash"></i></a>
            </span>
        </div>
        <ol class="dd-list">
            @foreach($branch['children'] as $branch)
                @include('docore::menu.branch', $branch)
            @endforeach
        </ol>
    </li>
@endif