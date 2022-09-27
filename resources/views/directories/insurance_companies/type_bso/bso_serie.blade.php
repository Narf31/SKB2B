@extends('layouts.frame')

@section('title')




    Типы и серия БСО


@stop

@section('content')



{{ Form::open(['url' => url("/directories/insurance_companies/{$insurance_companies->id}/type_bso/".((int)$type_bso->id)."/bso_serie/".((int)$bso_serie->id)."/"), 'method' => 'post', 'class' => 'form-horizontal']) }}

<div class="form-group">
    <label  class="col-sm-4 control-label">Актуально</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_actual', 1, $bso_serie->is_actual) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Класс БСО</label>
    <div class="col-sm-8">
        {{ Form::select('bso_class_id', collect(\App\Models\Directories\TypeBso::CLASS_BSO), $bso_serie->bso_class_id, ['class' => 'form-control', 'required']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Серия</label>
    <div class="col-sm-8">
        {{ Form::text('bso_serie', $bso_serie->bso_serie, ['class' => 'form-control', 'required']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Кол-во символов</label>
    <div class="col-sm-8">
        {{ Form::text('bso_count_number', $bso_serie->bso_count_number, ['class' => 'form-control sum', 'required']) }}
    </div>
</div>

{{Form::close()}}

@if($bso_serie->id >0 )
    <div class="form-group">
        <div class="page-subheading">
            <h2>Доп. серии БСО</h2>
            <a href="/directories/insurance_companies/{{$insurance_companies->id}}/type_bso/{{((int)$type_bso->id)}}/bso_serie/{{$bso_serie->id}}/bso_dop_serie/0/"
               class="btn btn-primary pull-right">
                {{ trans('form.buttons.add') }}
            </a>
        </div>
        @if(sizeof($bso_serie->bso_dop_serie))
            <table class="tov-table" >
                @foreach($bso_serie->bso_dop_serie as $bso_dop)
                    <tr class="clickable">
                        <td><a href="/directories/insurance_companies/{{$insurance_companies->id}}/type_bso/{{((int)$type_bso->id)}}/bso_serie/{{$bso_serie->id}}/bso_dop_serie/{{$bso_dop->id}}/"> {{ $bso_dop->bso_dop_serie  }}</a></td>

                    </tr>
                @endforeach
            </table>
        @else
            {{ trans('form.empty') }}
        @endif
    </div>
@endif


@stop

@section('footer')

    <a href="{{url("/directories/insurance_companies/{$insurance_companies->id}/type_bso/{$type_bso->id}/")}}" class="btn btn-danger pull-left">Назад</a>

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop