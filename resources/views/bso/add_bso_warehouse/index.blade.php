@extends('layouts.app')



@section('content')


    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="page-subheading">
            <h2>Добавление БСО на склад</h2>
        </div>
        <div class="block-main">
            <div class="block-sub">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label class="col-sm-12 control-label">Филиал</label>
                            <div class="col-sm-12">
                                {{ Form::select('bso_supplier_id', \App\Models\Directories\BsoSuppliers::where('is_actual', 1)->get()->pluck('title', 'id'), $bso_supplier_id, ['class' => 'form-control select2-all', 'id'=>'bso_supplier_id', 'required']) }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label class="col-sm-12 control-label">Точка продаж</label>
                            <div class="col-sm-12">
                                {{ Form::select('point_sale_id', \App\Models\Settings\PointsSale::where('is_actual', 1)->get()->pluck('title', 'id'), 1, ['class' => 'form-control select2-ws point_sale_id', 'id'=>'point_sale_id', 'required']) }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label class="col-sm-12 control-label">Номер акта</label>
                            <div class="col-sm-12">
                                {{ Form::text('act_number', '', ['class' => 'form-control', 'id'=>'act_number', 'placeholder' => 'присваивается автоматически']) }}
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label class="col-sm-12 control-label">Дата принятия</label>
                            <div class="col-sm-12">
                                {{ Form::text('time_create', date("d.m.Y"), ['class' => 'form-control datepicker date', 'id'=>'time_create']) }}
                                <span class="error_span time_create_err"></span>
                            </div>
                        </div>

                        <input type="hidden" name="agent_id" id="agent_id" value="0"/>


                    </div>



                </div>



                <div class="row" style="padding: 10px; margin-bottom: 10px;">
                    <table class="bso_table">
                        <tr>
                            <th rowspan="2">Тип</th>
                            <th colspan="5">
                                <b>Бланки строгой отчетности</b>
                                <br/>
                                (ввод бсо по номеру договора страхования - верхний)
                            </th>

                            <th rowspan="2" style="width: 80px;">
                                <span id="add_string_button" style="cursor:pointer; color: green;font-size: 24px;padding: 5px 5px 0px 25px;position: absolute;">
                                    <i class="fa fa-plus"></i>
                                </span>
                            </th>
                        </tr>

                        <tr>
                            <th>Серия</th>
                            <th>Кол-во</th>
                            <th class="bso_number_td">№ полиса / квит. / сер.карт с</th>
                            <th class="bso_number_td">№ по</th>
                            <th>Доп. серия</th>
                        </tr>


                        <tr class="table_row" completed="0">
                            <td class="bso_type_td">
                                {{ Form::select('bso_type', $bso_type->prepend('Выберите значение', 0), 0, ['class' => 'form-control select2-ws bso_type', 'onchange'=>'selectBsoType(this)']) }}
                                <div class="error_div"></div>
                            </td>
                            <td class="bso_series_td">
                                <select class="series_selector select2-ws form-control" onchange="selectBsoDopSeries(this)"><option value='0'>Не выбрано</option></select>
                                <div class="error_div"></div>
                            </td>
                            <td class="bso_qty_td">
                                <input type="text" class="bso_qty intmask form-control" onchange="selectBsoQty(this)"/>
                                <div class="error_div"></div>
                            </td>
                            <td>
                                <input type="text" class="bso_number form-control" onchange="selectBsoNumber(this)"/>
                                <div class="error_div"></div>
                            </td>
                            <td><input class="bso_number_to form-control" readonly/> </td>
                            <td>
                                <select class="dop_series_selector select2-ws form-control"><option value='0'>Не выбрано</option></select>
                            </td>
                            <td style="text-align: center;"><span class="remove_string_button" style="color: red;font-size: 18px;"><i class="fa fa-close"></i></span></td>

                        </tr>


                    </table>

                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="btn btn-primary btn-left" id="save_button" onclick="save_button_click(0)">Принять БСО</div>
                    <div id="acts_div" style="display: none;"></div>
                    </div>
                </div>

                <hr/>



            </div>
        </div>
    </div>



<textarea class="new_tr hidden">
    <td class="bso_type_td">
        {{ Form::select('bso_type', $bso_type->prepend('Выберите значение', 0), 0, ['class' => 'form-control bso_type select2-ws', 'onchange'=>'selectBsoType(this)']) }}

        <div class="error_div"></div>
    </td>
    <td class="bso_series_td">
        <select class="series_selector select2-ws form-control" onchange="selectBsoDopSeries(this)"><option value='0'>Не выбрано</option></select>
        <div class="error_div"></div>
    </td>
    <td class="bso_qty_td">
        <input type="text" class="bso_qty intmask form-control" onchange="selectBsoQty(this)"/>
        <div class="error_div"></div>
    </td>
    <td>
        <input type="text" class="bso_number form-control" onchange="selectBsoNumber(this)"/>
        <div class="error_div"></div>
    </td>
    <td><input class="bso_number_to form-control" readonly/> </td>
    <td>
        <select class="dop_series_selector form-control select2-ws"><option value='0'>Не выбрано</option></select>
    </td>
    <td style="text-align: center;"><span class="remove_string_button" style="color: red;font-size: 18px;"><i class="fa fa-close"></i></span></td>

</textarea>

<div id="loading_div" style="position: absolute; top: 0px; left: 0px; width: 100%; height: 100%; background-color: #333; opacity: 0.7; display: none;">
    <div style="position: absolute; width: 260px; height: 30px; font: 27px arial; color: #FFF; left: 50%; margin-left: -130px; top: 50%; margin-top: -15px;">Обработка
        данных...
    </div>
</div>	</div>
</div>

@stop

@section('js')

    <style>

        .bso_table {
            font: 12px arial;
            border: 1px solid #777;
            border-collapse: collapse;
        }

        .bso_table td, th {
            border: 1px solid #777;
            padding: 5px;
            font: 12px arial;
            background-color: #FFF;
        }

        .bso_table th {
            background-color: #EEE;
        }

        .bso_header {
            font: 12px arial;
            border: none;
            border-collapse: collapse;
            width: 100%;
        }

        .bso_header td {
            padding: 5px;
            border: none;
            font: 12px arial;
            background-color: #F3F3F3;
        }

        .bso_qty {
            width: 80px;
        }

        .bso_number, .bso_blank_number {
            width: 180px;
        }

        .bso_number_td {
            width: 180px;
        }

        .bso_type_td {
            width: 250px;
        }

        .bso_series_td {
            width: 150px;
        }


        .error_div, .error_span {
            color: red;
        }

        .remove_string_button {
            cursor: pointer;
        }

    </style>


    <script type="text/javascript">


        var bso_act_id = 0;

        $(function() {


            $(document).on(
                "click",
                ".remove_string_button",
                function(){

                    $(this).parent().parent().remove();
                }
            );


            $(document).on(
                "click",
                ".ignore_errors",
                function(){
                    save_button_click(1);
                }
            );


            $('#add_string_button').click(function() {
                // Создаем элемент
                var el = $('<tr>', {
                    //id: 'chat_1',
                    class: 'table_row',
                    completed: '0'
                });
                //el.attr('completed', '0');
                el.html($('.new_tr').val());

                el.children('.blank_td').children().prop('disabled', true);
                el.children('.blank_td').children().css('opacity', 0.4);

                // Помещаем в таблицу
                $('.bso_table').append(el);

                $('.select2-ws').select2("destroy").select2({
                    width: '100%',
                    dropdownCssClass: "bigdrop",
                    dropdownAutoWidth: true,
                    minimumResultsForSearch: -1
                });

                //str_position++;
            });


            $('#bso_supplier_id').on('change', function() {
                var tp_id = $('select.point_sale_id').val();
                window.location = '?point_sale_id=' + tp_id + '&bso_supplier_id=' + $(this).val();
            });



            $('#add_string_button').click();



        });




        function selectBsoType(bso_type){
            bso_type_id = $(bso_type).val();
            bso_supplier_id = $('#bso_supplier_id').val();

            $.getJSON('{{url('/bso/actions/get_series/')}}', {bso_type_id: bso_type_id, bso_supplier_id:bso_supplier_id}, function (response) {

                var options = "<option value='0'>Не выбрано</option>";
                response.map(function (item) {
                    options += "<option value='" + item.id + "'>" + item.bso_serie + "</option>";
                });

                $(bso_type).parent().siblings().children('select.series_selector').html(options);
                $(bso_type).parent().siblings().children('select.series_selector2').html(options);


            });

        }

        function selectBsoDopSeries(series){
            series_id = $(series).val();

            $.getJSON('{{url('/bso/actions/get_dop_series/')}}', {series_id: series_id}, function (response) {

                var options = "<option value='0'>Не выбрано</option>";
                response.map(function (item) {
                    options += "<option value='" + item.id + "'>" + item.bso_dop_serie + "</option>";
                });

                $(series).parent().siblings().children('select.dop_series_selector').html(options);

            });

        }



        function selectBsoQty(bso_qty){

            bso_qty_count = $(bso_qty).val();
            bso_num = $(bso_qty).parent().siblings().children('.bso_number').val();
            $(bso_qty).parent().siblings().children('.bso_number_to').val(selectBsoNumberOrQty(bso_qty_count, bso_num));

            bso_blank_number = $(bso_qty).parent().siblings().children('.bso_blank_number').val();
            if(bso_blank_number.length > 0){
                $(bso_qty).parent().siblings().children('.bso_blank_number_to').val(selectBsoNumberOrQty(bso_qty_count, bso_blank_number));
            }

        }

        function selectBsoNumber(bso_number){
            bso_num = $(bso_number).val();
            bso_qty_count = $(bso_number).parent().siblings().children('.bso_qty').val();
            $(bso_number).parent().siblings().children('.bso_number_to').val(selectBsoNumberOrQty(bso_qty_count, bso_num));

        }

        function selectBsoNumberOrQty(bso_qty_count, bso_num)
        {
            return myGetAjax('{{url('/bso/actions/bso_number_to/')}}?bso_qty='+bso_qty_count+"&bso_num="+bso_num);
        }



        function save_button_click(ignore_errors)
        {
            errors_qty = 0;

            $('.time_create_err').html('');

            if ($('#time_create').val() == '')
            {
                $('.time_create_err').html('Не указана дата приема');
                errors_qty++;
            }
            if (errors_qty > 0) return 0;

            $('#save_button').hide();
            $('#loading_div').fadeIn(300);


            setTimeout(function(){


                if (bso_act_id == 0)
                {
                    // создать акт
                    bso_act_id = myGetAjax('{{url('/bso/actions/create_transfer_act/')}}?act_number=' + $('#act_number').val() + '&bso_supplier_id=' + $('#bso_supplier_id').val()+ '&point_sale_id=' + $('#point_sale_id').val());
                }


                var obj = new Object();
                $('.table_row').each(function() {
                    if ($(this).attr('completed') == '0')
                    {
                        obj.ignore_errors = ignore_errors;
                        $(this).find('.error_div').html('');
                        $(this).find('td:nth-child(2)').children('.error_div').html('');

                        obj.bso_type_id = $(this).find('td:nth-child(1)').children('select').val();
                        //obj.bso_type_value = $(this).find('td:nth-child(1)').find('option:selected').html();

                        obj.bso_serie_id = $(this).find('td:nth-child(2)').children('select').val();
                        obj.bso_serie_value = $(this).find('td:nth-child(2)').find('option:selected').html();

                        obj.bso_qty = $(this).find('td:nth-child(3)').children('input').val();

                        obj.bso_number_from = $(this).find('td:nth-child(4)').children('input').val();

                        obj.bso_dop_serie_id = $(this).find('td:nth-child(6)').children('select').val();
                        obj.bso_dop_serie_value = $(this).find('td:nth-child(6)').find('option:selected').html();

                        obj.bso_blank_enabled = 0;
                        obj.bso_blank_serie_id = 0;
                        obj.bso_blank_serie_value = '';
                        obj.bso_blank_number_from = '';
                        obj.bso_blank_number_to = '';
                        obj.bso_blank_dop_serie_id = 0;
                        obj.bso_blank_dop_serie_value = '';


                        obj.tp_id = $('#point_sale_id').val();
                        obj.bso_supplier_id = $('#bso_supplier_id').val();
                        obj.act_number = $('#act_number').val();
                        obj.time_create = $('#time_create').val();
                        //obj.agent_id = $('#agent_id').val();
                        obj.bso_act_id = bso_act_id;


                        r = myPostAjax('{{url('/bso/add_bso_warehouse/add_bso/')}}', 'obj='+ JSON.stringify(obj));
                        res = r;//JSON.parse(r);

                        if (parseInt(res.error_state) == 1)
                        {

                            $(this).find('td:nth-child(' + parseInt(res.error_attr) + ')').children('.error_div').html(res.error_title);
                            $(this).children().css('background-color', '#FFEEEE');
                            errors_qty++;
                            $('#save_button').show();
                            $('#loading_div').fadeOut(300);


                        }
                        else
                        {
                            $(this).attr('completed', 1);
                            $(this).find('select').prop('disabled', true);
                            $(this).find('input').prop('disabled', true);
                            if (res.error_state != 2) $(this).children().css('background-color', '#EEFFEE');
                        }


                    }
                });

                if (errors_qty == 0)
                {

                    $('#loading_div').fadeOut(300);
                    var acts = '1. <a href="/bso_acts/show_bso_act/' + bso_act_id + '/" target="_blank">Акт приема БСО в организацию</a><br/>';
                    $('#acts_div').html(acts);

                    $('#acts_div').show();

                    if(parseInt($('#agent_id').val()) > 0){
                        transferBSOToAgent($('#agent_id').val(), bso_act_id);
                    }

                }


            }, 400);

        }


        function transferBSOToAgent(agent_id, bso_act_id)
        {
            var agent_act_id = myGetAjax('{{url('/bso/transfer/transfer_bso_act_agent/')}}/?agent_id='+agent_id+'&bso_act_id='+bso_act_id);
            var acts = '2. <a href="/bso_acts/show_bso_act/' + agent_act_id + '/" target="_blank">Акт передачи БСО агенту</a>';
            $('#acts_div').append(acts);
        }


    </script>


@stop

