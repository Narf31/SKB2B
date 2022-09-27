@php

    $spec = \App\Models\Directories\Products\ProductsSpecialSsettings::where('product_id', $product->id)->where('program_id', $program->id)->get()->first();
    $info = null;
    if($spec && $spec->json && strlen($spec->json) > 0) $info = json_decode($spec->json);

@endphp


<div class="page-heading product_form">
    <h2 class="inline-h1">Согласования</h2>
</div><br/>

    <div class="row form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12">



        {{ Form::open(['url' => url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/arbitration/default/"), 'method' => 'post', "id" =>"product_form"]) }}

        <div class="row">

            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <label class="control-label">Согласования андеррайтера</label>
                {{ Form::select("arbitration[matching][underwriter]", collect([0=>'Нет', 1=>'Да']),($info && isset($info->matching))?($info->matching->underwriter):'', ['class' => 'form-control select2-ws', 'onchange' => "showhidenum('underwriter')"]) }}
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3" id="underwriter_block" @if(!($info && isset($info->matching) && $info->matching->underwriter == 1)) style="display:none;" @endif>
                <label class="control-label">Очередь</label>
                {{ Form::select("arbitration[matching][underwriter_num]",  collect(['0'=>'Не выбрано','1'=>'1', '2'=>'2', '3'=>'3']), ($info && isset($info->matching))?($info->matching->underwriter_num):'', ['class' => 'form-control select2-ws']) }}
            </div>

        </div>

        <div class="row">

            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                <label class="control-label">Согласования СБ</label>
                {{ Form::select("arbitration[matching][sb]", collect([0=>'Нет', 1=>'Да']),($info && isset($info->matching))?($info->matching->sb):'', ['class' => 'form-control select2-ws', 'onchange' => "showhidenum('sb')"]) }}
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3" id="sb_block" @if(!($info && isset($info->matching) && $info->matching->sb == 1)) style="display:none;" @endif>
                <label class="control-label">Очередь</label>
                {{ Form::select("arbitration[matching][sb_num]", collect(['0'=>'Не выбрано','1'=>'1', '2'=>'2', '3'=>'3']), ($info && isset($info->matching))?($info->matching->sb_num):'', ['class' => 'form-control select2-ws']) }}
            </div>

        </div>
        

        <div class="row">
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

        if($("[name='arbitration[matching]["+match+"]']").val() == 1){
            $("#"+match+"_block").show();
        }else{
            $("#"+match+"_block").hide();
            $("[name='arbitration[matching]["+match+"_num]']").select2("val", '0');
        }
    }

    function saveDefault() {

        var data = checkNum();

        if(data.status) {
            loaderShow();


            $.post('{{url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/arbitration/default/")}}', $('#product_form').serialize(), function (response) {

                flashMessage('success', "Данные успешно сохранены!");


            }).always(function () {
                loaderHide();
            });
        }else{
            flashMessage('danger', data.mes);
        }
    }

    function checkNum(){
        var underwriter_num = $("[name='arbitration[matching][underwriter_num]']").val();
        var sb_num = $("[name='arbitration[matching][sb_num]']").val();
        var inspection_num = $("[name='arbitration[matching][inspection_num]']").val();

        if(($("[name='arbitration[matching][underwriter]']").val() == 1 && underwriter_num == 0) ||
            ($("[name='arbitration[matching][sb]']").val() == 1 && sb_num == 0) ||
            ($("[name='arbitration[matching][inspection]']").val() == 1 && inspection_num == 0)){
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
