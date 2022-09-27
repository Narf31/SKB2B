@extends('layouts.frame')

@section('title')

    Доп. Оборудование

@stop

@section('content')

    {{ Form::model($equipment, ['url' => url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/kasko/auto/equipment/{$equipment_id}"), 'method' => 'post', 'class' => 'form-horizontal']) }}


    <div class="form-group">
        <label class="col-sm-4 control-label">Страховая сумма от</label>
        <div class="col-sm-8">
            {{ Form::text('amount_to', titleFloatFormat($equipment->amount_to, 0, 1), ['class' => 'form-control sum']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Страховая сумма до</label>
        <div class="col-sm-8">
            {{ Form::text('amount_from', titleFloatFormat($equipment->amount_from, 0, 1), ['class' => 'form-control sum']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Тариф %</label>
        <div class="col-sm-8">
            {{ Form::text('payment_tarife', titleFloatFormat($equipment->payment_tarife, 0, 1), ['class' => 'form-control sum']) }}
        </div>
    </div>


    {{Form::close()}}

@stop

@section('footer')

    @if($equipment_id > 0)
        <button class="btn btn-danger pull-left" onclick="deleteEquipment()">{{ trans('form.buttons.delete') }}</button>

        <script>

            function deleteEquipment() {
                if (!customConfirm()) return false;

                $.post('{{url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/kasko/auto/equipment/{$equipment_id}")}}', {
                    _method: 'delete'
                }, function () {
                    window.parent.reloadTab();
                });
            }

        </script>

    @endif


    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>




@stop

