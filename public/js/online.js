
function selectCategory(category_id, product_id) {
    $('#productCategoryMenuList a').removeClass('current');
    $('#productCategoryMenuList .category-' + category_id + ' a').addClass('current');


    $('#productMenuList ul').hide();
    $('#productMenuList .product-category-' + category_id).show();
    if (product_id === undefined) {
        product_id = $('#productMenuList a:visible').eq(0).attr('data-product');
        selectProduct(product_id);
    } else {
        selectProduct(product_id);
    }
}

function selectProduct(product_id) {
    $('#productDescriptionMenuList .product-description').addClass('hidden');
    $('#productDescriptionMenuList .product-description-' + product_id).removeClass('hidden');
    $('#productMenuList a').removeClass('current');
    $('#productMenuList a[data-product="' + product_id + '"]').addClass('current');

    product = $("#product_"+product_id);

    if(parseInt(product.data('progarm')) > 0){
        $('#view_programs').show();

        $('#programsMenuList ul').hide();
        $('#programsMenuList .product-program-' + product_id).show();
        program_id = $('#programsMenuList a:visible').eq(0).attr('data-program');

        selectProgram(program_id);

    }else{
        $('#view_programs').hide();
    }


}

function selectProgram(program_id) {

    $('#productDescriptionMenuList .product-description').addClass('hidden');
    $('#productDescriptionMenuList .product-description-program-' + program_id).removeClass('hidden');
    $('#programsMenuList a').removeClass('current');
    $('#programsMenuList a[data-program="' + program_id + '"]').addClass('current');


}

$('document').ready(function () {
    $('[data-toggle="tooltip"]').tooltip({container: 'body'});

});


function formatTime() {
    var configuration = {
        timepicker: true,
        datepicker: false,
        format: 'H:i',
        scrollInput: false
    };
    $.datetimepicker.setLocale('ru');
    $('input.format-time').datetimepicker(configuration).keyup(function (event) {
        if (event.keyCode != 37 && event.keyCode != 39 && event.keyCode != 38 && event.keyCode != 40) {
            var pattern = new RegExp("[0-9:]{5}");
            if (pattern.test($(this).val())) {
                $(this).datetimepicker('hide');
                $(this).datetimepicker('show');
            }
        }
    });
    $('input.format-time').each(function () {
        var im = new Inputmask("99:99", {"oncomplete": function () {}});
        im.mask($(this));
    });
}


function get_end_dates(start_date) {
    var cur_date_tmp = start_date.split(".");
    var cur_date = new Date(cur_date_tmp[2], cur_date_tmp[1] - 1, cur_date_tmp[0]);
    var new_date = new Date(cur_date.setYear(cur_date.getFullYear() + 1));
    var new_date2 = new Date(new_date.setDate(new_date.getDate() - 1));
    return getFormattedDate(new_date2);
}

function setAllDates(key) {
    sign_date = $("#sign_date_" + key).val();
    $("#begin_date_" + key).val(sign_date);

    return setEndDates(key);
}

function setEndDates(key) {
    begin_date = $("#begin_date_" + key).val();
    end_date = get_end_dates(begin_date);
    $("#end_date_" + key).val(end_date);

    if($('*').is('#period_beg1')){
        $("#period_beg1").val(begin_date);
        $("#period_end1").val(end_date);
    }


}

function calculate(id) {
    loaderShow();

    $.post('/contracts/online/save/' + id, $('#product_form').serialize(), function (response) {

        //$("#result_calc").html(response);

        //reload();


    }).always(function () {
        loaderHide();
    });
}



function handleErrors(errors){
         $("#messages").html('');
        $.each(errors, function (index, value) {
            $('<div class="alert alert-danger"><button class="close" data-close="alert"></button>' + value + '</div>').appendTo("#messages");
            $(index).addClass('form-error');
            console.log(index);
        });

        $("#messages").fadeTo(2000, 500).slideUp(500, function () {
            $("#messages").slideUp(500);
            $("#messages").html();
        });
}


$(document).ready(function () {

    $("input, select").click(function () {
        $(this).removeClass('form-error');
    });

    $('.valid_accept').change(function() {
        $('#offers').html('');
    });

    $('.clear_offers').change(function() {
        $('#offers').html('');
    });
});



