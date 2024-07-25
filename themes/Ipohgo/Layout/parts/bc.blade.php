@if(!empty($breadcrumbs))
    <div class="container">
        <nav class="py-3" aria-label="breadcrumb">
            <ol class="breadcrumb breadcrumb-no-gutter mb-0 flex-nowrap flex-xl-wrap overflow-auto overflow-xl-visble" itemscope itemtype="https://schema.org/BreadcrumbList">
                <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"><a href="{{url('/')}}" itemprop="item"><span itemprop="name">{{__('Home')}}</span></a>
                    <meta itemprop="position" content="1" /></li>
                @foreach($breadcrumbs as $k=>$breadcrumb)
                    <li class="breadcrumb-item flex-shrink-0 flex-xl-shrink-1 {{$breadcrumb['class'] ?? ''}}" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        @if(!empty($breadcrumb['url']))
                            <a href="{{url($breadcrumb['url'])}}" itemscope itemtype="https://schema.org/WebPage" itemprop="item" itemid="{{url($breadcrumb['url'])}}"><span itemprop="name">{{$breadcrumb['name']}}</span></a>
                        @else
                            <span itemprop="name">{{$breadcrumb['name']}}</span>
                        @endif
                        <meta itemprop="position" content="{{$k + 2}}" />
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>
@endif
