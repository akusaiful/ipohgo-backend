<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__('Other Settings')}}</h3>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-body">
                <div class="form-group">
                    <label class="" >{{__("Why Book With Us?")}}</label>
                </div>
                <div class="form-group">
                    <div class="form-group-item">
                        <div class="form-group-item">
                            <div class="g-items-header">
                                <div class="row">
                                    <div class="col-md-3">{{__("Title")}}</div>
                                    <div class="col-md-8">{{__('Class icon')}}</div>
                                    <div class="col-md-1"></div>
                                </div>
                            </div>
                            <div class="g-items">
                                @php $booking_why_book_with_us = setting_item_array('booking_why_book_with_us',[]); @endphp
                                @foreach($booking_why_book_with_us as $key=>$item)
                                    <div class="item" data-number="{{$key}}">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <label>{{__("Title")}}</label>
                                                <div>
                                                    <input type="text" name="booking_why_book_with_us[{{$key}}][title]" placeholder="{{ __("Customer care available 24/7") }}" class="form-control" value="{{$item['title'] ?? ""}}">
                                                    <input type="text" name="booking_why_book_with_us[{{$key}}][link]" placeholder="{{ __("#") }}" class="form-control" value="{{$item['link'] ?? ""}}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>{{__("Icon")}}</label>
                                                <div>
                                                    <input type="text" name="booking_why_book_with_us[{{$key}}][icon]"placeholder="fa fa-phone" class="form-control" value="{{$item['icon'] ?? ""}}">
                                                </div>
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
                                        <div class="col-md-7">
                                            <label>{{__("Title - Link info")}}</label>
                                            <div>
                                                <input type="text" __name__="booking_why_book_with_us[__number__][title]" placeholder="{{ __("Customer care available 24/7") }}" class="form-control" value="">
                                                <input type="text" __name__="booking_why_book_with_us[__number__][link]" placeholder="{{ __("#") }}" class="form-control" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>{{__("Icon")}}</label>
                                            <div>
                                                <input type="text" __name__="booking_why_book_with_us[__number__][icon]"placeholder="fa fa-phone" class="form-control" value="">
                                            </div>
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
        </div>
    </div>
</div>
