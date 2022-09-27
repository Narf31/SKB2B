@extends('layouts.frame')

@section('title')

    Программа


@endsection

@section('content')

    {{ Form::model($programs, ['url' => url("/directories/products/{$product_id}/edit/programs/".(int)$programs->id), 'method' => 'post', 'class' => 'form-horizontal']) }}


    <div class="form-group">
        <label class="col-sm-4 control-label">{{ trans('settings/banks.is_actual') }}</label>
        <div class="col-sm-2">
            {{ Form::checkbox('is_actual', 1, old('is_actual')) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Ключ классов для калькулятора (EN)</label>
        <div class="col-sm-8" >
            {{ Form::select('slug', collect(\App\Models\Directories\ProductsPrograms::SLUG[$product->slug]), old('slug'), ['class' => 'form-control', 'id' => 'slug', "onchange"=>"getAPiPrograms()"]) }}
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-4 control-label">Описание</label>
        <div class="col-sm-8" >
            {{ Form::text('title', old('title'), ['class' => 'form-control']) }}
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-4 control-label">Описание продукта</label>
        <div class="col-sm-8">
            {{ Form::textarea('description', old('description'),['rows' => 2,  'class' =>'form-control', 'style' => 'width:100%', 'id' => 'text_description']) }}
        </div>
    </div>



    {{Form::close()}}

@endsection

@section('footer')

    @if((int)$programs->id > 0)
        <button class="btn btn-danger pull-left" onclick="deleteItem('/directories/products/{{ $product_id }}/edit/programs/', '{{ $programs->id }}')">{{ trans('form.buttons.delete') }}</button>
    @endif

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@endsection

@section('js')

    <script>


        $(function () {




        });



    </script>


@endsection