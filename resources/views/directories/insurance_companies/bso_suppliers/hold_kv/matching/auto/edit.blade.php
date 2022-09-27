@extends('layouts.frame')

@section('title')

    Условия согласования


@stop

@section('content')



{{ Form::open(['url' => url("/directories/insurance_companies/{$id}/bso_suppliers/{$bso_supplier_id}/hold_kv/{$hold_kv_id}/matching-terms/{$group_id}/{$type}/{$matching_id}"), 'method' => 'post', 'class' => 'form-horizontal']) }}



<div class="row form-horizontal" style="min-height: 400px;">

    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        <label class="control-label" style="min-width: 100%;">Категория</label>
        {{Form::select("matching[object][ts_category]", \App\Models\Vehicle\VehicleCategories::query()->get()->pluck('title', 'id')->prepend('Не выбрано', 0), getDataIsset($info, 'object', 'ts_category'), ['class' => 'form-control select2-ws', 'id'=>"object_ts_category", 'onchange'=>"viewCategory();"])}}
    </div>

    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" >
        <label class="control-label" style="min-width: 100%;">Марка</label>
        {{Form::select("matching[object][mark_id]", [0=>'Не выбрано'], getDataIsset($info, 'object', 'mark_id'), ['class' => 'select2-all', "id"=>"object_ts_mark_id", 'style'=>'width: 100%;', 'onchange'=>"getModelsObjectInsurer();"])}}
    </div>

    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" >
        <label class="control-label" style="min-width: 100%;">Модель</label>
        {{Form::select("matching[object][model_id]", [0=>'Не выбрано'], getDataIsset($info, 'object', 'model_id'), ['class' => 'select2-all', "id"=>"object_ts_model_id", 'style'=>'width: 100%;', 'onchange'=>"getYearTariff(this.value);"])}}
    </div>


    <div class="clear"></div>

    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" >
        <label class="control-label" style="min-width: 100%;">Страховая сумма от</label>
        {{Form::text("matching[contract][insurance_amount]", getDataIsset($info, 'contract', 'insurance_amount'), ['class' => 'form-control sum'])}}
    </div>

    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" >
        <label class="control-label" style="min-width: 100%;">Лет авто от</label>
        {{Form::text("matching[object][car_year]", getDataIsset($info, 'object', 'car_year'), ['class' => 'form-control sum'])}}
    </div>


    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
        <label class="control-label">Тип договора</label>
        {{Form::select("matching[contract][is_prolongation]", collect([0=>"Первичный", 1=>'Пролонгация'])->prepend('Не выбрано', -1), getDataIsset($info, 'contract', 'is_prolongation'), ['class' => 'form-control select2-ws'])}}
    </div>

    <div class="clear"></div>


    <div class="clear"></div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
        <label class="control-label" style="min-width: 100%;">Регион страхователя</label>
        {{Form::text("matching[insurer][address_register]", getDataIsset($info, 'insurer', 'address_register'), ['class' => 'form-control ', 'id'=>'insurer_address_register'])}}
        <input name="matching[insurer][address_register_kladr]" id="insurer_address_register_kladr" value="{{getDataIsset($info, 'insurer', 'address_register_kladr')}}" type="hidden"/>
    </div>

    <div class="clear"></div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
        <label class="control-label" style="min-width: 100%;">Регион собственника</label>
        {{Form::text("matching[owner][address_register]", getDataIsset($info, 'owner', 'address_register'), ['class' => 'form-control ', 'id'=>'owner_address_register'])}}
        <input name="matching[owner][address_register_kladr]" id="owner_address_register_kladr" value="{{getDataIsset($info, 'owner', 'address_register_kladr')}}" type="hidden"/>
    </div>


    <div class="clear"></div>


</div>


{{Form::close()}}


@stop

@section('footer')

    @if($matching_id > 0)


        <button class="btn btn-danger pull-left" onclick="deleteMatching()">
            {{ trans('form.buttons.delete') }}
        </button>


    @endif

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop

