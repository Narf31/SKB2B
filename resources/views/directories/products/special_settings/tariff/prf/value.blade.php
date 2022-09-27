<form id="product_form" class="product_form">
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

    <div class="row page-heading">
        <h2 class="inline-h1">{{$json['programs'][$request->program]['title']}}</h2>
    </div>

    <div class="row form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <table class="row table">
            <thead>
            <tr>
                <th nowrap>{{$json['programs'][$request->program]['categorys_tab_title']}}</th>
                @foreach($json['programs'][$request->program]['categorys'][0] as $param1_id => $param1)
                    @if($param1_id > 0)
                    <th>{{$param1}}</th>
                    @endif
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($json['programs'][$request->program]['categorys'][1] as $param2_id => $param2)

                <tr>
                    <td nowrap><span class="hidden">{{$param2_id}}</span>{{$param2}}</td>
                    @foreach($json['programs'][$request->program]['categorys'][0] as $param1_id => $param1)
                        @if(is_string($param1_id) || $param1_id > 0)
                        <td>
                            {{Form::text("value[{$param1_id}][{$param2_id}]",
                            ((isset($json_data['programs'][$request->program]))
                            ?\App\Processes\Tariff\Settings\Product\TariffVzr::getTariffValue($json_data['programs'][$request->program]['values'], $param1_id, $param2_id)
                            :'')
                            , ['class' => 'sum', 'style'=>'width: 80px'])}}
                        </td>
                        @endif
                    @endforeach
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>
    <br/>



</div>

<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <table class="row table">
            <thead>
            <tr>
                <th colspan="3">
                    <h3>Скидки
                        <span class="btn btn-info pull-right" onclick="openPeopleEdit(1)" style="margin-right: 10px;"><i class="fa fa-user"></i></span>
                    </h3>
                </th>
            </tr>
            </thead>
            <tbody id="discount">

            </tbody>
        </table>


    </div>

    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

        <table class="row table">
            <thead>
            <tr>
                <th colspan="3">
                    <h3>Надбавки к тарифам
                        <span class="btn btn-info pull-right" onclick="openPeopleEdit(2)" style="margin-right: 10px;"><i class="fa fa-user"></i></span>
                    </h3>
                </th>
            </tr>
            </thead>
            <tbody id="allowances">

            </tbody>
        </table>

    </div>

</div>

    <div class="clear"></div>
    <span class="btn btn-success pull-left" onclick="saveTariff({{$request->program}})">Сохранить</span>

</form>


<div id="people" class="hidden">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="background-color: white;">
        <div class="form-horizontal">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                <label class="control-label">Возраст от</label>
                {{Form::text("people_from", '', ['class' => 'form-control sum', 'id'=>'people_from', 'style'=>'width: 100%;'])}}
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                <label class="control-label">Возраст до</label>
                {{Form::text("people_to", '', ['class' => 'form-control sum', 'id'=>'people_to', 'style'=>'width: 100%;'])}}
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                <label class="control-label">Группа от</label>
                {{Form::text("people_group", '', ['class' => 'form-control sum', 'id'=>'people_group', 'style'=>'width: 100%;'])}}
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                <label class="control-label">Тариф</label>
                {{Form::text("people_tariff", '', ['class' => 'form-control sum', 'id'=>'people_tariff', 'style'=>'width: 100%;'])}}
            </div>
            <input type="hidden" id="people_type" value="0"/>

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" >
                <span class="btn btn-success pull-right" onclick="setPeople()">Сохранить</span>
            </div>
        </div>
    </div>
</div>



