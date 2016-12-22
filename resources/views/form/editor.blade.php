<div class="form-group {!! !$errors->has($column) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('docore::form.error')

        <textarea class="form-control" id="{{$id}}" name="{{$name}}" placeholder="{{ trans('docore::lang.input') }} {{$label}}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>

        @include('docore::form.help-block')

    </div>
</div>
