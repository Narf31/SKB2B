@extends('layouts.frame')

@section('title')

    Доп. Продукты

@stop

@section('content')

    {{ Form::model($k_product, ['url' => url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/kasko/auto/product/{$k_product_id}"), 'method' => 'post', 'class' => 'form-horizontal']) }}


    <div class="form-group">
        <label class="col-sm-4 control-label">Продукт</label>
        <div class="col-sm-8">
            {{ Form::select('kasko_product_id', \App\Models\Directories\Products\Data\Kasko\KaskoProduct::PRODUCT, $k_product->kasko_product_id,  ['class' => 'form-control select2-ws', 'id' => 'kasko_product_id', 'onchange'=>"viewAmount()"]) }}
        </div>
    </div>

    <div class="form-group is_amount">
        <label class="col-sm-4 control-label">Страховая сумма ДО</label>
        <div class="col-sm-8">
            {{ Form::text('amount', titleFloatFormat($k_product->amount, 0, 1), ['class' => 'form-control sum']) }}
        </div>
    </div>


    <div class="form-group is_civil_responsibility">
        <label class="col-sm-4 control-label">Страховая сумма</label>
        <div class="col-sm-8">
            {{ Form::select('civil_responsibility_amount', collect(\App\Models\Directories\Products\Data\Kasko\Standard::CIVIL_RESPONSIBILITY),(int)$k_product->amount, ['class' => 'form-control select2-ws']) }}
        </div>
    </div>


    <div class="form-group">
        <label class="col-sm-4 control-label">Тариф</label>
        <div class="col-sm-8">
            {{ Form::text('payment_tarife', titleFloatFormat($k_product->payment_tarife, 0, 1), ['class' => 'form-control sum']) }}
        </div>
    </div>


    {{Form::close()}}

@stop

@section('js')

    <script>


        $(function () {

            viewAmount();

        });

        function viewAmount() {
            if(parseInt($("#kasko_product_id").val()) == 4){
                $(".is_amount").hide();
                $(".is_civil_responsibility").show();
            }else{
                $(".is_amount").show();
                $(".is_civil_responsibility").hide();

            }
        }

    </script>

@stop

@section('footer')

    @if($k_product_id > 0)
        <button class="btn btn-danger pull-left" onclick="deleteElement()">{{ trans('form.buttons.delete') }}</button>

        <script>

            function deleteElement() {
                if (!customConfirm()) return false;

                $.post('{{url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/kasko/auto/product/{$k_product_id}")}}', {
                    _method: 'delete'
                }, function () {
                    window.parent.reloadTab();
                });
            }

        </script>

    @endif


    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>





@stop

