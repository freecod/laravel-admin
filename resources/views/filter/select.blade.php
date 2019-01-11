<select class="form-control {{ $class }}" name="{{$name}}" style="width: 100%;">
    <option></option>
    @foreach($options as $select => $option)
        <option value="{{$select}}" {{ $select == $selected ?'selected':'' }}> {{$option}}</option>
    @endforeach
</select>