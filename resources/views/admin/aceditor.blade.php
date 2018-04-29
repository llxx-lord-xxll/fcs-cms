<style type="text/css">

</style>

<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-8">

        @include('admin::form.error')


        <textarea class="form-control {{$class}}text" name="{{$name}}" style="display: none;" placeholder="{{ $placeholder }}" {!! $attributes !!} >{{ old($column, $value) }}</textarea>
        <div id="{{$class}}"  class="{{$class}}" ></div>

        @include('admin::form.help-block')

    </div>
</div>

