@extends('layouts.frame')


@section('title')

    Отправить на согласование

@stop

@section('content')


    {{ Form::open(['url' => url("/contracts/online/{$contract->id}/action/send-matching"), 'method' => 'post', 'class' => 'form-horizontal']) }}



    <div class="form-group">
        <label class="col-sm-12 control-label">Примечания</label>
        <div class="col-sm-12">
            {{ Form::textarea("comments", '', ['class' => 'form-control']) }}
        </div>
    </div>


    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">Отправить</button>

@stop
