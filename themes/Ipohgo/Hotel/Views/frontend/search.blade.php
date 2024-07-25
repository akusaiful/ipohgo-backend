@extends('layouts.app')
@push('css')
    <link href="{{ asset('themes/ipohgo/dist/frontend/module/hotel/css/hotel.css?_ver='.config('app.asset_version')) }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset("themes/ipohgo/libs/ion_rangeslider/css/ion.rangeSlider.min.css") }}"/>
@endpush
@section('content')
    <div class="bravo_search_hotel mt-7">
        <div class="container">
            @include('Hotel::frontend.layouts.search.list-item')
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript" src="{{ asset("themes/ipohgo/libs/ion_rangeslider/js/ion.rangeSlider.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset('js/filter.js?_ver='.config('app.asset_version')) }}"></script>
    <script type="text/javascript" src="{{ asset('themes/ipohgo/module/hotel/js/hotel.js?_ver='.config('app.asset_version')) }}"></script>
@endpush
