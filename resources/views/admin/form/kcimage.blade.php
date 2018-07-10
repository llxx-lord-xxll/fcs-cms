<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="col-sm-8">

        @include('admin::form.error')

        <input class="form-control {{$class}}" id="{{$id}}" name="{{$name}}" {!! $attributes !!} type="text" value="{{ old($column, $value) }}" readonly="readonly" placeholder="Click here and select a file double clicking on it" style="width:600px;cursor:pointer" />

        @include('admin::form.help-block')

    </div>
</div>
