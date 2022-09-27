@extends('layouts.frame')

@section('title')

    Доп. Услуги

@stop

@section('content')

    {{ Form::model($service, ['url' => url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/kasko/auto/services/{$service_id}"), 'method' => 'post', 'class' => 'form-horizontal']) }}


    <div class="form-group">
        <label class="col-sm-4 control-label">Услуга</label>
        <div class="col-sm-8">
            {{ Form::select('service_name', \App\Models\Directories\Products\Data\Kasko\KaskoService::SERVIVES, $service->service_name,  ['class' => 'form-control select2-ws']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-4 control-label">Сумма</label>
        <div class="col-sm-8">
            {{ Form::text('payment_total', titleFloatFormat($service->payment_total, 0, 1), ['class' => 'form-control sum']) }}
        </div>
    </div>




    {{Form::close()}}

@stop

@section('footer')

    @if($service_id > 0)
        <button class="btn btn-danger pull-left" onclick="deleteServices()">{{ trans('form.buttons.delete') }}</button>

        <script>

            function deleteServices() {
                if (!customConfirm()) return false;

                $.post('{{url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/kasko/auto/services/{$service_id}")}}', {
                    _method: 'delete'
                }, function () {
                    window.parent.reloadTab();
                });
            }

        </script>

    @endif


    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>




@stop

