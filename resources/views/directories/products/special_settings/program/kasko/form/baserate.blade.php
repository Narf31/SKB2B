

<div class="row form-horizontal">

    <div class="col-md-6 col-lg-3">
        <label class="control-label">Категория <span class="required">*</span></label>
        {{Form::select("contract[object][ts_category]", \App\Models\Vehicle\VehicleCategories::query()->where('is_actual', 1)->get()->pluck('title', 'id'), 2, ['class' => 'form-control select2-ws', 'id'=>"object_ts_category", 'onchange'=>"viewCategory();"])}}
    </div>

    <div class="col-md-6 col-lg-3" >
        <label class="control-label">Марка <span class="required">*</span></label>
        {{Form::select("contract[object][mark_id]", [0=>'Не выбрано'], null, ['class' => 'select2-all', "id"=>"object_ts_mark_id", 'style'=>'width: 100%;', 'onchange'=>"getModelsObjectInsurer();"])}}
    </div>

    <div class="col-md-6 col-lg-3" >
        <label class="control-label">Модель <span class="required">*</span></label>
        {{Form::select("contract[object][model_id]", [0=>'Не выбрано'], null, ['class' => 'select2-all', "id"=>"object_ts_model_id", 'style'=>'width: 100%;', 'onchange'=>"getYearTariff(this.value);"])}}
    </div>

</div>


<table id="tableControl" class="tov-table">

</table>

<div id="dataControl"></div>


<script>


    function initViewForm() {

        viewCategory();

    }


    function viewCategory() {

        getMarkObjectInsurer(0, 0);

    }

    function getMarkObjectInsurer(select_mark_id, select_model_id)
    {

        $('#tableControl').html('<tr><td>Название</td><td>БТС ущерб</td><td>БТС тоталь %</td><td>БТС хищение %</td></tr>');
        $('#dataControl').html('');

        $.getJSON("/directories/products/{{$product->id}}/edit/special-settings/program/{{$program->id}}/kasko/auto/mark", {category_id:$('#object_ts_category').val()}, function (response) {

            is_select = 0;


            var options = "<option value='0'>Не выбрано</option>";
            response.map(function (item) {

                if(item.id == select_mark_id){
                    is_select = 1;
                }

                options += "<option value='" + item.id + "'>" + item.title + "</option>";
                viewHtmlTablMarkOrModels(item.id, 0, item.title, item);
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

        $('#tableControl').html('<tr><td>Название</td><td>БТС ущерб</td><td>БТС тоталь %</td><td>БТС хищение %</td></tr>');
        $('#dataControl').html('');



        $.getJSON("/directories/products/{{$product->id}}/edit/special-settings/program/{{$program->id}}/kasko/auto/models", {category_id:$('#object_ts_category').val(),mark_id:$('#object_ts_mark_id').val()}, function (response) {

            is_select = 0;


            var options = "<option value='0'>Не выбрано</option>";
            response.map(function (item) {

                if(item.id == select_model_id){
                    is_select = 1;
                }

                options += "<option value='" + item.id + "'>" + item.title + "</option>";
                viewHtmlTablMarkOrModels($('#object_ts_mark_id').val(), item.id, item.title, item);

            });

            if(is_select == 0){
                select_model_id = 0;
            }

            $("#object_ts_model_id").html(options).select2('val', select_model_id);
        });

    }


    function viewHtmlTablMarkOrModels(select_mark_id, select_model_id, title, item)
    {

        if(select_mark_id > 0 && select_model_id > 0){
            tableVal = '<tr><td style="cursor: pointer;" onclick="getYearTariff('+select_model_id+')">'+title+'</td>'+getHTMLDefault(item)+'</tr>';
        }else{
            tableVal = '<tr><td style="cursor: pointer;" onclick="setMark('+select_mark_id+')">'+title+'</td>'+getHTMLDefault(item)+'</tr>';
        }


        $('#tableControl').append(tableVal);
        initTextControll();

    }


    function setMark(select_mark_id) {

        getMarkObjectInsurer(select_mark_id, 0);

    }

    function getYearTariff(select_model_id) {

        select_mark_id = $('#object_ts_mark_id').val();

        $("#object_ts_model_id").select2('val', select_model_id);

        if(select_model_id == 0){
            getMarkObjectInsurer(select_mark_id, 0);
            return;
        }


        $('#tableControl').html('');
        $('#dataControl').html('');

        $.get("/directories/products/{{$product->id}}/edit/special-settings/program/{{$program->id}}/kasko/auto/baserate",
            {
                mark_id:select_mark_id,
                model_id:select_model_id
            },
            function (response) {

                $('#dataControl').html(response);
                initBaseRate();


            });

    }

    function getHTMLDefault(item) {
        _HTML = '<td>' +
            '<input name="baserate['+item.id+'][payment_damage]" type="text" value="'+notNullVal(item.payment_damage)+'" class="sum" onchange="saveBaserateDefault('+item.id+')"/>' +
            '</td><td>' +
            '<input name="baserate['+item.id+'][total]" type="text" value="'+notNullVal(item.total)+'" class="sum" onchange="saveBaserateDefault('+item.id+')"/>' +
            '</td><td>' +
            '<input name="baserate['+item.id+'][theft]" type="text" value="'+notNullVal(item.theft)+'" class="sum"  onchange="saveBaserateDefault('+item.id+')"/>' +
            '</td>';

        return _HTML;
    }

    function notNullVal(val) {

        if(!val){
            return '';
        }
        return CommaFormatted(val);
    }


    function saveBaserateDefault(isn) {

        payment_damage = $("input[name='baserate["+isn+"][payment_damage]']").val();
        total = $("input[name='baserate["+isn+"][total]']").val();
        theft = $("input[name='baserate["+isn+"][theft]']").val();


        $.get("/directories/products/{{$product->id}}/edit/special-settings/program/{{$program->id}}/kasko/auto/def-baserate",
            {   category_id:$('#object_ts_category').val(),
                mark_id:$('#object_ts_mark_id').val(),
                payment_damage: payment_damage,
                total: total,
                theft: theft,
                isn: isn

            }, function (response) {


        });


    }

</script>
