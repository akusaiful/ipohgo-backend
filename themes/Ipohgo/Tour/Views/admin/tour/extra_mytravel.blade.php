<?php
if(!is_default_lang()) return;
?>
<input type="hidden" name="mytravel_save_extra" value="1">
{{-- <div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label class="control-label">{{__("Date From-To")}}</label>
            <input type="text" name="date_form_to" class="form-control" value="{{$row->date_form_to}}" placeholder="{{__("Date From-To")}}">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label class="control-label">{{__("Min age")}}</label>
            <input type="text" name="min_age" class="form-control" value="{{$row->min_age}}" placeholder="{{__("Min age")}}">
        </div>
    </div>
</div> --}}
<div class="row">
    {{-- <div class="col-lg-6">
        <div class="form-group">
            <label class="control-label">{{__("Pickup")}}</label>
            <input type="text" name="pickup" class="form-control" value="{{$row->pickup}}" placeholder="{{__("Pickup")}}">
        </div>
    </div> --}}
    <div class="col-lg-6">
        <div class="form-group">
            <label class="control-label">{{__("Wifi available")}}</label> <br>
            <input type="checkbox" name="wifi_available" @if($row->wifi_available) checked @endif value="1"> {{__("Enable featured")}}
        </div>
        <div class="form-group">
            <label class="control-label">{{__("Parking")}}</label> <br>
            <input type="checkbox" name="paid_parking" @if($row->paid_parking) checked @endif value="1"> {{__("Paid Parking")}}
            <input type="checkbox" name="free_parking" @if($row->free_parking) checked @endif value="1"> {{__("Free Parking (Limited)")}}
        </div>
        
    </div>
   
</div>
