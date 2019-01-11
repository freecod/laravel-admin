<div class="box-footer">

    {{ csrf_field() }}

    <div class="col-md-{{$width['label']}}">
    </div>

    <div class="col-md-{{$width['field']}}">

        @if(in_array('submit', $buttons))
        <div class="btn-group pull-right">
            <button type="submit" class="btn btn-primary">{{ trans('admin.submit') }}</button>
        </div>

        @if(in_array('continue_editing', $checkboxes))
            <div style="margin: 0 5px 0 0;" class="btn-group pull-right" onclick="$(this).closest('form').attr('action', $(this).closest('form').attr('action') + '?after-save=1');">
                <button type="submit" class="btn btn-primary">{{ trans('admin.submit_and_continue') }}</button>
            </div>
        @endif


        @if(in_array('view', $checkboxes))
        <label class="pull-right" style="margin: 5px 10px 0 0;">
            <input type="checkbox" class="after-submit" name="after-save" value="2"> {{ trans('admin.view') }}
        </label>
        @endif

        @endif

        @if(in_array('reset', $buttons))
        <div class="btn-group pull-left">
            <button type="reset" class="btn btn-warning">{{ trans('admin.reset') }}</button>
        </div>
        @endif
    </div>
</div>