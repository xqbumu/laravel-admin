<div class="form-group {!! !$errors->has($column) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('docore::form.error')

        <div class="input-group {{$class}}">
            <span class="input-group-addon"><i></i></span>
            <input type="text" name="{{$name}}" value="{{ old($column, $value) }}" class="form-control" placeholder="{{ trans('docore::lang.input') }} {{ $label }}" {!! $attributes !!}  style="width: 140px" />
        </div>

        @include('docore::form.help-block')

    </div>
</div>
