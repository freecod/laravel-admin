<div class="{{$viewClass['form-group']}} {!! !$errors->has($column) ?: 'has-error' !!}">

    <label for="{{$id['address']}}" class="{{$viewClass['label']}} control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}" id="{{$id['address']}}">

        @include('admin::form.error')

        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-map fa-fw"></i></span>
            <input name="{{$name['address']}}" class="form-control {{ $class['address'] }}" placeholder="{{ $placeholder }}" type="text" size="100" {!! $attributes !!} value="{{ old($column['address'], $value['address']) }}"/>
            @if(isset($id['lat']))
                <input type="hidden" id="{{$id['lat']}}" name="{{$name['lat']}}" value="{{ old($column['lat'], $value['lat']) }}" {!! $attributes !!} />
            @endif
            @if(isset($id['lon']))
                <input type="hidden" id="{{$id['lon']}}" name="{{$name['lon']}}" value="{{ old($column['lon'], $value['lon']) }}" {!! $attributes !!} />
            @endif

        </div>

        @include('admin::form.help-block')
    </div>
</div>