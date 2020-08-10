@php
    $notification_count = auth('admin')->user()->unreadNotifications->count();
@endphp
<li class="dropdown notification-list" id="notification_dropdown">
    <a class="nav-link dropdown-toggle  waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
        <i class="fe-bell noti-icon"></i>
        @if($notification_count > 0)
            <span class="badge badge-danger rounded-circle noti-icon-badge">{{ $notification_count }}</span>
        @endif
    </a>
    <div class="dropdown-menu dropdown-menu-right dropdown-lg">

        <!-- item-->
        @if($notification_count > 0)
            <div class="dropdown-item noti-title">
                <h5 class="m-0">
                    <span class="float-right">
                        <a href="{{ route('admin.notifications.all_read') }}" class="text-dark">
                            <small>Clear All</small>
                        </a>
                    </span>Notifications
                </h5>
            </div>
            <div class="slimscroll noti-scroll">

                @foreach (auth('admin')->user()->notifications as $notification)
                <a href="{{ route('admin.home') }}/{{$notification->data['link'] ?? ''}}" class="dropdown-item notify-item {{$notification->read_at === null ? 'active' : ''}}">
                    <div class="notify-icon msg" style="background: {{$notification->data['color'] ?? '#2bb53e'}}">
                        <i class="fa fa-{{$notification->data['icon'] ?? 'bell-o'}}"></i>
                    </div>
                    <p class="notify-details">{{$notification->data['msg'] ?? 'New Notification'}}</p>
                    <p class="text-muted mb-0">
                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                    </p>
                </a>
                @endforeach

            </div>

        @else
        <div class="dropdown-item noti-title">
            <h5 class="m-0">Notifications</h5>
        </div>
        <div class="slimscroll noti-scroll">
            <p class="py-3 text-center">No new notifications</p>
        </div>
        @endif

        <!-- All-->
        <a href="{{route('admin.notifications')}}" class="dropdown-item text-center text-primary notify-item notify-all">
            View all
            <i class="fi-arrow-right"></i>
        </a>

    </div>
</li>