@extends('layouts.frame')


@section('title')

    Организация получатель

@stop

@section('content')


    {{ Form::open(['url' => url("/bso/items/{$bso->id}/edit_supplier_org"), 'method' => 'post', 'class' => 'form-horizontal']) }}



    <div class="form-group">
        <label class="col-sm-4 control-label">Поставщики БСО</label>
        <div class="col-sm-8">
            {{ Form::select('bso_suppliers_id', $supplier->pluck('title', 'id'), $bso->bso_supplier_id, ['class' => 'form-control', 'required']) }}
        </div>
    </div>


    {{Form::close()}}


@stop

@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop
