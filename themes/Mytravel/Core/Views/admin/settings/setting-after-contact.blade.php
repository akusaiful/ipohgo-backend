<div class="form-group">
    <label>{{__("List Contact")}}</label>
    <div class="form-controls">
        <div class="form-group-item">
            <div class="form-group-item">
                <div class="g-items-header">
                    <div class="row">
                        <div class="col-md-4">{{__("Title")}}</div>
                        <div class="col-md-7">{{__('Info Contact')}}</div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
                <div class="g-items">
                    <?php
                    $page_contact_lists = setting_item_with_lang('page_contact_lists',request()->query('lang'));
                    if(!empty($page_contact_lists)) $page_contact_lists = json_decode($page_contact_lists,true);
                    if(empty($page_contact_lists) or !is_array($page_contact_lists))
                        $page_contact_lists = [];
                    ?>
                    @foreach($page_contact_lists as $key=>$item)
                        <div class="item" data-number="{{$key}}">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="page_contact_lists[{{$key}}][title]" class="form-control" value="{{$item['title']}}">
                                </div>
                                <div class="col-md-7">
                                    <label for="">{{ __("Address") }}</label>
                                    <input type="text" name="page_contact_lists[{{$key}}][address]" class="form-control" value="{{$item['address']}}">
                                    <label for="">{{ __("Phone") }}</label>
                                    <input type="text" name="page_contact_lists[{{$key}}][phone]" class="form-control" value="{{$item['phone']}}">
                                    <label for="">{{ __("Email") }}</label>
                                    <input type="text" name="page_contact_lists[{{$key}}][email]" class="form-control" value="{{$item['email']}}">
                                    <label for="">{{ __("Link View on Map") }}</label>
                                    <input type="text" name="page_contact_lists[{{$key}}][link_map]" class="form-control" value="{{$item['link_map']}}">
                                </div>
                                <div class="col-md-1">
                                    <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-right">
                    <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i> {{__('Add item')}}</span>
                </div>
                <div class="g-more hide">
                    <div class="item" data-number="__number__">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="">{{ __("Address") }}</label>
                                <input type="text" __name__="page_contact_lists[__number__][title]" class="form-control" value="">
                            </div>
                            <div class="col-md-7">
                                <label for="">{{ __("Address") }}</label>
                                <input type="text" __name__="page_contact_lists[__number__][address]" class="form-control" value="">
                                <label for="">{{ __("Phone") }}</label>
                                <input type="text" __name__="page_contact_lists[__number__][phone]" class="form-control" value="">
                                <label for="">{{ __("Email") }}</label>
                                <input type="text" __name__="page_contact_lists[__number__][email]" class="form-control" value="">
                                <label for="">{{ __("Link View on Map") }}</label>
                                <input type="text" __name__="page_contact_lists[__number__][link_map]" class="form-control" value="">
                            </div>
                            <div class="col-md-1">
                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label class="">{{__("Iframe google map")}}</label>
    <div class="form-controls">
        <input type="text" class="form-control" name="page_contact_iframe_google_map" value="{{ setting_item_with_lang('page_contact_iframe_google_map',request()->query('lang')) }}">
    </div>
</div>