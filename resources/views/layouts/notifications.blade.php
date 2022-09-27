@php
    $notifications = auth()->user()->getNotifications()->get();
@endphp

<div class="user-messages">
    <a href="/users/notification/">
        <span class="glyphicon glyphicon-bell bell-first"></span>
        <span class="glyphicon glyphicon-bell bell-second"></span>

        <div class="user-alerts-counter">
            <span class="round-big"></span>
            <span class="round-small">{{ $notifications->count() > 9 ? "9+" : $notifications->count() }}</span>
        </div>
    </a>
    <div class="user-messages-container" id="user-messages-form">
        @if(sizeof($notifications))

            <div class="btn btn-info" id="clear_all_messages_wrapper" onclick="clear_all_messages();">
                Убрать все уведомления
            </div>
            <hr/>
            @foreach($notifications as $notification)
                <div style="cursor: pointer;" onclick="openPage('/account/notification/{{$notification->id}}')">
                    {!! $notification->msg !!}
                </div>
                <hr/>
            @endforeach
        @else
            <p>Нет уведомлений</p>
        @endif
    </div>
</div>