@extends('layouts.frame')

@section('title')

    История изменения договора


@stop

@section('content')


    <div class="row form-horizontal" >

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="max-height: 300px;overflow: auto;">


            <table class="tov-table" >
                <tr>
                    <td>Дата</td>
                    <td>Назначил</td>
                    <td>Статус</td>
                    <td>Примечание</td>
                </tr>
                @if($contract->history_logs)
                    @foreach($contract->history_logs as $log)

                        <tr>
                            <td>{{ setDateTimeFormatRu($log->created_at) }}</td>
                            <td>{{ $log->user ? $log->user->name : '' }}</td>
                            <td>{{ $log->status_title }}</td>
                            <td>{!! $log->text !!}</td>
                        </tr>
                    @endforeach
                @endif
            </table>



        </div>



    </div>


@stop

@section('footer')


@stop

