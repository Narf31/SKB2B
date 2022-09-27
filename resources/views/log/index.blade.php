@extends('layouts.frame')


@section('title')

    Логи

@stop

@section('content')


    <table class="tov-table" >
        <thead>
        <tr>
            <th>Дата время</th>
            <th>Тип</th>
            <th>Событие</th>
            <th>Пользователь</th>
        </tr>
        </thead>
        @if(sizeof($logs))
            @foreach($logs as $log)
                <tr>
                    <td>{{ setDateTimeFormatRu($log->created_at) }}</td>
                    <td>{{ \App\Models\Log\LogEvents::LOG_TYPE[$log->type_id] }}</td>
                    <td>{{ $log->event }}</td>
                    <td>{{ $log->user->name }}</td>
                </tr>
            @endforeach
        @endif
    </table>


@stop
