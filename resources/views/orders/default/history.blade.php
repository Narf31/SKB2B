<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <table class="table">
        <thead>
        <tr>
            <th>Дата / Время</th>
            <th>Статус</th>
            <th>Событие</th>
            <th>Пользователь</th>
        </tr>
        </thead>

    @if(sizeof($order->logs))
            <tbody>
        @foreach($order->logs as $log)
            <tr class="{{$log->color}}">
                <td>{{setDateTimeFormatRu($log->created_at)}}</td>
                <td>{{$log->status_title}}</td>
                <td>{{$log->event_title}}</td>
                <td>{{ ($log->user)?$log->user->name : $log->create_title }}</td>
            </tr>
        @endforeach
            </tbody>
    @endif
    </table>
</div>