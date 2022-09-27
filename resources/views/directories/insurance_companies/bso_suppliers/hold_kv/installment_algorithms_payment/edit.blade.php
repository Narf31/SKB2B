@extends('layouts.frame')

@section('title')

    Алгоритм рассрочки


@stop

@section('content')



{{ Form::open(['url' => url("/directories/insurance_companies/{$id}/bso_suppliers/{$bso_supplier_id}/hold_kv/{$hold_kv_id}/installment_algorithms_payment/{$group_id}/{$algorithm_id}"), 'method' => 'post', 'class' => 'form-horizontal']) }}

<div class="form-group">
    <label class="col-sm-4 control-label">Алгоритм</label>
    <div class="col-sm-8">
        {{ Form::select('algorithm_id', \App\Models\Settings\InstallmentAlgorithmsPayment::all()->pluck('title', 'id'), $algorithm->algorithm_id, ['class' => 'form-control select2-ws', 'required']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Андерайтер</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_underwriting', 1, $algorithm->is_underwriting) }}
    </div>
</div>



{{Form::close()}}


@stop

@section('footer')

    @if($algorithm_id > 0)

        <button class="btn btn-danger pull-left" onclick="deleteItem('/directories/insurance_companies/{{$id}}/bso_suppliers/{{$bso_supplier_id}}/hold_kv/{{$hold_kv_id}}/installment_algorithms_delete/', '{{ $algorithm->id }}')">{{ trans('form.buttons.delete') }}</button>

    @endif

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop