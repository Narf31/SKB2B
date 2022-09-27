@extends('layouts.frame')

@section('title')




    Типы и серия БСО


@stop

@section('content')



{{ Form::open(['url' => url("/directories/insurance_companies/{$insurance_companies->id}/type_bso/".((int)$type_bso->id)."/bso_serie/{$bso_serie->id}/bso_dop_serie/".(int)$bso_dop_serie->id."/"), 'method' => 'post', 'class' => 'form-horizontal']) }}

<div class="form-group">
    <label class="col-sm-4 control-label">Класс БСО</label>
    <div class="col-sm-8">
        {{ \App\Models\Directories\TypeBso::CLASS_BSO[$bso_serie->bso_class_id]  }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Продукт</label>
    <div class="col-sm-8">
        {{ ($type_bso->product)?$type_bso->product->title : 'Все'  }}
    </div>
</div>


<div class="form-group">
    <label class="col-sm-4 control-label">Серия</label>
    <div class="col-sm-8">
        {{$bso_serie->bso_serie}}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Доп. серия</label>
    <div class="col-sm-8">
        {{ Form::text('bso_dop_serie', $bso_dop_serie->bso_dop_serie, ['class' => 'form-control', 'required']) }}
    </div>
</div>

{{Form::close()}}


@stop

@section('footer')

    <a href="{{url("/directories/insurance_companies/{$insurance_companies->id}/type_bso/{$type_bso->id}/bso_serie/{$bso_serie->id}/")}}" class="btn btn-danger pull-left">Назад</a>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop