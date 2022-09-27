@php

    $spec = \App\Models\Directories\Products\ProductsSpecialSsettings::where('product_id', $product->id)->where('program_id', $program->id)->get()->first();
    $info = null;
    if($spec && $spec->json && strlen($spec->json) > 0) $info = json_decode($spec->json);

@endphp


    <div class="row form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12">



        {{ Form::open(['url' => url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/kasko/default/"), 'method' => 'post', "id" =>"product_form"]) }}

        <div class="row form-equally">

            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <label class="control-label">Страховая сумма ДО</label>
                {{ Form::text("kasko[damage][insurance_amount]", ($info)?($info->damage->insurance_amount):'', ['class' => 'form-control sum']) }}
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <label class="control-label">Ущерб %</label>
                {{ Form::text("kasko[damage][tariff]", ($info)?($info->damage->tariff):'', ['class' => 'form-control sum']) }}
            </div>

        </div>


        <div class="row form-equally">

            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <label class="control-label">Страховая сумма ДО</label>
                {{ Form::text("kasko[hijackinge][insurance_amount]", ($info)?($info->hijackinge->insurance_amount):'', ['class' => 'form-control sum']) }}
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <label class="control-label">Угон %</label>
                {{ Form::text("kasko[hijackinge][tariff]", ($info)?($info->hijackinge->tariff):'', ['class' => 'form-control sum']) }}
            </div>

        </div>



        <div class="row form-equally">

            <div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
                <label class="control-label" style="max-width: none;">Уведомления на Email в случае ошибки</label>
                {{ Form::text("kasko[error][email]", ($info)?$info->error->email:'', ['class' => 'form-control']) }}
            </div>


        </div>

        <div class="row form-equally">

            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <label class="control-label">Согласования андеррайтера</label>
                {{ Form::select("kasko[matching][underwriter]", collect([0=>'Нет', 1=>'Да']),($info && isset($info->matching))?($info->matching->underwriter):'', ['class' => 'form-control select2-ws', 'onchange' => "showhidenum('underwriter')"]) }}
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3" id="underwriter_block" @if(!($info && isset($info->matching) && $info->matching->underwriter == 1)) style="display:none;" @endif>
                <label class="control-label">Очередь</label>
                {{ Form::select("kasko[matching][underwriter_num]",  collect(['0'=>'Не выбрано','1'=>'1', '2'=>'2', '3'=>'3']), ($info && isset($info->matching))?($info->matching->underwriter_num):'', ['class' => 'form-control select2-ws']) }}
            </div>

        </div>

        <div class="row form-equally">

            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <label class="control-label">Согласования СБ</label>
                {{ Form::select("kasko[matching][sb]", collect([0=>'Нет', 1=>'Да']),($info && isset($info->matching))?($info->matching->sb):'', ['class' => 'form-control select2-ws', 'onchange' => "showhidenum('sb')"]) }}
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3" id="sb_block" @if(!($info && isset($info->matching) && $info->matching->sb == 1)) style="display:none;" @endif>
                <label class="control-label">Очередь</label>
                {{ Form::select("kasko[matching][sb_num]", collect(['0'=>'Не выбрано','1'=>'1', '2'=>'2', '3'=>'3']), ($info && isset($info->matching))?($info->matching->sb_num):'', ['class' => 'form-control select2-ws']) }}
            </div>

        </div>

        <div class="row form-equally">

            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <label class="control-label">Осмотр</label>
                {{ Form::select("kasko[matching][inspection]", collect([0=>'Нет', 1=>'Да']),($info && isset($info->matching))?($info->matching->inspection):'', ['class' => 'form-control select2-ws', 'onchange' => "showhidenum('inspection')"]) }}
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3" id="inspection_block" @if(!($info && isset($info->matching) && $info->matching->inspection == 1)) style="display:none;" @endif>
                <label class="control-label">Очередь</label>
                {{ Form::select("kasko[matching][inspection_num]", collect(['0'=>'Не выбрано','1'=>'1', '2'=>'2', '3'=>'3']), ($info && isset($info->matching))?($info->matching->inspection_num):'', ['class' => 'form-control select2-ws']) }}
            </div>

        </div>


        <div @if($program->slug == 'standard') style="display: none;" @endif>

            <div class="row form-equally">
                <div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
                    <label class="control-label" style="max-width: none;">Максимальная страховая сумма</label>
                    {{ Form::text("kasko[terms][insurance_amount]", ($info)?($info->terms->insurance_amount):'', ['class' => 'form-control sum']) }}
                </div>
            </div>

            <div class="row form-equally">
                <div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
                    <label class="control-label">Покрытия и риски</label>
                    {{ Form::select("kasko[terms][coatings_risks_id]", \App\Models\Directories\Products\Data\Kasko\Standard::COATINGS_RISKS , ($info)?$info->terms->coatings_risks_id:'', ['class' => 'form-control select2-ws']) }}
                </div>
            </div>

            <div class="row form-equally">
                <div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
                    <label class="control-label">Территория страхования</label>
                    {{ Form::select("kasko[terms][territory_id]", \App\Models\Directories\Products\Data\Kasko\Standard::TERRIRORY , ($info)?$info->terms->territory_id:'', ['class' => 'form-control select2-ws']) }}
                </div>
            </div>

            <div class="row form-equally">
                <div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
                    <label class="control-label">Варианты ремонта</label>
                    {{ Form::select("kasko[terms][repair_options_id]", \App\Models\Directories\Products\Data\Kasko\Standard::REPAIR_OPTIONS , ($info)?$info->terms->repair_options_id:'', ['class' => 'form-control select2-ws']) }}
                </div>
            </div>

        </div>


        <div class="row form-equally">
            <div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
                <span class="btn btn-success pull-left" onclick="saveDefault()">Сохранить</span>
            </div>
        </div>


        {{ Form::close() }}


    </div>





<script>




    function initViewForm() {

    }

    function showhidenum(match) {

        if($("[name='kasko[matching]["+match+"]']").val() == 1){
            $("#"+match+"_block").show();
        }else{
            $("#"+match+"_block").hide();
            $("[name='kasko[matching]["+match+"_num]']").select2("val", '0');
        }
    }

    function saveDefault() {

        var data = checkNum();

        if(data.status) {
            loaderShow();


            $.post('{{url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/kasko/default/")}}', $('#product_form').serialize(), function (response) {

                flashMessage('success', "Данные успешно сохранены!");


            }).always(function () {
                loaderHide();
            });
        }else{
            flashMessage('danger', data.mes);
        }
    }

    function checkNum(){
        var underwriter_num = $("[name='kasko[matching][underwriter_num]']").val();
        var sb_num = $("[name='kasko[matching][sb_num]']").val();
        var inspection_num = $("[name='kasko[matching][inspection_num]']").val();

        if(($("[name='kasko[matching][underwriter]']").val() == 1 && underwriter_num == 0) ||
            ($("[name='kasko[matching][sb]']").val() == 1 && sb_num == 0) ||
            ($("[name='kasko[matching][inspection]']").val() == 1 && inspection_num == 0)){
            return {
                status: false,
                mes: "Очередь не выбрана!"
            };
        }
        if(underwriter_num != 0 && (underwriter_num == sb_num || underwriter_num == inspection_num)) {
            return {
                status: false,
                mes: "Очереди совпадают! Измените очередность согласования!"
            };
        }
        if(sb_num != 0 && sb_num == inspection_num) {
            return {
                status: false,
                mes: "Очереди совпадают! Измените очередность согласования!"
            };
        }

        return {
            status: true,
            mes: ""
        };
    }

</script>
