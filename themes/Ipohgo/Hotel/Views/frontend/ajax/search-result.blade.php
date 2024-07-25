<div class="list-item">
    <div class="row">
        @if($rows->total() > 0)
            @foreach($rows as $row)
                @php $layout = $layout ?? setting_item("hotel_layout_item_search",'list') @endphp
                @if($layout == "list")
                    <div class="col-lg-12 col-md-12">
                        @include('Hotel::frontend.layouts.search.loop-list',['wrap_class'=>'inner-loop-wrap'])
                    </div>
                @else
                    <div class="col-lg-4 col-md-12">
                        @include('Hotel::frontend.layouts.search.loop-grid',['wrap_class'=>'mb-3 item-loop-wrap'])
                    </div>
                @endif
            @endforeach
        @else
            <div class="col-lg-12">
                {{__("Hotel not found")}}
            </div>
        @endif
    </div>
</div>
<div class="bravo-pagination">
    @if($rows->total() > 0)
        <div class="text-center text-md-left font-size-14 mb-3 text-lh-1">{{ __("Showing :from - :to of :total hotels",["from"=>$rows->firstItem(),"to"=>$rows->lastItem(),"total"=>$rows->total()]) }}</div>
    @endif
    {{$rows->appends(request()->except(['_ajax']))->links()}}
</div>
