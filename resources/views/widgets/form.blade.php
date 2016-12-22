<form {!! $attributes !!}>
    <div class="box-body">

        @foreach($fields as $field)
            {!! $field->render() !!}
        @endforeach

    </div>

    <!-- /.box-body -->
    <div class="box-footer">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-2">
            <div class="btn-group pull-left">
                <button type="reset" class="btn btn-warning pull-right">{{ trans('docore::lang.reset') }}</button>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="btn-group pull-right">
                <button type="submit" class="btn btn-info pull-right">{{ trans('docore::lang.submit') }}</button>
            </div>
        </div>

    </div>
</form>