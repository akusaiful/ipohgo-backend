@if(is_default_lang())
    <div class="form-group">
        <label>{{__("Logo Color")}}</label>
        <div class="form-controls form-group-image">
            {!! \Modules\Media\Helpers\FileHelper::fieldUpload('logo_id_2',setting_item('logo_id_2')) !!}
        </div>
    </div>
@endif
<div class="form-group">
    <label>{{__("Logo Text")}}</label>
    <div class="form-controls">
        <input type="text" class="form-control" name="logo_text" value="{{ setting_item_with_lang('logo_text',request()->query('lang')) }}">
    </div>
</div>