<div class="form-group {!! !$errors->has($column) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('docore::form.error')

        <div class="input-group">
            <div class="input-group-addon">
                <i class="fa fa-eye-slash"></i>
            </div>
            <input type="password" id="{{$id}}" name="{{$name}}" value="{{ old($column, $value) }}" class="form-control" placeholder="{{ trans('docore::lang.input') }} {{$label}}" {!! $attributes !!} />
        </div>

        @include('docore::form.help-block')

    </div>
</div>
