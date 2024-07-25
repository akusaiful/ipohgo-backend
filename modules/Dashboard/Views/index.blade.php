@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="dashboard-page">
            <h4 class="welcome-title text-uppercase">{{__('Welcome :name!',['name'=>Auth::user()->nameOrEmail])}}</h4>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-3 col-md-3">
                <div class="dashboard-report-card card purple">
                    <div class="card-content">
                        <span class="card-title">Total User</span>
                        <span class="card-amount">{{ $total_user }}</span>
                        <span class="card-desc">Total registered by {{ date('d-m-Y G:i:s A') }}</span>
                    </div>
                    <div class="card-media">
                        <img src="{{ asset('images/user.png') }}" width="50px" alt="">
                    </div>
                </div>
            </div>

            <div class="col-sm-3 col-md-3">
                <div class="dashboard-report-card card bg-danger">
                    <div class="card-content">
                        <span class="card-title">POI</span>
                        <span class="card-amount">{{ $total_poi }}</span>
                        <span class="card-desc">Attractive place</span>
                    </div>
                    <div class="card-media">
                        <img src="{{ asset('images/placeholder.png') }}" width="50px" alt="">
                    </div>
                </div>
            </div>

            <div class="col-sm-3 col-md-3">
                <div class="dashboard-report-card card info">
                    <div class="card-content">
                        <span class="card-title">Review</span>
                        <span class="card-amount">{{ $total_review }}</span>
                        <span class="card-desc">Latest review received | <img src="{{ asset('images/message.png') }}" width="15px" alt=""> {{ $total_review_spam }} Spam marked</span>
                    </div>
                    <div class="card-media">
                        <img src="{{ asset('images/review.png') }}" width="50px" alt="">
                    </div>
                </div>
            </div>

            <div class="col-sm-3 col-md-3">
                <div class="dashboard-report-card card success">
                    <div class="card-content">
                        <span class="card-title">Device</span>
                        <span class="card-amount">{{ $total_device }}</span>
                        <span class="card-desc">Total install and open apps</span>
                    </div>
                    <div class="card-media">
                        <img src="{{ asset('images/smartphone.png') }}" width="50px" alt="">
                    </div>
                </div>
            </div>
                
        </div>

        <div class="row">

            <div class="col-sm-6 col-md-6">
                <div class="dashboard-report-card card bg-dark">
                    <div class="card-content">
                        <span class="card-title">Media File</span>
                        <span class="card-amount">{{ $total_media }}</span>
                        <span class="card-desc">Total number of images used in the system</span>
                    </div>
                    <div class="card-media">
                        <img src="{{ asset('images/images.png') }}" width="50px" alt="">
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-6">
                <div class="dashboard-report-card card bg-dark">
                    <div class="card-content">
                        <span class="card-title">Mobile Push Notification (Firebase Integration)</span>
                        <span class="card-amount">{{ $total_notification }}</span>
                        <span class="card-desc">Number of notifications that have been sent out</span>
                    </div>
                    <div class="card-media">
                        <img src="{{ asset('images/notification-bell.png') }}" width="50px" alt="">
                    </div>
                </div>
            </div>

        </div>
        
        <div class="row">
            <div class="col-md-12 col-lg-6 mb-3">
                <div class="panel">
                    <div class="panel-title d-flex justify-content-between">
                        <strong>{{__('Latest User Register')}}</strong>
                        <a href="{{route('user.admin.index')}}" class="btn-link">{{__("More")}}
                            <i class="icon ion-ios-arrow-forward"></i></a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th width="60px">#</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__("Email")}}</th>
                                    {{-- <th>{{__("Sign-In Provider")}}</th> --}}
                                    <th>{{__("Register Date")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($users) > 0)
                                    @foreach($users as $user)                                    
                                        <tr>
                                            <td>{{$user->id}}</td>
                                            <td>{{$user->getDisplayName()}}</td>
                                            <td>{{$user->email}}</td>                                            
                                            {{-- <td>{{$user->sign_in_provider}}</td> --}}
                                            <td>{{$user->created_at}}</td>                                            
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">{{__("No data")}}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                {{-- <div class="panel">
                    <div class="panel-title d-flex justify-content-between align-items-center">
                        <strong>{{__('Earning statistics')}}</strong>
                        <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                            <i class="fa fa-calendar"></i>&nbsp;
                            <span></span> <i class="fa fa-caret-down"></i>
                        </div>
                    </div>
                    <div class="panel-body">
                        <canvas id="earning_chart"></canvas>
                        <script>
                            var earning_chart_data = {!! json_encode($earning_chart_data) !!};
                        </script>
                    </div>
                </div> --}}
            </div>
            <div class="col-md-12 col-lg-6 ">
                <div class="panel">
                    <div class="panel-title d-flex justify-content-between">
                        <strong>{{__('Recent Review')}}</strong>
                        <a href="{{route('review.admin.index')}}" class="btn-link">{{__("More")}}
                            <i class="icon ion-ios-arrow-forward"></i></a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th width="60px">#</th>
                                    <th>{{__('POI Location')}}</th>
                                    <th>{{__("Review")}}</th>
                                    <th>{{__("User")}}</th>
                                    <th>{{__("Created At")}}</th>                                                                        
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($recent_reviews) > 0)
                                    @foreach($recent_reviews as $review)
                                    @php $service = $review->getService @endphp
                                        <tr>
                                            <td>#{{$review->id}}</td>
                                            <td>
                                                @if(!empty($service))
                                            {{-- <a href="{{ route('review.admin.index',['service_id'=>$service->id,'object_model'=>$service->type]) }}"> --}}
                                                {{ $service->title }}
                                            {{-- </a> --}}
                                            {{-- <p>
                                                <a target="_blank" href="{{$service->getDetailUrl()}}">
                                                    <i class="fa fa-long-arrow-right" aria-hidden="true"></i> {{ __("View :name",["name"=>$service->getModelName() ])}}
                                                </a>
                                            </p> --}}
                                        @else
                                            {{__("[Deleted]")}}
                                        @endif
                                            </td>                                            
                                            <td>{{$review->content}}</td>
                                            <td>{{$review->author->name}}</td>
                                            <td>{{display_datetime($review->created_at)}}</td>
                                            {{-- <td>
                                                <span class="badge badge-{{$review->status_class}}">{{$review->status_name}}</span>
                                            </td> --}}
                                            
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">{{__("No data")}}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                {{-- <div class="panel">
                    <div class="panel-title d-flex justify-content-between">
                        <strong>{{__('Recent Bookings')}}</strong>
                        <a href="{{route('report.admin.booking')}}" class="btn-link">{{__("More")}}
                            <i class="icon ion-ios-arrow-forward"></i></a>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th width="60px">#</th>
                                    <th>{{__('Item')}}</th>
                                    <th width="100px">{{__("Total")}}</th>
                                    <th width="100px">{{__("Paid")}}</th>
                                    <th width="100px">{{__("Status")}}</th>
                                    <th width="100px">{{__("Created At")}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($recent_reviews) > 0)
                                    @foreach($recent_reviews as $review)
                                        <tr>
                                            <td>#{{$review->id}}</td>
                                            <td>
                                                @if(get_bookable_service_by_id($review->object_model) and $service = $review->service)
                                                    <a href="{{$service->getDetailUrl()}}" target="_blank">{{$service->title}}</a>
                                                @else
                                                    {{__("[Deleted]")}}
                                                @endif
                                            </td>
                                            <td>{{format_money_main($review->total)}}</td>
                                            <td>{{format_money_main($review->paid)}}</td>
                                            <td>
                                                <span class="badge badge-{{$review->status_class}}">{{$review->status_name}}</span>
                                            </td>
                                            <td>{{display_datetime($review->created_at)}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">{{__("No data")}}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
        <br>
        <div class="row">
        </div>
    </div>
@endsection

@push('js')
    <script src="{{url('libs/chart_js/Chart.min.js')}}"></script>
    <script src="{{url('libs/daterange/moment.min.js')}}"></script>
    <script>
        var ctx = document.getElementById('earning_chart').getContext('2d');
        window.myMixedChart = new Chart(ctx, {
            type: 'bar',
            data: earning_chart_data,
            options: {
                responsive: true,
                tooltips: {
                    mode: 'index',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        stacked: true,
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '{{__("Timeline")}}'
                        }
                    }],
                    yAxes: [{
                        stacked: true,
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '{{__("Currency: :currency_main",['currency_main'=>setting_item('currency_main')])}}'
                        },
                        ticks: {
                            beginAtZero: true,
                        }
                    }]
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += tooltipItem.yLabel + " ({{setting_item('currency_main')}})";
                            return label;
                        }
                    }
                }
            }
        });

        var start = moment().startOf('week');
        var end = moment();
        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            "alwaysShowCalendars": true,
            "opens": "left",
            "showDropdowns": true,
            ranges: {
                '{{__("Today")}}': [moment(), moment()],
                '{{__("Yesterday")}}': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '{{__("Last 7 Days")}}': [moment().subtract(6, 'days'), moment()],
                '{{__("Last 30 Days")}}': [moment().subtract(29, 'days'), moment()],
                '{{__("This Month")}}': [moment().startOf('month'), moment().endOf('month')],
                '{{__("Last Month")}}': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                '{{__("This Year")}}': [moment().startOf('year'), moment().endOf('year')],
                '{{__('This Week')}}': [moment().startOf('week'), end]
            }
        }, cb).on('apply.daterangepicker', function (ev, picker) {
            // Reload Earning JS
            $.ajax({
                url: '{{route('report.admin.statistic.reloadChart')}}',
                data: {
                    chart: 'earning',
                    from: picker.startDate.format('YYYY-MM-DD'),
                    to: picker.endDate.format('YYYY-MM-DD'),
                },
                dataType: 'json',
                type: 'post',
                success: function (res) {
                    if (res.status) {
                        window.myMixedChart.data = res.data;
                        window.myMixedChart.update();
                    }
                }
            })
        });
        cb(start, end);
    </script>
@endpush
