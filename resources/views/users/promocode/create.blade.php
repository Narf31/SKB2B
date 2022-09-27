@extends('layouts.frame')


@section('title')

    Создать промокод

@stop

@section('content')


    {{ Form::open(['url' => url('/users/promocode/create'), 'method' => 'post', 'class' => 'form-horizontal']) }}


    <div class="form-group">
        <label class="col-sm-12 control-label">Пользователь</label>
        <div class="col-sm-12">
            {{ Form::select('user_id', $users->pluck('name', 'id')->prepend('Нет', 0), $user_id, ['class' => 'form-control select2']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-12 control-label">Кол-во промокодов</label>
        <div class="col-sm-12">
            {{ Form::text('count_promocode', '', ['class' => 'form-control sum']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-12 control-label">Дата действия</label>
        <div class="col-sm-12">
            {{ Form::text('valid_date', date('d.m.Y', strtotime('+1 months')), ['class' => 'form-control datepicker date inline', 'autocomplete' => 'off']) }}
        </div>
    </div>


    {{Form::close()}}


@stop


@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary" id="button_save">Создать</button>

    <br/><br/>
@stop

@section('js')

    <script>




        $(function () {



        });


    </script>


@stop