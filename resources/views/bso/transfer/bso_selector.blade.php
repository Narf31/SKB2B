

<div style="padding: 5px; margin-bottom: 10px;">
    <table class="table table-bordered bso_items_table" >
        <tr>
            <th>Тип</th>
            <th>Серия</th>
            <th>Кол-во</th>
            <th class="bso_number_td">№ полиса / квит. / сер.карт с</th>
            <th class="bso_number_td">№ по</th>
            <th>Удалить</th>
        </tr>
        <tr class="table_row row_1" data-index="1" completed="0">
            <td>
                <input type="hidden" class="sk_user_id" value="{{$bso_supplier_id}}"/>
                {{ Form::select('type_selector', $bso_type->prepend('Выберите значение', 0), 0, ['class' => 'form-control type_selector']) }}
                <div class="error_div"></div>
            </td>
            <td>
                <select class="series_selector form-control " name="series_selector"></select>
            </td>
            <td>
                <input type="text" class="bso_qty intmask" onchange="selectBsoQty(this)" />
                <div class="error_div"></div>
            </td>
            <td>
                <input type="text" class="bso_number" name="bso_number" />
                <div class="error_div"></div>
            </td>
            <td><span class="bso_number_to">&nbsp;</span></td>

            <td style="text-align: center;">
                <span class="remove_string_button" style="color: red;font-size: 18px;"><i class="fa fa-close"></i></span>
            </td>
        </tr>

        <tr class="table_row row_2" data-index="2" completed="0">
            <td>
                <input type="hidden" class="sk_user_id" value="{{$bso_supplier_id}}"/>
                {{ Form::select('type_selector', $bso_type->prepend('Выберите значение', 0), 0, ['class' => 'form-control type_selector']) }}
                <div class="error_div"></div>
            </td>
            <td>
                <select class="series_selector form-control " name="series_selector"></select>
            </td>
            <td>
                <input type="text" class="bso_qty intmask"  onchange="selectBsoQty(this)"/>
                <div class="error_div"></div>
            </td>
            <td>
                <input type="text" id="bso_number" name="bso_number" class="bso_number" />
                <div class="error_div"></div>
            </td>
            <td><span class="bso_number_to">&nbsp;</span></td>

            <td style="text-align: center;">
                <span class="remove_string_button" style="color: red;font-size: 18px;"><i class="fa fa-close"></i></span>
            </td>
        </tr>


    </table>

</div>


<input class="btn btn-success btn-right" type="button" value="Добавить строку" style="cursor: pointer;" onclick="addRowSelector()" />
<input type="button" value="Поместить в корзину" class="save_transmit_bso btn btn-primary btn-left"/>




<textarea class="new_tr" style="display: none;" >

    <tr class="table_row row_[:KEY:]" data-index="[:KEY:]" completed="0">
        <td>
            <input type="hidden" class="sk_user_id" value="{{$bso_supplier_id}}"/>
            {{ Form::select('type_selector', $bso_type->prepend('Выберите значение', 0), 0, ['class' => 'form-control type_selector']) }}
            <div class="error_div"></div>
        </td>
        <td>
            <select class="series_selector form-control " name="series_selector"></select>
        </td>
        <td>
            <input type="text" class="bso_qty intmask"  onchange="selectBsoQty(this)"/>
            <div class="error_div"></div>
        </td>
        <td>
            <input type="text" class="bso_number" name="bso_number" />
            <div class="error_div"></div>
        </td>
        <td><span class="bso_number_to">&nbsp;</span></td>
        <td style="text-align: center;">
            <span class="remove_string_button" style="color: red;font-size: 18px;"><i class="fa fa-close"></i></span>
        </td>
    </tr>

</textarea>
<br/><br/>


