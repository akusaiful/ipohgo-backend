<?php
if(!auth()->check()) return;
[$notifications,$countUnread] = getNotify();

?>
<div class="dropdown-notifications position-relative px-3 u-header__login-form dropdown-connector-xl u-header__topbar-divider">
                <span class="d-inline-block font-size-14 mr-1 dropdown-nav-link" data-toggle="dropdown">
                    <i class="flaticon-bell mr-2 ml-1 font-size-18"></i>
                    <span class="d-inline-block badge badge-danger notification-icon">{{$countUnread}}</span>
                </span>
    <ul class="dropdown-menu text-left dropdown overflow-auto notify-items dropdown-large">
        <div class="dropdown-toolbar">
            <h3 class="dropdown-toolbar-title">{{__('Notifications')}} (<span class="notif-count">{{$countUnread}}</span>)</h3>
            <div class="dropdown-toolbar-actions">
                <a href="#" class="markAllAsRead">{{__('Mark all as read')}}</a>
            </div>
        </div>
        <ul class="dropdown-list-items p-0">
            @if(count($notifications)> 0)
                @foreach($notifications as $oneNotification)
                    @php
                        $active = $class = '';
                        $data = json_decode($oneNotification['data']);

                        $idNotification = @$data->id;
                        $forAdmin = @$data->for_admin;
                        $usingData = @$data->notification;

                        $services = @$usingData->type;
                        $idServices = @$usingData->id;
                        $title = @$usingData->message;
                        $name = @$usingData->name;
                        $avatar = @$usingData->avatar;
                        $link = @$usingData->link;

                        if(empty($oneNotification->read_at)){
                            $class = 'markAsRead';
                            $active = 'active';
                        }
                    @endphp
                    <li class="notification {{$active}}">
                        <div class="media">
                            <div class="media-left">
                                <div class="media-object">
                                    @if($avatar)
                                        <img class="image-responsive" src="{{$avatar}}" alt="{{$name}}">
                                    @else
                                        <span class="avatar-text">{{ucfirst($name[0])}}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="media-body">
                                <a class="{{$class}} p-0" data-id="{{$idNotification}}" href="{{$link}}">{!! $title !!}</a>
                                <div class="notification-meta">
                                    <small class="timestamp">{{format_interval($oneNotification->created_at)}}</small>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            @endif
        </ul>
        <div class="dropdown-footer text-right">
            <a href="{{route('core.notification.loadNotify')}}">{{__('View More')}}</a>
        </div>
    </ul>
</div>
