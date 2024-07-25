<div class="row">
    <div class="col-lg-3 col-md-12">
        @include('Event::frontend.layouts.search.filter-search')
    </div>
    <div class="col-lg-9 col-md-12">
        <div class="bravo-list-item">
            <div class="d-flex justify-content-between align-items-center mb-4 topbar-search">
                <h3 class="font-size-21 font-weight-bold mb-0 text-lh-1">
                    @if($rows->total() > 1)
                        {{ __(":count events found",['count'=>$rows->total()]) }}
                    @else
                        {{ __(":count event found",['count'=>$rows->total()]) }}
                    @endif
                </h3>
                <div class="control bc-form-order">
                    @include('Layout::global.search.orderby',['routeName'=>'event.search'])
                </div>
            </div>
            <div class="ajax-search-result">
                @include('Event::frontend.ajax.search-result')
            </div>
        </div>
    </div>
</div>