<script>
    var bso_used = [];

    var current_select = '';

    var select = '';



    startContractFunctions(1);
    startContractFunctions(2);

    function startContractFunctions(key){

        selectorSearchClearBso("bso_number", key, 1);

    }



    function selectorSelectClearBso(object, key, suggestion) {

        var data = suggestion.data;
        var object = $('#rit_bsos .row_'+key);

        object.find("[name*='type_selector']").val(data.type_bso_id);

        var options = "<option value='0'>Не выбрано</option>";
        data.select_bso_serie.map(function (item) {
            options += "<option value='" + item.id + "'>" + item.bso_serie + "</option>";
        });
        object.find("[name*='series_selector']").html(options);
        object.find("[name*='series_selector']").val(data.bso_serie_id);
        object.find("[name*='bso_number']").val(data.bso_number);

    }



    //ПОИСК ЧИСТЫХ БСО
    function selectorSearchClearBso(object_id, key, type) {

        var object = $('#rit_bsos .row_'+key);
        object_bso = $('#rit_bsos .row_'+key+' .'+object_id);


        object_bso.suggestions({
            serviceUrl: "/bso/actions/get_clear_bso/",
            type: "PARTY",
            params: {
                type_selector: object.find("[name*='type_selector']").val(),
                series_selector: object.find("[name*='series_selector']").val(),
                bso_number: object.find("[name*='bso_number']").val(),
                bso_supplier_id: '{{$bso_supplier_id}}',
                point_sale: $("#point_sale").val(),
                bso_agent_id: -1
            },
            count: 5,
            minChars: 3,
            formatResult: function (e, t, n, i) {
                var s = this;

                var title = n.value;
                var bso_type = n.data.bso_type;
                var bso_sk = n.data.bso_sk;
                var agent_name = n.data.agent_name;

                var view_res = title;
                view_res += '<div class="' + s.classes.subtext + '">' + bso_type + "</div>";
                view_res += '<div class="' + s.classes.subtext + '">' + agent_name + "</div>";

                return view_res;
            },
            onSelect: function (suggestion) {

                selectorSelectClearBso(object, key, suggestion);
                return true;
            }
        });
    }


    function selectBsoType(bso_type)
    {
        bso_type_id = $(bso_type).val();
        bso_supplier_id = $('#bso_supplier_id').val();

        $.getJSON('/bso/actions/get_series/', {bso_type_id: bso_type_id, bso_supplier_id:bso_supplier_id}, function (response) {

            var options = "<option value='0'>Не выбрано</option>";
            response.map(function (item) {
                options += "<option value='" + item.id + "'>" + item.bso_serie + "</option>";
            });

            $(bso_type).parent().siblings().children('select.series_selector').html(options);
            $(bso_type).parent().siblings().children('select.series_selector2').html(options);


        });


        var tr = $(bso_type).parents('tr')[0];
        var key = $(tr).data('index');
        var val = $('#rit_bsos .row_'+key+' .bso_number').val();

        startContractFunctions(key);

    }


    function selectBsoQty(bso_qty)
    {

        bso_qty_count = $(bso_qty).val();
        bso_num = $(bso_qty).parent().siblings().children('.bso_number').val();
        $(bso_qty).parent().siblings().children('.bso_number_to').html(selectBsoNumberOrQty(bso_qty_count, bso_num));

    }

    function selectBsoNumber(bso_number)
    {
        bso_num = $(bso_number).val();
        bso_qty_count = $(bso_number).parent().siblings().children('.bso_qty').val();
        $(bso_number).parent().siblings().children('.bso_number_to').html(selectBsoNumberOrQty(bso_qty_count, bso_num));
    }

    function selectBsoNumberOrQty(bso_qty_count, bso_num)
    {
        if(parseInt(bso_qty_count) >0 && parseInt(bso_num) > 0){
            return myGetAjax('/bso/actions/bso_number_to/?bso_qty='+bso_qty_count+"&bso_num="+bso_num);
        }
        return '';
    }


    var KEY_ROW = 2;

    String.prototype.replaceAll = function (search, replace) {
        return this.split(search).join(replace);
    }

    function addRowSelector() {

        KEY_ROW = KEY_ROW+1;
        // Создаем элемент
        myHtml = $('.new_tr').val();
        myHtml = myHtml.replaceAll('[:KEY:]', KEY_ROW);

        // Помещаем в таблицу
        $('.bso_items_table tbody').append(myHtml);

        startContractFunctions(KEY_ROW);

        $(".type_selector").on('change', function () {
            selectBsoType(this);
        });

        $(".bso_number").on('change', function () {
            selectBsoNumber(this);
        });

        $(".bso_qty").on('change', function () {
            selectBsoQty(this);
        });

        $(document).on(
            "click",
            ".remove_string_button",
            function () {

                $(this).parent().parent().remove();
            }
        );



    }


</script>