@extends('layouts.frame')


@section('title')

    Тип и номер БСО

@stop

@section('content')


    {{ Form::open(['url' => url("/bso/items/{$bso->id}/edit_bso_title"), 'method' => 'post', 'class' => 'form-horizontal']) }}

    <input type="hidden" id="bso_supplier_id" value="{{$bso->bso_supplier_id}}"/>

    <div class="form-group">
        <label class="col-sm-3 control-label">Тип</label>
        <div class="col-sm-9">
            {{ Form::select('bso_type', $bso_type->prepend('Выберите значение', 0), $bso->type_bso_id, ['class' => 'form-control bso_type', 'onchange'=>'selectBsoType(this)']) }}
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">Номер полис</label>
        <div class="col-sm-3">
            {{ Form::select('bso_serie_id' , $bso_serie->prepend('Выберите значение', 0), $bso->bso_serie_id, ['class' => 'form-control series_selector', 'required', 'onchange'=>'selectBsoDopSeries(this)']) }}
        </div>

        <div class="col-sm-4">
            {{ Form::text('bso_number' , $bso->bso_number, ['class' => 'form-control', 'required']) }}
        </div>

        <div class="col-sm-2">
            {{ Form::select('bso_dop_serie_id' , $bso_dop_serie->prepend('Выберите значение', 0), $bso->bso_dop_serie_id, ['class' => 'form-control dop_series_selector', 'required']) }}
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




        function selectBsoType(bso_type){
            bso_type_id = $(bso_type).val();
            bso_supplier_id = $('#bso_supplier_id').val();

            $.getJSON('{{url('/bso/actions/get_series/')}}', {bso_type_id: bso_type_id, bso_supplier_id:bso_supplier_id}, function (response) {

                var options = "<option value='0'>Не выбрано</option>";
                response.map(function (item) {
                    options += "<option value='" + item.id + "'>" + item.bso_serie + "</option>";
                });

                $('select.series_selector').html(options);
                $('select.series_selector2').html(options);


            });

        }

        function selectBsoDopSeries(series){
            series_id = $(series).val();

            $.getJSON('{{url('/bso/actions/get_dop_series/')}}', {series_id: series_id}, function (response) {

                var options = "<option value='0'>Не выбрано</option>";
                response.map(function (item) {
                    options += "<option value='" + item.id + "'>" + item.bso_dop_serie + "</option>";
                });

                $('select.dop_series_selector').html(options);

            });

        }

        function selectBsoBlankDopSeries(series){
            series_id = $(series).val();

            $.getJSON('{{url('/bso/actions/get_dop_series/')}}', {series_id: series_id}, function (response) {

                var options = "<option value='0'>Не выбрано</option>";
                response.map(function (item) {
                    options += "<option value='" + item.id + "'>" + item.bso_dop_serie + "</option>";
                });

                $('select.dop_series_selector2').html(options);

            });

        }






    </script>


@stop