function saveContractAndCalc(id, calc_state) {


    if(parseInt(calc_state) == 1 && !validate()){
        flashHeaderMessage("Заполните все поля!", 'danger');
        return false;
    }

    loaderShow();


    $.post('/contracts/online/' + id + '/save', $('#product_form').serialize(), function (response) {



        if (Boolean(response.state) === true) {

            if(calc_state == 1) {

                setTimeout(function tick() {
                    calcContract(id);
                }, 500);
                return true;
            }else if(calc_state == 2){
                //return matchingContract(id);
            }else{
                flashMessage('success', "Данные успешно сохранены!");
            }


        }else {
            if(response.errors){
                $.each(response.errors, function (index, value) {
                    flashHeaderMessage(value, 'danger');
                    $('[name="' + index + '"]').addClass('form-error');
                });
            }else{
                flashHeaderMessage(response.msg, 'danger');
            }

        }

    }).always(function () {
        if(calc_state == 1){

        }else{

            loaderHide();
        }

    });

    return true;

}



function calcContract(id) {


    if(!validate()){
        flashHeaderMessage("Заполните все поля!", 'danger');
        return false;
    }

    loaderShow();


    $.post('/contracts/online/' + id + '/calc', $('#product_form').serialize(), function (response) {



        if (Boolean(response.state) === true) {

            $("#offers").html(response.html);
            windowsScrollDon();

            if(parseInt(response.payment_total) >= 15000){
                $(".is_limit_payment_total").show();
                is_limit_payment_total = 1;
            }else{
                $(".is_limit_payment_total").hide();
                is_limit_payment_total = 0;
            }

        }else {
            if(response.errors){
                $.each(response.errors, function (index, value) {
                    flashHeaderMessage(value, 'danger');
                    $('[name="' + index + '"]').addClass('form-error');
                });
            }else{
                flashHeaderMessage(response.msg, 'danger');
            }

        }

    }).always(function () {
        loaderHide();
    });

    return true;

}

/*
function calcContract(id) {
    //Расчет

    loaderShow();


    $("#offers").html('');


    $.get('/contracts/online/' + id + '/calc', {}, function (response) {


        if (Boolean(response.state) === true) {

            $("#offers").html(response.html);
            windowsScrollDon();

            if(parseInt(response.payment_total) >= 15000){
                $(".is_limit_payment_total").show();
                is_limit_payment_total = 1;
            }else{
                $(".is_limit_payment_total").hide();
                is_limit_payment_total = 0;
            }

        }else {
            if(response.errors){
                $.each(response.errors, function (index, value) {
                    flashHeaderMessage(value, 'danger');
                    $('[name="' + index + '"]').addClass('form-error');
                });
            }else{
                flashHeaderMessage(response.msg, 'danger');
            }

        }

    }).always(function () {
        loaderHide();
    });

    return true;

}
*/

var is_limit_payment_total = 0;

function releaseContract(id)
{

    msg_arr = [];

    $('.valid_accept').each(function() {
        val = ($(this).val())?$(this).val():$(this).html();
        if(val.length<1){
            msg_arr.push('Заполните все поля' + $(this).attr('name'));
            $(this).addClass('form-error');
        }

    });

    if(is_limit_payment_total == 1){
        $('.limit_payment_total_valid_accept').each(function() {
            val = ($(this).val())?$(this).val():$(this).html();
            if(val.length<1){
                msg_arr.push('Заполните все поля' + $(this).attr('name'));
                $(this).addClass('form-error');
            }

        });
    }


    if (msg_arr.length) {
        flashHeaderMessage("Заполните все поля", 'danger');
        clearCalc();
        return false;
    }



    return openFancyBoxFrame("/contracts/online/"+ id +"/release");
}



function matchingContract(id)
{

    msg_arr = [];

    $('.valid_accept').each(function() {
        val = ($(this).val())?$(this).val():$(this).html();
        if(val.length<1){
            msg_arr.push('Заполните все поля' + $(this).attr('name'));
            $(this).addClass('form-error');
        }

    });

    if (msg_arr.length) {
        flashHeaderMessage("Заполните все поля", 'danger');
        clearCalc();
        return false;
    }

    loaderShow();

    return openPage("/contracts/online/"+ id +"/matching/send/");
}


function clearCalc() {
    $("#offers").html('');
}


function windowsScrollUp() {
    $('html,body').animate ({scrollTop: 1},100);
}

function windowsScrollDon() {
    $('html,body').animate ({scrollTop: $('html,body').height()},100);
}


function copyDataValIsNull(input_id, val_id) {
    //if($('#'+input_id).val().length <= 0){
        $('#'+input_id).val($('#'+val_id).val());
    //}

}

function refreshMask(id) {

    loaderShow();


    $.post('/contracts/online/' + id + '/refresh-mask', {}, function (response) {

        return reload();

    }).always(function () {
        loaderHide();
    });

    return true;

}