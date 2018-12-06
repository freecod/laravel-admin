<div class="{{$viewClass['form-group']}} {!! !$errors->has($column) ?: 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}" id="{{$id}}">

        @include('admin::form.error')

        <div class="input-group">
            <div class="checkbox">
                <label class="checkbox-inline" style="cursor: pointer; margin-top: -6px;">
                    <input type="checkbox" id="check_{{$name}}" value="1" {{ old($column, $value) ? 'checked' : '' }} class="form-control {{$class}}" autocomplete="off"/>
                </label>
            </div>
        </div>

        <input type="hidden" class="{{ $class }}" name="{{$name}}" value="{{ old($column, $value) ? '1' : '0' }}">

        @include('admin::form.help-block')

    </div>
</div>