<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-6">

        @include('docore::form.error')

        @foreach($values as $option => $label)
            <input type="radio" name="{{$name}}" value="{{$option}}" class="minimal {{$id}}" {{ ($option == old($column, $value))?'checked':'' }} />&nbsp;{{$label}}&nbsp;&nbsp;
        @endforeach

        @include('docore::form.help-block')

    </div>
</div>
