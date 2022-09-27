@extends('layouts.frame')


@section('title')

   Андеры

@endsection

@section('content')


    <table class="table table-bordered text-left payments_table huck">
        <thead>
        <tr>
            <th>Пользователь</th>
            <th>Дата начала</th>
            <th>Дата окончания</th>
        </tr>
        </thead>
        <tbody>
        @foreach($logs as $log)
            <tr>
                <td>{{$log->user->name}}</td>
                <td>{{setDateTimeFormatRu($log->start_date)}}</td>
                <td>{{setDateTimeFormatRu($log->end_date)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>


@endsection

@section('js')
    <script>

        $(function(){


        })

    </script>

@endsection

@section('footer')


@endsection
