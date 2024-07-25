<input type="hidden" name="mytravel_save_extra" value="1">
<div class="form-group-item">
    <label class="control-label">{{__('Badge tag')}}</label>
    <div class="g-items-header">
        <div class="row">
            <div class="col-md-5">{{__("Title")}}</div>
            <div class="col-md-5">{{__('Color')}}</div>
            <div class="col-md-1"></div>
        </div>
    </div>
    <div class="g-items">
        @if(!empty($translation->badge_tags))
            @foreach($translation->badge_tags as $key=>$item)
                <div class="item" data-number="{{$key}}">
                    <div class="row">
                        <div class="col-md-7">
                            <input type="text" name="badge_tags[{{$key}}][title]" class="form-control" value="{{$item['title']}}" placeholder="{{__('Eg: service VIP')}}">
                        </div>
                        <div class="col-md-4">
                            <select name="badge_tags[{{$key}}][color]" class="form-control">
                                <option @if($item['color'] == "brown") selected @endif value="brown">{{ __("Brown") }}</option>
                                <option @if($item['color'] == "maroon") selected @endif value="maroon">{{ __("Maroon") }}</option>
                                <option @if($item['color'] == "green") selected @endif value="green">{{ __("Green") }}</option>
                                <option @if($item['color'] == "danger") selected @endif value="danger">{{ __("Danger") }}</option>
                                <option @if($item['color'] == "warning") selected @endif value="warning">{{ __("Warning") }}</option>
                                <option @if($item['color'] == "info") selected @endif value="info">{{ __("Info") }}</option>
                                <option @if($item['color'] == "success") selected @endif value="success">{{ __("Success") }}</option>
                                <option @if($item['color'] == "dark") selected @endif value="dark">{{ __("Dark") }}</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <div class="text-right">
        <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
    </div>
    <div class="g-more hide">
        <div class="item" data-number="__number__">
            <div class="row">
                <div class="col-md-7">
                    <input type="text" __name__="badge_tags[__number__][title]" class="form-control" placeholder="{{__('Eg: Service VIP')}}">
                </div>
                <div class="col-md-4">
                    <select __name__="badge_tags[__number__][color]" class="form-control">
                        <option value="brown">{{ __("Brown") }}</option>
                        <option value="maroon">{{ __("Maroon") }}</option>
                        <option value="green">{{ __("Green") }}</option>
                        <option value="danger">{{ __("Danger") }}</option>
                        <option value="warning">{{ __("Warning") }}</option>
                        <option value="info">{{ __("Info") }}</option>
                        <option value="success">{{ __("Success") }}</option>
                        <option value="dark">{{ __("Dark") }}</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>
