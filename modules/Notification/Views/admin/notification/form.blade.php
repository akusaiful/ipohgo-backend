<div class="form-group">
    <label>{{ __('Title') }}</label>
    <input type="text" value="{{ old('title', $translation->title) }}" placeholder="Notification title" name="title"
        class="form-control">
</div>
<div class="form-group">
    <label class="control-label">{{ __('Preview Notication') }} - Notication Center/Popup box </label>
    <div class="">
        <textarea class="form-control" name="preview" cols="30" rows="5">{{ old('preview', $translation->preview) }}</textarea>
    </div>
</div>
<div class="form-group">
    <label class="control-label">{{ __('Detail Content') }} </label>
    <div class="">
        <textarea name="body" class="d-none has-ckeditor" cols="30" rows="10">{{ old('body', $translation->body) }}</textarea>
    </div>
</div>
