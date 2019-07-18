<div class="box-footer">

    {{ csrf_field() }}

    <div class="col-md-{{$width['label']}}">
    </div>

    <div class="col-md-{{$width['field']}}">

        @if(in_array('submit', $buttons))
        <div class="btn-group pull-right">
            <button type="submit" class="btn btn-primary">{{ trans('admin.submit_and_close') }}</button>
        </div>

        <input type="hidden" name="after-save" value="">

        @if(in_array('continue_editing', $checkboxes))
        <div style="margin: 0 5px 0 0;" class="btn-group pull-right" onclick="$('input[name = after-save]').val(1);">
            <button type="submit" class="btn btn-primary">{{ trans('admin.submit_and_edit') }}</button>
        </div>
        @endif

        @if(in_array('continue_creating', $checkboxes))
        <div style="margin: 0 5px 0 0;" class="btn-group pull-right" onclick="$('input[name = after-save]').val(2);">
            <button type="submit" class="btn btn-primary">{{ trans('admin.submit_and_create') }}</button>
        </div>
        @endif

        @if(in_array('view', $checkboxes))
        <div style="margin: 0 5px 0 0;" class="btn-group pull-right" onclick="$('input[name = after-save]').val(3);">
            <button type="submit" class="btn btn-primary">{{ trans('admin.submit_and_view') }}</button>
        </div>
        @endif
        @foreach($submit_redirects as $value => $redirect)
            @if(in_array($redirect, $checkboxes))
            <label class="pull-right" style="margin: 5px 10px 0 0;">
                <input type="checkbox" class="after-submit" name="after-save" value="{{ $value }}" {{ ($default_check == $redirect) ? 'checked' : '' }}> {{ trans("admin.{$redirect}") }}
            </label>
            @endif
        @endforeach

        @endif

        @if(in_array('reset', $buttons))
        <div class="btn-group pull-left">
            <button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
        </div>
        @endif
    </div>
</div>