<div class="row  pt-5 pt-xl-8 mb-5 mb-xl-9 pb-xl-1">
    <div class="col-lg-3 col-md-12">
        @include('Flight::frontend.layouts.search.filter-search')
    </div>
    <div class="col-lg-9 col-md-12">
        <div class="bravo-list-item">
            <div class="d-flex justify-content-between align-items-center mb-4 topbar-search">
                <h3 class="font-size-21 font-weight-bold mb-0 text-lh-1">
                    @if($rows->total() > 1)
                        {{ __(":count flights found",['count'=>$rows->total()]) }}
                    @else
                        {{ __(":count flight found",['count'=>$rows->total()]) }}
                    @endif
                </h3>
                <div class="control bc-form-order">
                    @include('Layout::global.search.orderby',['routeName'=>'flight.search'])
                </div>
            </div>
        </div>
        <div class="ajax-search-result" id="flightFormBook">
            @include('Flight::frontend.ajax.search-result')
        </div>
        @include('Flight::frontend.layouts.search.modal-form-book')
    </div>
</div>
