@extends('layouts.frame')

@section('title')

    Запрос #{{$inquiry->id}}

@stop

@section('content')

    <div class="block-inner">
        <div class="info-group">
            <label>Дата запроса</label>
            <div class="value"><span>{{setDateTimeFormatRu($inquiry->created_at)}}</span></div>
        </div>

        <div class="info-group">
            <label>Инициатор</label>
            <div class="value"><span>{{$inquiry->send_user->name}}</span></div>
        </div>

        <div class="info-group">
            <label>Тип</label>
            <div class="value"><span>{{$inquiry->type_inquiry_title($inquiry->type_inquiry)}}</span></div>
        </div>

        <div class="info-group">
            <label>Статус</label>
            <div class="value"><span>{{$inquiry->status_title($inquiry->status)}}</span></div>
        </div>

        <div class="info-group">
            <label>Взятли в работу</label>
            <div class="value"><span>{{setDateTimeFormatRu($inquiry->dates_work)}}</span></div>
        </div>

        <div class="info-group">
            <label>Сотрудник</label>
            <div class="value"><span>{{$inquiry->work_user->name}}</span></div>
        </div>

        <div class="info-group">
            <label>Комментарий</label>
            <div class="value"><br/>
                <div class="value"><span> </span></div>
            </div>
        </div>
        <div class="info-group col-sm-12">
            {{$inquiry->comments or ''}}
        </div>
    </div>

@stop


