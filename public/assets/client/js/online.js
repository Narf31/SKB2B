
function getStepValid(step) {

    //alert(step);


    msg_arr = [];

    $('#step-'+step).find('.valid_accept').each(function() {
        val = ($(this).val())?$(this).val():$(this).html();
        if(val.length<1){
            form_id = $(this).data('form');
            if(form_id){
                $("#"+form_id).css('border-color', 'red');
            }else{
                $(this).css('border-color', 'red');
            }
            msg_arr.push('Заполните все поля');

        }
    });

    if (msg_arr.length) {
        return false;
    }

    calc = 0;
    res = true;
    if(step == CALC_STEP && IS_CALC == 0){
        calc = 1;
        res = false;
    }



    if(IS_CALC == 0){
        saveContractAndCalc(calc);
    }


    return res;
}

function getCalcStepValid(step) {
    IS_CALC = 0;

    return true;
}




function saveContractAndCalc(calc) {

    loaderShow();

    $.post(CONTRACT_URL + '/save/' + CONTRACT_TOKEN, $('#product_form').serialize(), function (response) {



        if (Boolean(response.state) === true) {

            if(parseInt(calc) == 1){
                return calcContract();
            }

        }

    }).always(function () {
        loaderHide();
    });

    return true;

}


function calcContract() {
    //Расчет
    loaderShow();

    $.get(CONTRACT_URL + '/calc/' + CONTRACT_TOKEN, {}, function (response) {


        if (Boolean(response.state) === true) {
            IS_CALC = 1;
            //window.location = CONTRACT_URL + '/release/' + CONTRACT_TOKEN;
            $("#payment_total").html(response.payment_total);
            $("#calc_butt").click();

        }

    }).always(function () {
        loaderHide();
    });


    return true;

}

function releaseContract()
{
    //Выпуск договора
    loaderShow();

    $.post(CONTRACT_URL + '/release/' + CONTRACT_TOKEN, $('#product_form').serialize(), function (response) {


        if (Boolean(response.state) === true) {

            if(response.payment_type == 4){

                window.location = CONTRACT_URL + '/payment-link/' + CONTRACT_TOKEN;

            }else{

                reload();

            }



        }else{
            if(response.payment_type == 5){
                setPromoError(response.msg);
            }
        }



    }).always(function () {
        loaderHide();
    });


    return true;
}



function setEndDates(key) {
    begin_date = $("#begin_date_" + key).val();
    $("#end_date_" + key).val(get_end_dates(begin_date));
}

function get_end_dates(start_date) {
    var cur_date_tmp = start_date.split(".");
    var cur_date = new Date(cur_date_tmp[2], cur_date_tmp[1] - 1, cur_date_tmp[0]);
    var new_date = new Date(cur_date.setYear(cur_date.getFullYear() + 1));
    var new_date2 = new Date(new_date.setDate(new_date.getDate() - 1));
    return getFormattedDate(new_date2);
}

function getFormattedDate(date) {
    var year = date.getFullYear();
    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;
    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;
    return day + '.' + month + '.' + year;
}