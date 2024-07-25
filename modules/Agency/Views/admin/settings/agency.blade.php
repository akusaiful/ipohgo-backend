<div class="row">
    <div class="col-sm-4">
        <h3 class="form-group-title">{{__("Page Search")}}</h3>
        <p class="form-group-desc">{{__('Config page search of your website')}}</p>
    </div>
    <div class="col-sm-8">
        <div class="panel">
            <div class="panel-title"><strong>{{__("General Options")}}</strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="" >{{__("Title Page")}}</label>
                    <div class="form-controls">
                        <input type="text" name="agency_page_search_title" value="{{setting_item_with_lang('agency_page_search_title',request()->query('lang'))}}" class="form-control">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if(is_default_lang())
    <hr>
    <div class="row">
        <div class="col-sm-4">
            <h3 class="form-group-title">{{__("Review Options")}}</h3>
            <p class="form-group-desc">{{__('Config review for agency')}}</p>
        </div>
        <div class="col-sm-8">
            <div class="panel">
                <div class="panel-body">
                    <div class="form-group">
                        <label class="" >{{__("Enable review system for Agency?")}}</label>
                        <div class="form-controls">
                            <label><input type="checkbox" name="agency_enable_review" value="1" @if(!empty(setting_item('agency_enable_review'))) checked @endif /> {{__("Yes, please enable it")}} </label>
                            <br>
                            <small class="form-text text-muted">{{__("Turn on the mode for reviewing agency")}}</small>
                        </div>
                    </div>
                    <div class="form-group" data-condition="agency_enable_review:is(1)">
                        <label class="" >{{__("Review must be approval by admin")}}</label>
                        <div class="form-controls">
                            <label><input type="checkbox" name="agency_review_approved" value="1"  @if(!empty(setting_item('agency_review_approved'))) checked @endif /> {{__("Yes please")}} </label>
                            <br>
                            <small class="form-text text-muted">{{__("ON: Review must be approved by admin - OFF: Review is automatically approved")}}</small>
                        </div>
                    </div>
                    <div class="form-group" data-condition="agency_enable_review:is(1)">
                        <label class="" >{{__("Review number per page")}}</label>
                        <div class="form-controls">
                            <input type="number" class="form-control" name="agency_review_number_per_page" value="{{ setting_item('agency_review_number_per_page') ?? 5 }}" />
                            <small class="form-text text-muted">{{__("Break comments into pages")}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