<script>

    _url = "{{$url}}";

    var discounts_txt = '{!! (isset($json_data['programs'][$request->program]))?\App\Processes\Tariff\Settings\Product\TariffVzr::getTariffDiscontTextJson($json_data['programs'][$request->program]):'' !!}';
    var allowances_txt = '{!! (isset($json_data['programs'][$request->program]))?\App\Processes\Tariff\Settings\Product\TariffVzr::getTariffAllowancesTextJson($json_data['programs'][$request->program]):'' !!}';


    function saveTariff(id) {

        loaderShow();

        $.post(_url+"?program="+id, $('#product_form').serialize(), function (response) {

            if (Boolean(response.state) === true) {
                flashMessage('success', "Данные успешно сохранены!");

            }else {
                flashHeaderMessage(response.msg, 'danger');
            }

        }).always(function () {
            loaderHide();
        });

        return true;

    }


    function initActivTable()
    {

        discounts = jQuery.parseJSON(discounts_txt);
        for(var i in discounts) {

            setValueTable(discounts[i].type, discounts[i].tariff, discounts[i].сountry_id, discounts[i].country_name, discounts[i].people_from, discounts[i].people_to, discounts[i].people_group);
        }

        allowances = jQuery.parseJSON(allowances_txt);
        for(var i in allowances) {
            setValueTable(allowances[i].type, allowances[i].tariff, allowances[i].сountry_id, allowances[i].country_name, allowances[i].people_from, allowances[i].people_to, allowances[i].people_group);
        }

    }




    function openPeopleEdit(type)
    {
        //type 1 Скидки 2 Надбавки
        $("#people_type").val(type);
        $.fancybox.open("<div id='fancybox-data'>"+$("#people").html()+"</div>");
        $('.sum')
            .change(function () {
                $(this).val(CommaFormatted($(this).val()));
            })
            .blur(function () {
                $(this).val(CommaFormatted($(this).val()));
            })
            .keyup(function () {
                $(this).val(CommaFormatted($(this).val()));
            });
    }





    function setPeople() {

        type = $("#fancybox-data").find("#people_type").val();
        people_from = $("#fancybox-data").find("#people_from").val();
        people_to = $("#fancybox-data").find("#people_to").val();
        people_group = $("#fancybox-data").find("#people_group").val();
        tariff = $("#fancybox-data").find("#people_tariff").val();
        setValueTable(type, tariff, '', '', people_from, people_to, people_group);

        $.fancybox.close();
    }

    function setValueTable(type, tariff, сountry_id, country_name, people_from, people_to, people_group) {

        txt = '';

        if(type == 1){
            type_name = 'discount';
        }

        if(type == 2){
            type_name = 'allowances';
        }

        if(country_name.length > 0){
            txt = country_name;
        }else{



            if(people_from.length > 0 || people_to.length > 0 ){
                txt = "Возраст";
            }

            if(people_from.length > 0){
                txt += " от "+parseInt(people_from);
            }

            if(people_to.length > 0){
                txt += " до "+parseInt(people_to);
            }

            if(people_group.length > 0){
                txt += "Группа от "+parseInt(people_group);
            }

        }

        index = $("#"+type_name+" tr").children().length+1;


        html = "<tr id='"+type_name+"-"+index+"'><td width='20px;'>" +
            '<span class="pull-left" style="cursor: pointer;color: red;" onclick="remuveData('+"'"+type_name+"-"+index+"'"+')"><i class="fa fa-close"></i></span>' +
            "</td><td>" +
            txt +
            "</td><td>" +
            '<input type="hidden" name="'+type_name+'['+index+'][type]" value="'+type+'"/>' +
            '<input type="hidden" name="'+type_name+'['+index+'][tariff]" value="'+tariff+'"/>' +
            '<input type="hidden" name="'+type_name+'['+index+'][сountry_id]" value="'+сountry_id+'"/>' +
            '<input type="hidden" name="'+type_name+'['+index+'][country_name]" value="'+country_name+'"/>' +
            '<input type="hidden" name="'+type_name+'['+index+'][people_from]" value="'+people_from+'"/>' +
            '<input type="hidden" name="'+type_name+'['+index+'][people_to]" value="'+people_to+'"/>' +
            '<input type="hidden" name="'+type_name+'['+index+'][people_group]" value="'+people_group+'"/>' +
            '<span class="pull-right">'+CommaFormatted(tariff)+'</span>' +
            "</td></tr>";
        $("#"+type_name).append(html);

    }

    function remuveData(id) {
        $("#"+id).remove();
    }

</script>