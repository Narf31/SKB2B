@extends('layouts.frame')

@section('title')Сформировать агентский договор@stop

@section('content')

    @if(sizeof($templates))
        {{ Form::open(['url' => url("/users/{$agent->id}/generate_word_contract"), 'method' => 'post',  'class' => 'form-horizontal']) }}
        <div class="form-group">
            <label class="col-sm-4 control-label">Выберите шаблон для экспорта</label>
            <div class="col-sm-8">
                {{ Form::select('template_id', collect($templates)->pluck('title', 'id')->prepend('Не выбрано', 0), 0,  ['class' => 'form-control select2-ws', 'required']) }}
            </div>
        </div>
        {{Form::close()}}
    @else
        @if(auth()->user()->hasPermission('settings', 'templates'))
            <a href="{{url('/settings/templates')}}" target="_blank">Загрузите</a>
        @else
            Загрузите
        @endif
        .docx шаблон экспорта для выгрузки "Генерация договора агента"
    @endif

@stop

@section('footer')
    <a class="btn btn-primary pull-left" onclick="parent.location.reload()">Отмена</a>
    <button onclick="submitForm()" type="submit" class="btn btn-success">Экспорт</button>
@stop


