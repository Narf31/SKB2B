@extends('layouts.app')

@section('content')



        <div class="page-heading">
            <h1>{{ trans('menu.security') }}: {{ trans('menu.security_archive') }}</h1>

            <div class="pull-right">
                <form>
                    <table>
                        <tr>
                            <td style="padding: 5px;">
                                <input style="width: 100px" type="text" class="form-control datepicker date" placeholder="С" name="date_from" value="{{ $dates_start }}">
                            </td>
                            <td style="padding: 5px;">
                                <input style="width: 100px" type="text" class="form-control datepicker date" placeholder="По" name="date_to" value="{{ $dates_end }}">
                            </td>
                            <td>
                                <button class="btn btn-primary">Применить</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>

        </div>

        <div class="block-inner">
        @if(sizeof($inquirys))
            <table class="largeTable">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Дата</th>
                    <th>Инициатор</th>
                    <th>Тип</th>
                    <th>Взятли в работу</th>
                    <th>Сотрудник</th>
                </tr>
                </thead>
                @foreach($inquirys as $key => $inquiry)
                    <tr class="clickable-row" data-href="{{url ("/security/$inquiry->id/work")}}">
                        <td>{{ $key+1 }}</td>
                        <td>{{ setDateTimeFormatRu($inquiry->created_at) }}</td>
                        <td>{{ $inquiry->send_user->name }}</td>
                        <td>{{ $inquiry->type_inquiry_title($inquiry->type_inquiry) }}
                            @if($inquiry->type_inquiry == \App\Models\Security\Security::TYPE_INQUIRY_EMERGENCY)
                                {{$inquiry->emergency->event_title}}
                            @elseif($inquiry->type_inquiry == \App\Models\Security\Security::TYPE_INQUIRY_ORDER)
                                {{$inquiry->event_order->type_title($inquiry->event_order->types)}}
                            @endif
                        </td>
                        <td>{{ setDateTimeFormatRu($inquiry->dates_work) }}</td>
                        <td>{{ $inquiry->work_user->name }}</td>
                    </tr>
                @endforeach

            </table>
        @else
            {{ trans('form.empty') }}
        @endif
        </div>


@endsection

