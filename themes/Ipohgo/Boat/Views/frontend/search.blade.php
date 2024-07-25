@extends('layouts.app')
@push('css')
    <link href="{{ asset('/themes/ipohgo/dist/frontend/module/boat/css/boat.css?_ver='.config('app.asset_version')) }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset("/themes/ipohgo/libs/ion_rangeslider/css/ion.rangeSlider.min.css") }}"/>
@endpush
@section('content')
    <div class="bravo_search_boat">
        <div class="container">
            @include('Boat::frontend.layouts.search.list-item')
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript" src="{{ asset("/themes/ipohgo/libs/ion_rangeslider/js/ion.rangeSlider.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset('js/filter.js?_ver='.config('app.asset_version')) }}"></script>
    <script type="text/javascript" src="{{ asset('/themes/ipohgo/module/boat/js/boat.js?_ver='.config('app.asset_version')) }}"></script>
@endpush
