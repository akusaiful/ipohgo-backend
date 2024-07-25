<div class="bravo-form-search-all slider-carousel hero-block hero-v1 bg-img-hero-bottom gradient-overlay-half-black-gradient text-center z-index-2">
    <div class="effect ">
        <div class="owl-carousel">
            @foreach($list_slider as $item)
                @php $img = get_file_url($item['bg_image'],'full') @endphp
                <div class="item" style="background-image: linear-gradient(0deg,rgba(0, 0, 0, 0.2),rgba(0, 0, 0, 0.2)),url('{{$img}}') !important">
                    <div class="pb-10">
                        <h1 class="font-size-60 font-size-xs-30 text-white font-weight-bold">{{ $item['title'] ?? "" }}</h1>
                        <p class="font-size-20 font-weight-normal text-white">{{ $item['desc'] ?? "" }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="pb-300"></div>
    <div class="container space-2 space-top-xl-4">
        @if(empty($hide_form_search))
            <div class="mb-lg-n1">
                <ul class="nav tab-nav-rounded flex-nowrap pb-2 pb-md-4 tab-nav @if(!empty($single_form_search)) d-none @endif" role="tablist">
                    @if(!empty($service_types))
                        @php $number = 0; @endphp
                        @foreach ($service_types as $service_type)
                            @php
                                $allServices = get_bookable_services();
                                if(empty($allServices[$service_type])) continue;
                                $module = new $allServices[$service_type];
                            @endphp
                            <li class="nav-item" role="bravo_{{$service_type}}">
                                <a class="nav-link font-weight-medium @if($number == 0) active @endif pl-md-5 pl-3" id="bravo_{{$service_type}}-tab" data-toggle="pill" href="#bravo_{{$service_type}}" role="tab" aria-controls="bravo_{{$service_type}}" aria-selected="true">
                                    <div class="d-flex flex-column flex-md-row  position-relative text-white align-items-center">
                                        <figure class="ie-height-40 d-md-block mr-md-3">
                                            <i class="icon {{ $module->getServiceIconFeatured() }} font-size-3"></i>
                                        </figure>
                                        <span class="tabtext mt-2 mt-md-0 font-weight-semi-bold">
                                              {{ !empty($modelBlock["title_for_".$service_type]) ? $modelBlock["title_for_".$service_type] : $module->getModelName() }}
                                        </span>
                                    </div>
                                </a>
                            </li>
                            @php $number++; @endphp
                        @endforeach
                    @endif
                </ul>
                <div class="tab-content hero-tab-pane">
                    @if(!empty($service_types))
                        @php $number = 0; @endphp
                        @foreach ($service_types as $service_type)
                            @php
                                $allServices = get_bookable_services();
                                if(empty($allServices[$service_type])) continue;
                            @endphp
                            <div class="tab-pane fade @if($number == 0) active show @endif" id="bravo_{{$service_type}}" role="tabpanel" aria-labelledby="bravo_{{$service_type}}-tab">
                                <div class="card border-0 tab-shadow">
                                    <div class="card-body">
                                        @include(ucfirst($service_type).'::frontend.layouts.search.form-search')
                                    </div>
                                </div>
                            </div>
                            @php $number++; @endphp
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
