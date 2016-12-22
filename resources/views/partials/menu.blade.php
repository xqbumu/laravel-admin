@if(Docore::user()->visible($item['roles']))
    @if(!isset($item['children']))
        <li>
            <a href="{{ Docore::url($item['uri']) }}"><i class="fa {{$item['icon']}}"></i>
                <span>{{$item['title']}}</span>
            </a>
        </li>
    @else
        <li class="treeview">
            <a href="#">
                <i class="fa {{$item['icon']}}"></i>
                <span>{{$item['title']}}</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                @foreach($item['children'] as $item)
                    @include('docore::partials.menu', $item)
                @endforeach
            </ul>
        </li>
    @endif
@endif