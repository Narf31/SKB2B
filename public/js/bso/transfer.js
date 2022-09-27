function tpChange(){

}

function show_hide_controls(bso_cart_type, disable) {
    switch (bso_cart_type) {
        case '1':
            // Передача со склада агенту
            $("#tr_bso_state_id").hide();
            $("#tr_user_id_from").hide();
            $("#tr_user_id_to").show();
            $("#tr_sk").hide();
            $("#tr_tp").show();
            $(".tr-region").hide();
            $("#tr_tp_bso").hide();
            $("#tr_courier").hide();
            $("#tr_button").show();
            $("#tr_tp_new").hide();
            $("#tr_tp_change").hide();
            break;
        case '2':
            // Передача от агента-агенту
            $("#tr_bso_state_id").hide();
            $("#tr_user_id_from").show();
            $("#tr_user_id_to").show();
            $("#tr_sk").hide();
            $("#tr_tp, .tr-region").hide();
            $("#tr_tp_bso").hide();
            $("#tr_courier").hide();
            $("#tr_button").show();
            $("#tr_tp_new").hide();
            $("#tr_tp_change").show();
            break;
        case '3':
            // Прием БСО от агента
            $("#tr_bso_state_id").hide();
            $("#tr_user_id_from").show();
            $("#tr_user_id_to").hide();
            $("#tr_sk").hide();
            $("#tr_tp, .tr-region").hide();
            $("#tr_tp_bso").hide();
            $("#tr_courier").hide();
            $("#tr_button").show();
            $("#tr_tp_new").hide();
            $("#tr_tp_change").hide();
            break;
        case '4':
            // Передача БСО в СК
            $("#tr_bso_state_id").show();
            $("#tr_user_id_from").hide();
            $("#tr_user_id_to").hide();
            $("#tr_sk").show();
            $("#tr_tp, .tr-region").hide();
            $("#tr_tp_bso").hide();
            $("#tr_courier").hide();
            $("#tr_button").show();
            $("#tr_tp_new").hide();
            $("#tr_tp_change").hide();
            break;
        case '5':
            // Передача БСО на точку продаж
            $("#tr_bso_state_id").hide();
            $("#tr_user_id_from").hide();
            $("#tr_user_id_to").hide();
            $("#tr_sk").hide();
            $("#tr_tp").show();
            $(".tr-region").hide();
            $("#tr_tp_bso").show();
            $("#tr_courier").hide();
            $("#tr_button").show();
            $("#tr_tp_new").hide();
            $("#tr_tp_change").hide();
            break;
        case '6':
            // Передача БСО курьеру
            $("#tr_bso_state_id").hide();
            $("#tr_user_id_from").hide();
            $("#tr_user_id_to").show();
            $("#tr_sk").hide();
            $("#tr_tp, .tr-region").hide();
            $("#tr_tp_bso").hide();
            $("#tr_courier").show();
            $("#tr_button").show();
            $("#tr_tp_new").hide();
            $("#tr_tp_change").hide();
            break;
        case '7':
            // Прием БСО от агента
            $("#tr_bso_state_id").hide();
            $("#tr_user_id_from").show();
            $("#tr_user_id_to").hide();
            $("#tr_sk").hide();
            $("#tr_tp, .tr-region").hide();
            $("#tr_tp_bso").hide();
            $("#tr_courier").hide();
            $("#tr_button").show();
            $("#tr_tp_new").hide();
            $("#tr_tp_change").hide();
            break;
        case '8':
            // Передача БСО на Регион
            $("#tr_bso_state_id").hide();
            $("#tr_user_id_from").hide();
            $("#tr_user_id_to").show();
            $("#tr_sk").hide();
            $("#tr_tp").show();
            $("#tr_tp_bso").hide();
            $("#tr_courier").hide();
            $("#tr_button").show();
            $("#tr_tp_new").show();
            $("#tr_tp_change").hide();
            break;
        case '9':
            // Передача БСО от ТП на ТП
            $("#tr_bso_state_id").hide();
            $("#tr_user_id_from").hide();
            $("#tr_user_id_to").hide();
            $("#tr_sk").hide();
            $("#tr_tp").show();
            $("#tr_tp_bso").hide();
            $("#tr_courier").hide();
            $("#tr_button").show();
            $("#tr_tp_new").show();
            $("#tr_tp_change").hide();
            break;
        default:
            // Не выбрано
            $("#tr_bso_state_id").hide();
            $("#tr_user_id_from").hide();
            $("#tr_user_id_to").hide();
            $("#tr_sk").hide();
            $("#tr_tp, .tr-region").hide();
            $("#tr_tp_bso").hide();
            $("#tr_courier").hide();
            $("#tr_button").hide();
            $("#tr_tp_new").hide();
            $("#tr_tp_change").hide();
    }

    if (disable == 1 && bso_cart_type != '0') {
        $("#tr_button").hide();
        $('#bso_cart_type').prop('disabled', true);
    }

}

$(function () {


    $("#bso_cart_type").on('change', function () {
        show_hide_controls($(this).val(), 0);
    });

    openViewCar();

    $('#user_id_to').on('change', function () {
        getUserInfo($(this).val());
    });

    $(document).on(
        "click",
        ".unban_user",
        function () {
            if ($(this).prop("checked")) {
                $('#cart_create').show();
            }
            else {
                $('#cart_create').hide();
            }
        }
    );


    $("#cart_create").on('click', function () {
        createCart();
    });

    $('#sk_user_id').on('change', function () {
        $("#bso_type_id").html(myGetAjax('/bso/transfer/get_bso_types/?sk_user_id=' + $(this).val()));
    });

    $(document).on(
        "click",
        ".cb_left_column_style",
        function () {

            if ($(this).prop("checked")) {
                $('.tr_type_selector').hide();
                $('.button_sk_selector').show();
                $('#bsos').hide();
                $('#rit_bsos').show();
                $('.save_transmit_bso').show();
                document.cookie = "bso_transfer_left_column_style=1";
            }
            else {
                $('.tr_type_selector').show();
                $('.button_sk_selector').hide();
                $('#bsos').show();
                $('#rit_bsos').hide();
                $('.save_transmit_bso').hide();
                document.cookie = "bso_transfer_left_column_style=0";
            }

        }
    );


    $(document).on(
        "click",
        ".button_sk_selector",
        function () {


            if ($('.bso_table[sk_user_id=' + $('#sk_user_id').val() + ']').length == 0) {
                res = myGetAjax('/bso/transfer/rit_bso_selector/?sk_user_id=' + $('#sk_user_id').val());
                $('#rit_bsos').append(res);
            }
        }
    );

    $(document).on(
        "click",
        ".remove_string_button",
        function () {

            $(this).parent().parent().remove();
        }
    );

    $(document).on(
        "click",
        ".remove_sk_button",
        function () {

            $(this).parent().remove();
        }
    );

    $(document).on(
        "click",
        ".add_string_button",
        function () {

            var sk_user_id = $(this).attr('sk_user_id');
            // Создаем элемент
            var el = $('<tr>', {
                class: 'table_row',
                completed: '0'
            });
            el.html($('.new_tr[sk_user_id=' + sk_user_id + ']').val());


            // Помещаем в таблицу
            $('.bso_table[sk_user_id=' + sk_user_id + ']').append(el);


        });



    $(document).on(
        "change",
        ".type_selector",
        function () {

            selectBsoType(this);

        }
    );


    $(document).on(
        "blur",
        ".bso_number",
        function () {
            selectBsoNumber(this);
        }
    );

    $(document).on(
        "blur",
        ".bso_qty",
        function () {

            selectBsoQty(this);
        }
    );


    $("#bso_type_id").on('change', function () {
        getBsosList($(this).val());
    });

    $("#bso_state_id").on('change', function () {
        getBsosList($("#bso_type_id").val());
    });

    $(document).on(
        "click",
        "#check_all",
        function () {
            if ($(this).prop("checked")) {
                $(".cb_bso").prop("checked", true);
            }
            else {
                $(".cb_bso").prop("checked", false);
            }
        }
    );


    $(document).on(
        "click",
        "#move_to_cart",
        function () {

            var bsos = '';
            $('.cb_bso:checked').each(function () {
                bsos += $(this).attr('bso_id') + ',';
            });

            //Резервируем бсо
            var res = myGetAjax('/bso/transfer/move_to_cart/?bso_cart_id=' + $("#bso_cart_id").val() + '&bsos=' + bsos);

            //Обновляем левую корзину
            getBsosList($("#bso_type_id").val());

            //Обновляем правую корзину
            openViewCar();
        }
    );


    $(document).on(
        "click",
        ".cb_right_column_style",
        function () {

            if ($(this).prop("checked")) {
                $('#group_by_items').hide();
                $('#group_by_types').show();
                document.cookie = "bso_transfer_right_column_style=1";
            }
            else {
                $('#group_by_items').show();
                $('#group_by_types').hide();
                document.cookie = "bso_transfer_right_column_style=0";
            }

        }
    );


    $(document).on(
        "click",
        ".remove_button",
        function () {
            var bso_id = $(this).attr('bso_id');
            removeBsosCar(bso_id, 0);
        }
    );

    $(document).on(
        "click",
        ".remove_type_button",
        function () {
            var bso_type_id = $(this).attr('type_id');
            removeBsosCar(0, bso_type_id);
        }
    );


    $(document).on(
        "click",
        ".save_transmit_bso",
        function () {
            var errors_qty = 0;
            $.each($('.table_row[completed=0]'), function () {

                $(this).find('.error_div').html('');
                $(this).find('td:nth-child(2)').children('.error_div').html('');

                obj = new Object();
                obj.type_id = $(this).find('.type_selector').val();
                obj.serie_id = $(this).find('.series_selector').val();
                obj.bso_qty = $(this).find('.bso_qty').val();
                obj.number = $(this).find('.bso_number').val();
                obj.tp_id = $("#tp").val();
                obj.bso_cart_id = $("#bso_cart_id").val();
                res = myGetAjax('/bso/transfer/rit_bso_transfer/?obj=' + JSON.stringify(obj));

                //res = JSON.parse(r);
                if (res.error_state == 1) {
                    $(this).find('td:nth-child(' + res.error_attr + ')').children('.error_div').html(res.error_title);
                    $(this).children().css('background-color', '#FFEEEE');
                    errors_qty++;
                }
                else {
                    $(this).attr('completed', 1);
                    $(this).find('select').prop('disabled', true);
                    $(this).find('input').prop('disabled', true);
                    if (res.error_state != 2) $(this).children().css('background-color', '#EEFFEE');
                }

            });

            if (errors_qty > 0) {
                alert('Перемещены не все БСО!');
            }

            openViewCar();

        }
    );



    $("#b_transfer_bso").on('click', function () {

        if (!confirm('Подвердите передачу БСО')) return false;

        var bso_cart_id = $("#bso_cart_id").val();
        var res = myGetAjax('/bso/transfer/transfer_bso/?bso_cart_id=' + bso_cart_id);

        if (res == 'empty cart') {
            alert('В корзине отсутствуют БСО.');
            return false;
        }

        reload();

    });


    $("#show_all_bsos").on('click', function () {
        var user_id_from = $('#user_id_from').val();
        var bso_state_id = $('#bso_state_id').val();
        $('#bsos').html(myGetAjax('/bso/transfer/get_all_bsos/?user_id_from=' + user_id_from + '&bso_state_id=' + bso_state_id));

    });


});

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

function selectBsoType(bso_type)
{
    bso_type_id = $(bso_type).val();
    bso_supplier_id = $('#sk_user_id').val();

    $.getJSON('/bso/actions/get_series/', {bso_type_id: bso_type_id, bso_supplier_id:bso_supplier_id}, function (response) {

        var options = "<option value='0'>Не выбрано</option>";
        response.map(function (item) {
            options += "<option value='" + item.id + "'>" + item.bso_serie + "</option>";
        });

        $(bso_type).parent().siblings().children('select.series_selector').html(options);
        $(bso_type).parent().siblings().children('select.series_selector2').html(options);


    });

}

function getUserInfo(agent_id)
{
    bso_cart_id = parseInt($("#bso_cart_id").val());

    obj = myGetAjax('/bso/transfer/get_user_ban_reason?user_id=' + agent_id);
    //var obj = JSON.parse(res);
    if (obj.ban_level == 0) {
        $('#cart_create').show();
    }
    else {
        $('#cart_create').hide();
    }
    $('.agent_to_span').html('<br/><a target="_blank" href="/users/users/' + agent_id + '/edit">Подробнее</a>');
    $('.agent_to_ban_text').html(obj.ban_reason);


    res = myGetAjax('/bso/transfer/get_agent_info/?user_id=' + agent_id);
    $('.agent_info').html(res);

    if($('#limit_ban').val()>0){
        $('#cart_create').hide();
    }

}

function createCart()
{
    var user_id_from = $("#user_id_from").val();
    var user_id_to = $("#user_id_to").val();
    var tp_change_selected = $("#tp_change_selected").is(':checked') ? 1 : 0;
    var sk_id_to = $("#sk_id_to").val();
    var bso_cart_type = $("#bso_cart_type").val();
    var bso_state_id = $("#bso_state_id").val();
    var tp_id = $("#tp").val();
    var tp_new_id = $(".tp_new").val();
    var tp_bso_manager_id = $(".tp_bso_manager").val();
    var courier_id = $(".couriers").val();
    switch (bso_cart_type) {
        case '1':
            // Передача со склада агенту
            if (user_id_to == 0) {
                alert('Укажите агента-получателя');
                return false;
            }
            break;
        case '6':
            // Передача БСО курьеру
            if (user_id_to == 0) {
                alert('Укажите агента-получателя');
                return false;
            }
            break;
        case '2':
            // Передача от агента-агенту
            if (user_id_from == 0) {
                alert('Укажите агента-отправителя');
                return false;
            }
            if (user_id_to == 0) {
                alert('Укажите агента-получателя');
                return false;
            }
            break;
        case '3':
            // Прием БСО от агента
            if (user_id_from == 0) {
                alert('Укажите агента-отправителя');
                return false;
            }
            break;
    }
    var bso_cart_id = myGetAjax('/bso/transfer/create_bso_cart/?user_id_from=' + user_id_from + '&user_id_to=' + user_id_to + '&sk_id_to=' + sk_id_to + '&bso_cart_type=' + bso_cart_type + '&bso_state_id=' + bso_state_id + '&tp_id=' + tp_id+ '&tp_new_id=' + tp_new_id + '&tp_bso_manager_id=' + tp_bso_manager_id + '&courier_id=' + courier_id + '&tp_change_selected=' + tp_change_selected);
    if(parseInt(bso_cart_id)>0) window.location = '/bso/transfer/?bso_cart_id=' + bso_cart_id;
    return;
}


function openViewCar()
{
    bso_cart_id = parseInt($("#bso_cart_id").val());
    if(bso_cart_id > 0){
        $('#bso_cart').html(myGetAjax('/bso/transfer/bso_cart_content/?bso_cart_id=' + bso_cart_id));
        show_hide_controls($("#bso_cart_type").val(), 1);
    }
}

function getBsosList(bso_type_id)
{
    var sk_user_id = $('#sk_user_id').val();
    var user_id_from = $('#user_id_from').val();
    var bso_cart_type = $('#bso_cart_type').val();
    var bso_state_id = $('#bso_state_id').val();
    var tp_id = $("#tp").val();

    $('#bsos').html(myGetAjax('/bso/transfer/get_bsos/?sk_user_id=' + sk_user_id + '&bso_type_id=' + bso_type_id + '&user_id_from=' + user_id_from + '&bso_cart_type=' + bso_cart_type + '&bso_state_id=' + bso_state_id + '&tp_id=' + tp_id));

}

function removeBsosCar(bso_id, bso_type_id)
{
    bso_cart_id = parseInt($("#bso_cart_id").val());

    myGetAjax('/bso/transfer/remove_from_bso_cart/?bso_id=' + bso_id + '&bso_type_id=' + bso_type_id + '&bso_cart_id=' + $("#bso_cart_id").val());

    openViewCar();
}