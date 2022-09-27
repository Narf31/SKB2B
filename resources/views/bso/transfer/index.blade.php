@extends('layouts.app')

@section('content')

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="page-subheading">
            <h2>Прием передача БСО</h2>

        </div>


        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="block-main">
                <div class="block-sub">
                    <div class="form-horizontal">


                        <div class="form-group">
                            <label class="col-sm-4 control-label">Тип</label>
                            <div class="col-sm-8">
                                {{ Form::select('bso_cart_type', $bso_cart_type->prepend('Выберите значение', 0), $bso_cart->bso_cart_type, ['class' => 'form-control select2-ws', 'id'=>'bso_cart_type']) }}
                            </div>
                        </div>

                        <div class="form-group" id="tr_tp" style="display: none;">
                            <label class="col-sm-4 control-label">Точка продаж</label>
                            <div class="col-sm-8">
                                {{ Form::select('tp', \App\Models\Settings\PointsSale::where('is_actual', 1)->get()->pluck('title', 'id'), $bso_cart->tp_id, ['class' => 'form-control select2-ws tp', 'id'=>'tp']) }}
                            </div>
                        </div>

                        <div class="form-group" id="tr_tp_new" style="display: none;">
                            <label class="col-sm-4 control-label">Новоя точка продаж</label>
                            <div class="col-sm-8">
                                {{ Form::select('tp_new', \App\Models\Settings\PointsSale::where('is_actual', 1)->get()->pluck('title', 'id'), $bso_cart->tp_new_id, ['class' => 'form-control select2-ws tp_new', 'id'=>'tp_new']) }}
                            </div>
                        </div>

                        <div class="form-group" id="tr_user_id_to" style="display: none;">
                            <label class="col-sm-4 control-label">Агент-получатель</label>
                            <div class="col-sm-8">
                                {{ Form::select('user_id_to', $agents->prepend('Выберите значение', 0), $bso_cart->user_id_to, ['class' => 'form-control user_id_to select2', 'id'=>'user_id_to']) }}


                                <span class="agent_to_span"></span>
                                <div class="agent_to_ban_text"></div>
                            </div>
                        </div>

                        <div class="form-group" id="tr_tp_bso" style="display: none;">
                            <label class="col-sm-4 control-label">Сотрудник</label>
                            <div class="col-sm-8">
                                {{ Form::select('tp_bso_manager', $bso_manager, $bso_cart->tp_bso_manager_id, ['class' => 'form-control select2-all tp_bso_manager', 'id'=>'tp_bso_manager']) }}
                            </div>
                        </div>

                        <div class="form-group" id="tr_button" style="display: none;">
                            <div class="col-sm-12">
                                <span class="btn btn-primary pull-left" onclick="createCart();">Далее</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
            <div class="agent_info">
            </div>
        </div>




    </div>
@stop

@section('js')

<script>


    $(function () {

        $("#bso_cart_type").on('change', function () {
            show_hide_controls($(this).val(), 0);
        });



        $('#user_id_to').on('change', function () {
            getUserInfo($(this).val());
        });



        $("#bso_cart_type").change();
        $("#user_id_to").change();

    });




    function show_hide_controls(bso_cart_type, disable) {
        switch (bso_cart_type) {
            case '1':
                // Передача со склада агенту
                $("#tr_tp").show();
                $("#tr_tp_new").hide();
                $("#tr_user_id_to").show();
                $("#tr_tp_bso").hide();
                $("#tr_button").hide();
                break;
            case '2':
                // Передача БСО на точку продаж
                $("#tr_tp").show();
                $("#tr_tp_new").show();
                $("#tr_user_id_to").hide();
                $("#tr_tp_bso").show();
                $("#tr_button").show();
                $('.agent_to_span').html('');
                $('.agent_to_ban_text').html('');
                $('.agent_info').html('');
                $('#user_id_to').select2('val', 0);
                break;
            case '3':
            default:
                // Не выбрано
                $("#tr_tp").hide();
                $("#tr_tp_new").hide();
                $("#tr_user_id_to").hide();
                $("#tr_tp_bso").hide();
                $("#tr_button").hide();
                $('.agent_to_span').html('');
                $('.agent_to_ban_text').html('');
                $('.agent_info').html('');
                $('#user_id_to').select2('val', 0);

        }

        if (disable == 1 && bso_cart_type != '0') {
            $("#tr_button").hide();
            $('#bso_cart_type').prop('disabled', true);
        }

    }




    function getUserInfo(agent_id)
    {

        obj = myGetAjax('/bso/transfer/get_user_ban_reason?user_id=' + agent_id);
        //var obj = JSON.parse(res);
        if (obj.ban_level == 0) {
            $('#tr_button').show();
        }
        else {
            $('#tr_button').hide();
        }
        $('.agent_to_span').html('<br/><a target="_blank" href="' + obj.details_url + '">Подробнее</a>');
        $('.agent_to_ban_text').html(obj.ban_reason);


        $('.unban_user').on('change', function () {
            if ($(this).prop("checked")) {
                $('#tr_button').show();
            }
            else {
                $('#tr_button').hide();
            }
        });


        res = myGetAjax('/bso/transfer/get_agent_info/?user_id=' + agent_id);
        $('.agent_info').html(res);


    }



    function createCart()
    {
        var user_id_to = $("#user_id_to").val();
        var bso_cart_type = $("#bso_cart_type").val();
        var tp_id = $("#tp").val();
        var tp_new_id = $("#tp_new").val();
        var tp_bso_manager_id = $("#tp_bso_manager").val();
        switch (bso_cart_type) {
            case '1':
                // Передача со склада агенту
                if (user_id_to == 0) {
                    alert('Укажите агента-получателя');
                    return false;
                }
                break;
        }
        var bso_cart_id = myGetAjax('/bso/transfer/create_bso_cart/?user_id_to=' + user_id_to + '&bso_cart_type=' + bso_cart_type + '&tp_id=' + tp_id+ '&tp_new_id=' + tp_new_id + '&tp_bso_manager_id=' + tp_bso_manager_id);

        if(parseInt(bso_cart_id)>0) {
            openPage('/bso/transfer/?bso_cart_id=' + bso_cart_id);
        }
        return;
    }


</script>


<style>

    .error_div, .error_span {
        color: red;
    }


    .agent_to_ban_text {
        margin-top: 10px;
        color: red;
    }
</style>

@stop