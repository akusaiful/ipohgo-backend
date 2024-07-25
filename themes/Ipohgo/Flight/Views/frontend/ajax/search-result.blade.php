<div class="list-item">
    <div class="row">
        @if($rows->total() > 0)
            @foreach($rows as $row)
                <div class="col-md-6 col-xl-4">
                    @include('Flight::frontend.layouts.search.loop-grid',['wrap_class'=>'item-loop-wrap'])
                </div>
            @endforeach
        @else
            <div class="col-lg-12">
                {{__("Flight not found")}}
            </div>
        @endif
    </div>
</div>
<div class="bravo-pagination">
    @if($rows->total() > 0)
        <div class="text-center text-md-left font-size-14 mb-3 text-lh-1">{{ __("Showing :from - :to of :total flights",["from"=>$rows->firstItem(),"to"=>$rows->lastItem(),"total"=>$rows->total()]) }}</div>
    @endif
    {{$rows->appends(request()->query())->links()}}
</div>
