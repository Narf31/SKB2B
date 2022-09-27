@extends('layouts.frame')


@section('title')

    Тип и номер БСО

@stop

@section('content')


    {{ Form::open(['url' => url("/bso/items/{$bso->id}/edit_bso_state"), 'method' => 'post', 'class' => 'form-horizontal']) }}


    <div class="form-group">
        <label class="col-sm-3 control-label">Событие</label>
        <div class="col-sm-9">
            {{Form::select('location_id', \App\Models\BSO\BsoLocations::query()->where('can_be_set_manually', 1)->get()->pluck('title', 'id'), $bso->location_id,  ['class' => 'form-control'])}}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Статус</label>
        <div class="col-sm-9">
            {{Form::select('state_id', \App\Models\BSO\BsoState::all()->pluck('title', 'id'), $bso->state_id,  ['class' => 'form-control'])}}

        </div>
    </div>





    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop




@section('js')


    <script type="text/javascript">



        $(function() {


        });






    </script>


@stop
