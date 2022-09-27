@if(sizeof($notifications))

    <table class="tov-table">
        <thead>
        <tr class="sort-row">
            <th>{{ trans('users/roles.index.title') }}</th>
        </tr>
        </thead>


        <tbody>
        @foreach($notifications as $notification)
            <tr class="clickable-row-blank" onclick="openPage('/account/notification/{{$notification->id}}')">
                <td width="70%">
                    {!! $notification->msg !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@else
    нет уведомлений
@endif