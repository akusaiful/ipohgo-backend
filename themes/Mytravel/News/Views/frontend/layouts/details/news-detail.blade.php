@php $translation = $row->translate(); @endphp
<div class="mb-4">
    {{-- @if($row->getGallery())
        <div class="position-relative mb-5">
            <div id="sliderSyncingNav" class="travel-slick-carousel u-slick mb-2"
                 data-infinite="true"
                 data-arrows-classes="d-none d-lg-inline-block u-slick__arrow-classic u-slick__arrow-centered--y rounded-circle"
                 data-arrow-left-classes="flaticon-back u-slick__arrow-classic-inner u-slick__arrow-classic-inner--left ml-lg-2 ml-xl-4"
                 data-arrow-right-classes="flaticon-next u-slick__arrow-classic-inner u-slick__arrow-classic-inner--right mr-lg-2 mr-xl-4"
                 data-nav-for="#sliderSyncingThumb">
                @foreach($row->getGallery() as $key=>$item)
                    <div class="js-slide">
                        <img class="img-fluid border-radius-3" src="{{$item['large']}}" alt="{{ __("Gallery") }}">
                    </div>
                @endforeach
            </div>
            @if(!empty($row->video))
                <div class="position-absolute right-0 mr-3 mt-md-n11 mt-n9">
                    <div class="flex-horizontal-center">
                        <a class="travel-fancybox btn btn-white transition-3d-hover py-2 px-md-5 px-3 shadow-6 mr-1" href="javascript:;"
                           data-src="{{ handleVideoUrl($row->video) }}"
                           data-speed="700">
                            <i class="flaticon-movie mr-md-2 font-size-18 text-primary"></i>
                            <span class="d-none d-md-inline">{{ __("Video") }}</span>
                        </a>
                    </div>
                </div>
            @endif
            <div id="sliderSyncingThumb" class="travel-slick-carousel u-slick u-slick--gutters-4 u-slick--transform-off"
                 data-infinite="true"
                 data-slides-show="6"
                 data-is-thumbs="true"
                 data-nav-for="#sliderSyncingNav"
                 data-responsive='[{
                                    "breakpoint": 992,
                                    "settings": {
                                        "slidesToShow": 4
                                    }
                                }, {
                                    "breakpoint": 768,
                                    "settings": {
                                        "slidesToShow": 3
                                    }
                                }, {
                                    "breakpoint": 554,
                                    "settings": {
                                        "slidesToShow": 2
                                    }
                                }]'>
                @foreach($row->getGallery() as $key=>$item)
                    <div class="js-slide" style="cursor: pointer;">
                        <img class="img-fluid border-radius-3 height-110" src="{{$item['thumb']}}" alt="{{ __("Gallery") }}">
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($image_tag = get_image_tag($row->image_id,'full',['alt'=>$translation->title,'class'=>'img-fluid mb-4 rounded-xs w-100']))
        <a class="d-block" href="{{$row->getDetailUrl()}}">
            {!! $image_tag !!}
        </a>
    @endif --}}
    <h5 class="font-weight-bold font-size-21 text-gray-3">
        <a href="{{$row->getDetailUrl()}}">{!! clean($translation->title) !!}</a>
    </h5>
    <div class="mb-3">
        <a class="mr-3 pr-1" href="#">
            <span class="font-weight-normal text-gray-3">{{ display_date($row->updated_at)}}</span>
        </a>
        @php $category = $row->category; @endphp
        @if(!empty($category))
            @php $t = $category->translate(); @endphp
            <a href="{{$category->getDetailUrl(app()->getLocale())}}">
                <span class="font-weight-normal">{{$t->name ?? ''}}</span>
            </a>
        @endif
    </div>
    <p class="mb-0 text-lh-lg">
        {!! $translation->content !!}
    </p>
    <div class="space-between">
        @if (!empty($tags = $row->getTags()) and count($tags) > 0)
            <div class="tags">
                {{__("Tags:")}}
                @foreach($tags as $tag)
                    @php $t = $tag->translate(); @endphp
                    <a href="{{ $tag->getDetailUrl(app()->getLocale()) }}" class="tag-item"> {{$t->name ?? ''}} </a>
                @endforeach
            </div>
        @endif
        {{-- <div class="share"> {{__("Share")}}
            <a class="facebook share-item" href="https://www.facebook.com/sharer/sharer.php?u={{$row->getDetailUrl()}}&amp;title={{$translation->title}}" target="_blank" original-title="{{__("Facebook")}}"><i class="fa fa-facebook fa-lg"></i></a>
            <a class="twitter share-item" href="https://twitter.com/share?url={{$row->getDetailUrl()}}&amp;title={{$translation->title}}" target="_blank" original-title="{{__("Twitter")}}"><i class="fa fa-twitter fa-lg"></i></a>
        </div> --}}
    </div>
</div>
