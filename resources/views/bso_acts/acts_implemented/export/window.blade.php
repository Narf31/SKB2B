@extends('layouts.frame')

@section('title')Экспорт реализованнах актов@stop

@section('content')

    @if(sizeof($templates))
        {{ Form::open(['url' => url("bso_acts/acts_implemented/details/{$act->id}/export_realized_bso"), 'method' => 'post',  'class' => 'form-horizontal']) }}
        <div class="form-group">
            <label class="col-sm-4 control-label">Выберите шаблон для экспорта</label>
            <div class="col-sm-8">
                {{ Form::select('template_id', collect($templates)->pluck('title', 'id')->prepend('Не выбрано', 0), 0,  ['class' => 'form-control', 'required']) }}
            </div>
        </div>
        {{Form::close()}}
    @else
        @if(auth()->user()->hasPermission('settings', 'templates'))
            <a href="{{url('/settings/templates')}}" target="_blank">Загрузите</a>
        @else
            Загрузите
        @endif
        .xls шаблон экспорта для выгрузки "Реализованные акты БСО"
    @endif

@stop

@section('footer')

    <button class="btn btn-primary pull-left" onclick="parent_reload()">Отмена</button>
    <button onclick="submitForm()" type="submit" class="btn btn-success">Экспорт</button>

@stop


