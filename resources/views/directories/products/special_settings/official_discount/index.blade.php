@extends('layouts.frame')

@section('title')


    Скидки

@stop

@section('content')

    {{ Form::open(['url' => url("/directories/products/{$product->id}/edit/special-settings/official_discount/".(int)$discount->id), 'method' => 'post', 'class' => 'form-horizontal']) }}

    <div class="form-group">
        <label class="col-sm-4 control-label">Скидка</label>
        <div class="col-sm-8">
            {{ Form::text('discount', titleFloatFormat($discount->discount), ['class' => 'form-control sum']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Скидка</label>
        <div class="col-sm-8">
            {{ Form::select('type_id', collect(\App\Models\Directories\Products\ProductsOfficialDiscount::TYPE), $discount->type_id, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Условия</label>
        <div class="col-sm-8">
            {{ Form::select('risks[]', $product->flats_risks->pluck('title', 'id'), $json, ['class' => 'form-control select2-all', 'multiple' => true]) }}
        </div>
    </div>




    {{Form::close()}}

@stop

@section('footer')

    @if((int)$discount->id > 0)

    <button class="btn btn-danger pull-left" onclick="deleteItem('{{"/directories/products/{$product->id}/edit/special-settings/official_discount/"}}', '{{ $discount->id }}')">{{ trans('form.buttons.delete') }}</button>

    @endif

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop

