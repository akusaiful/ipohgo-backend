@extends('layouts.app')
@push('css')
    <link href="{{ asset('themes/ipohgo/dist/frontend/module/flight/css/flight.css?_ver='.config('app.asset_version')) }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset("themes/ipohgo/libs/ion_rangeslider/css/ion.rangeSlider.min.css") }}"/>
@endpush
@section('content')
    <div class="bravo_search_flight">
        <div class="bg-gray-33 py-1">
            <div class="container">
                <div class="border-0">
                    <div class="card-body pl-0 pr-0">
                        @includeIf('Flight::frontend.layouts.search.form-search')
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            @include('Flight::frontend.layouts.search.list-item')
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript" src="{{ asset("themes/ipohgo/libs/ion_rangeslider/js/ion.rangeSlider.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset('js/filter.js?_ver='.config('app.asset_version')) }}"></script>
    <script type="text/javascript" src="{{ asset('themes/ipohgo/module/flight/js/flight.js?_ver='.config('app.asset_version')) }}"></script>
    <script>
        $(document).ready(function () {
            $.BCoreModal.init('[data-modal-target]');
        })
    </script>
@endpush
