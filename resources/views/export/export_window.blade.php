@php
    $title = request('title', 'Экспорт');
    $url = request('url', '');
    $templates = request('templates', [])
@endphp

@extends('layouts.frame')

@section('title')
    {{$title}}
@stop

@section('content')
    {{ Form::open(['url' => url($url), 'method' => 'get',  'class' => 'form-horizontal']) }}
        <div class="form-group">
            <label class="col-sm-4 control-label">Выберите шаблон для экспорта</label>
            <div class="col-sm-8">
                {{ Form::select('_template_id', collect($templates), 0,  ['class' => 'form-control', 'required']) }}
            </div>
        </div>
    {{Form::close()}}
@stop

@section('footer')
    <a class="btn btn-primary pull-left" onclick="parent_reload()">Отмена</a>
    <button onclick="submitForm()" type="submit" class="btn btn-success">Экспорт</button>
@stop