@section('js')

    <script>

        $(function(){


            $('#insurer_address_register').suggestions({
                serviceUrl: DADATA_AUTOCOMPLETE_URL,
                token: DADATA_TOKEN,
                type: "ADDRESS",
                count: 5,
                onSelect: function (suggestion) {
                    key = $(this).data('key');
                    $('#insurer_address_register').val($(this).val());
                    $('#insurer_address_register_kladr').val(suggestion.data.kladr_id);

                    $('#owner_address_register').val($(this).val());
                    $('#owner_address_register_kladr').val(suggestion.data.kladr_id);
                }
            });

            $('#owner_address_register').suggestions({
                serviceUrl: DADATA_AUTOCOMPLETE_URL,
                token: DADATA_TOKEN,
                type: "ADDRESS",
                count: 5,
                onSelect: function (suggestion) {
                    key = $(this).data('key');
                    $('#owner_address_register').val($(this).val());
                    $('#owner_address_register_kladr').val(suggestion.data.kladr_id);
                }
            });


            @if((int)getDataIsset($info, 'object', 'ts_category') > 0)

                getMarkObjectInsurer({{(int)getDataIsset($info, 'object', 'mark_id')}}, {{(int)getDataIsset($info, 'object', 'model_id')}});
            @endif

        });

        @if($matching_id > 0)
        function deleteMatching() {
            if (!customConfirm()) return false;

            ur = '{{url("/directories/insurance_companies/{$id}/bso_suppliers/{$bso_supplier_id}/hold_kv/{$hold_kv_id}/matching-terms/{$group_id}/{$matching->type}/{$matching->id}")}}';


            $.post(ur, {
                _method: 'delete'
            }, function () {
                parent_reload();
            });
        }

        @endif

        function viewCategory() {

            var options = "<option value='0'>Не выбрано</option>";

            if($('#object_ts_category').val() == 0){

                $("#object_ts_mark_id").html(options).select2('val', 0);
                $("#object_ts_model_id").html(options).select2('val', 0);

            }else{
                getMarkObjectInsurer(0, 0);
            }

        }

        function getMarkObjectInsurer(select_mark_id, select_model_id)
        {

            $('#tableControl').html('');
            $('#dataControl').html('');
            $("#object_ts_model_id").html("<option value='0'>Не выбрано</option>").select2('val', 0);



            $.getJSON("/directories/products/0/edit/special-settings/program/0/kasko/auto/mark", {category_id:$('#object_ts_category').val()}, function (response) {

                is_select = 0;


                var options = "<option value='0'>Не выбрано</option>";
                response.map(function (item) {

                    if(item.isn == select_mark_id){
                        is_select = 1;
                    }

                    options += "<option value='" + item.isn + "'>" + item.title + "</option>";
                });

                if(is_select == 0){
                    select_mark_id = 0;
                }

                $("#object_ts_mark_id").html(options).select2('val', select_mark_id);
                if(select_mark_id > 0){
                    getModelsObjectInsurer(select_model_id);
                }


            });


        }

        function getModelsObjectInsurer(select_model_id)
        {

            $('#tableControl').html('');
            $('#dataControl').html('');



            $.getJSON("/directories/products/0/edit/special-settings/program/0/kasko/auto/models", {category_id:$('#object_ts_category').val(),mark_id:$('#object_ts_mark_id').val()}, function (response) {

                is_select = 0;


                var options = "<option value='0'>Не выбрано</option>";
                response.map(function (item) {

                    if(item.isn == select_model_id){
                        is_select = 1;
                    }

                    options += "<option value='" + item.isn + "'>" + item.title + "</option>";

                });

                if(is_select == 0){
                    select_model_id = 0;
                }

                $("#object_ts_model_id").html(options).select2('val', select_model_id);
            });

        }


    </script>

@stop