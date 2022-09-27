<div class="notification_body">
    <p>
        <span class="col-lg-12">
            <span class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                @yield('notification')
            </span>

            <span class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                @if($notification->is_read == 0)
                    <span class="btn-xs btn-success btn-right" data-notification="{{$notification->id}}">прочитано</span>
                @endif
            </span>

        </span>
    </p>
</div>
