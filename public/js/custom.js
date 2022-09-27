//var DADATA_TOKEN = "";
//var DADATA_AUTOCOMPLETE_URL = '/suggestions/dadata/';
var DADATA_TOKEN = '2638297789589d3f747b48baaad30f3d928f6b0f';
var DADATA_AUTOCOMPLETE_URL = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs';

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    startMainFunctions();

});


function startMainFunctions() {
    $(document)
        .on('focus', '.datepicker', function () {
            $(this).datepicker({
                allowInputToggle: true,
                dateFormat: 'dd.mm.yy',
                changeMonth: true,
                changeYear: true,
                yearRange: '1900:2050'
            });
        }).on('focus', '.date', function () {
        $(this).mask('99.99.9999');
    })
        .on('focus', ".disable_keypress", function () {
            $(this).keypress(function (e) {

                e.preventDefault();
            });
        })
        .on('focus', ".datetimepicker", function () {
            $(this).bootstrapMaterialDatePicker({
                allowInputToggle: true,
                format: 'DD.MM.YYYY HH:mm',
                lang: 'ru',
                weekStart: 1,
                cancelText: 'Отмена',
            });

        });

    $(document).on('focus', '.datepicker_start', function () {
        $(this).datepicker({
            allowInputToggle: true,
            dateFormat: 'dd.mm.yy',

            onSelect: function (date) {
                set_end_dates(date);
            },
            changeMonth: true,
            changeYear: true,
            yearRange: '2015:2030'

        });

    });



    $('.float').keypress(function (event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    if($('*').is('.phone')){
        $('.phone').mask('+7 (999) 999-99-99');
    }

    if($('*').is('.price')){
        $('.price').mask('000 000 000.00', {reverse: true});
    }

    if($('*').is('.time')){
        $('.time').mask('99:99');
    }




    $('body').on('click', '.fancybox\\.iframe', function () {
        $.fancybox.open({
            type: 'iframe',
            href: $(this).attr('href'),
        });
    });

    if($('*').is('.fancybox')) {
        $('.fancybox').fancybox();
    }

    if($('*').is('.fancybox-custom')) {
        $('.fancybox-custom').fancybox({
            type: 'iframe',
            href: $(this).attr('href'),
            height: $(this).data('height'),
            width: $(this).data('width'),
            autoDimensions: false,
            autoSize: false
        });
    }

    if($('*').is('.fancybox-parent')) {
        $('.fancybox-parent').click(function () {
            var href = $(this).attr('href');
            parent.$.fancybox({
                type: 'iframe',
                href: href,
                height: '360px',
                fitToView: false,
                autoSize: false
            });
            return false;
        });
    }

    $('.numbers').keypress(function (event) {
        if (event.which == 8 || event.keyCode == 46)
            return true;
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });



    if($('*').is('.party-autocomplete')) {
        $(".party-autocomplete").suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "PARTY",
            count: 5,
            onSelect: function (suggestion) {
                var data = suggestion.data;
                var subjectType = $(this).data('party-type');


                $('[data-name='+subjectType+'_title]').val(suggestion.value);
                $('[data-name='+subjectType+'_title_doc]').val(suggestion.unrestricted_value);
                $('[data-name='+subjectType+'_inn]').val(data.inn);
                $('[data-name='+subjectType+'_kpp]').val(data.kpp);
                $('[data-name='+subjectType+'_ogrn]').val(data.ogrn);

                if(data.management && data.management.name){
                    $('[data-name='+subjectType+'_general_manager]').val(data.management.name);
                }

                $('[data-name='+subjectType+'_address]').val(data.address.value);

            }
        });
    }

    initSelect2();
    initTextControll();

    $(document)
        .on('focus', '.fio-autocomplete', function () {
            $(this).suggestions({
                serviceUrl: DADATA_AUTOCOMPLETE_URL,
                token: DADATA_TOKEN,
                type: "NAME",
                count: 5,
                onSelect: function (suggestion) {
                }
            });
        })

        .on('keyup', '.documentSeries', function () {
            $(this).val($(this).val().toUpperCase());
        })
        .on('keypress', '.foreignDocumentSeries', function (e) {
            var regex = new RegExp("^[a-zA-Z0-9]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }

            e.preventDefault();
            return false;
        });

    if($('*').is('.address-autocomplete')) {
        $(".address-autocomplete").suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "ADDRESS",
            count: 5,
            onSelect: function (suggestion) {
                var data = suggestion.data;
                var subjectType = $(this).data('address-type');

                $('[data-name='+subjectType+'_country]').val(data.country);
                $('[data-name='+subjectType+'_city]').val(data.city);

                $('[data-name='+subjectType+'_latitude]').val(data.geo_lat);
                $('[data-name='+subjectType+'_longitude]').val(data.geo_lon);

                $('[data-name='+subjectType+'_area_title]').val(data.city_district);
                $('[data-name='+subjectType+'_district_text]').val(data.city_area);

                $('[data-name='+subjectType+'_kladr]').val(data.city_kladr_id);

                $(this).change();

            }
        });
    }

}

function initTextControll(){

    $('.sum-max-value')
        .change(function () {
            var value = StringToFloat($(this).val());
            if(value > $(this).data("sum-max-value")){
                $(this).val($(this).data("sum-max-value"));
            }else if(value < 0){
                $(this).val("0");
            }else{
                $(this).val(CommaFormatted($(this).val()));
            }
        })
        .blur(function () {
            var value = StringToFloat($(this).val());
            if(value > $(this).data("sum-max-value")){
                $(this).val($(this).data("sum-max-value"));
            }else if(value < 0){
                $(this).val("0");
            }else{
                $(this).val(CommaFormatted($(this).val()));
            }
        })
        .keyup(function () {
            var value = StringToFloat($(this).val());
            if(value > $(this).data("sum-max-value")){
                $(this).val($(this).data("sum-max-value"));
            }else if(value < 0){
                $(this).val("0");
            }else{
                $(this).val(CommaFormatted($(this).val()));
            }
        });

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

    $('.to_up_letters')
        .change(function () {
            var value = $(this).val().toUpperCase();
            $(this).val(value);
        })
        .blur(function () {
            var value = $(this).val().toUpperCase();
            $(this).val(value);
        })
        .keyup(function () {
            var value = $(this).val().toUpperCase();
            $(this).val(value);
        });

    $('.only_en')
        .change(function () {
            var value = $(this).val().replace(/[^a-zA-Z0-9]/g,'');
            if(!$(this).attr('readonly')){
                $(this).val(value);
            }
        })
        .blur(function () {
            var value = $(this).val().replace(/[^a-zA-Z0-9]/g,'');
            if(!$(this).attr('readonly')){
                $(this).val(value);
            }
        })
        .keyup(function () {
            var value = $(this).val().replace(/[^a-zA-Z0-9]/g,'');
            if(!$(this).attr('readonly')){
                $(this).val(value);
            }
        });

    $('.only_ru')
        .change(function () {
            var value = $(this).val().replace(/[^а-яёЁА-Я0-9]/g,'');
            if(!$(this).attr('readonly')){
                $(this).val(value);
            }
        })
        .blur(function () {
            var value = $(this).val().replace(/[^а-яёЁА-Я0-9]/g,'');
            if(!$(this).attr('readonly')){
                $(this).val(value);
            }
        })
        .keyup(function () {
            var value = $(this).val().replace(/[^а-яёЁА-Я0-9]/g,'');
            if(!$(this).attr('readonly')){
                $(this).val(value);
            }
        });
}

function initSelect2() {
    if($('*').is('.select2')) {
        $('.select2').select2("destroy").select2({
            width: '100%',
            minimumInputLength: 3,
            dropdownCssClass: "bigdrop",
            dropdownAutoWidth: true
        });
    }

    if($('*').is('.select2-all')) {
        $('.select2-all').select2("destroy").select2({
            width: '100%',
            dropdownCssClass: "bigdrop",
            dropdownAutoWidth: true
        });
    }


    if($('*').is('.select2-ws')) {
        $('.select2-ws').select2("destroy").select2({
            width: '100%',
            dropdownCssClass: "bigdrop",
            dropdownAutoWidth: true,
            minimumResultsForSearch: -1
        });
    }
}

function myGetAjax(urls) {
    var res = (function () {
        start_wait();

        var val = null;

        $.ajax({
            'async': false,
            'url': urls,
            'success': function (data) {
                val = data;
                end_wait();
            }
        }).always(function () {
            end_wait();
        });

        return val;
    })();

    return res;
}

function myPostAjax(urls, param) {

    var res = (function () {
        start_wait();

        var val = null;

        $.ajax({
            type: "POST",
            data: param + '&_token=' + $('meta[name="csrf-token"]').attr('content'),
            'async': false,
            'url': urls,
            'success': function (data) {
                val = data;

            }
        }).always(function () {
            end_wait();
        });

        return val;
    })();
    end_wait();
    return res;
}

function start_wait() {
    //$('#shadow').fadeIn(200);
}

function end_wait() {
    //$('#shadow').fadeOut(200);
}


function CommaFormatted(amount) {
    String.prototype.replaceAll = function (search, replace) {
        return this.split(search).join(replace);
    }

    var delimiter = " "; // replace comma if desired
    amount = new String(amount);
    amount = amount.replaceAll(' ', '');
    amount = amount.replaceAll('ю', ',');
    amount = amount.replaceAll('б', ',');
    amount = amount.replaceAll('.', ',');

    var state = 0;

    var sim = '';
    sim = ',';
    if (amount.search(",") > 0) {
        state = 1;
    }

    var a = amount.split(sim, 2);
    var d = a[1];

    var i = parseInt(a[0], 10);
    if (isNaN(i)) {
        return '';
    }
    var minus = '';
    if (i < 0) {
        minus = '-';
    }
    i = Math.abs(i);
    var n = new String(i);
    var a = [];
    while (n.length > 3) {
        var nn = n.substr(n.length - 3);
        a.unshift(nn);
        n = n.substr(0, n.length - 3);
    }
    if (n.length > 0) {
        a.unshift(n);
    }
    n = a.join(delimiter);
    //alert(d);
    if (d) {
        d=d.replace(/[^0-9]/g,'');
        amount = n + ',' + d;
    }
    else {
        amount = n;
        if (state == 1) {
            amount += sim;
        }
    }

    // amount = n;
    amount = minus + amount;
    return amount;
}


/**************************/


//--------------------------------
//Следующий год
//--------------------------------

function a_get_next_year(curr, s_year) {
    original = ToUSADate(curr.value);
    if (original) original = strtotime(original);
    else return "";

    if (s_year) result = date('d.m.Y', strtotime('-1 days +' + s_year + ' years', original));
    else result = date('d.m.Y', strtotime('-1 days +1 years', original));
    return result;
}

//--------------------------------
//Конвертирование формата даты
//--------------------------------

function ToUSADate(curr_date) {
    curr_date = explode(' ', curr_date);
    curr_date[0] = explode('.', curr_date[0]);

    if (!curr_date[0][2]) return false;
    if (!is_numeric(curr_date[0][0])) return false;
    if (!is_numeric(curr_date[0][1])) return false;
    if (!is_numeric(curr_date[0][2])) return false;

    curr_date[0] = curr_date[0][2] + '-' + curr_date[0][1] + '-' + curr_date[0][0];
    curr_date = implode(' ', curr_date);

    return curr_date;
}


//--------------------------------
//Smart авто-подстановка
//--------------------------------

var _a_auto_array;

function a_auto(from, to) {
    if (typeof(a_auto_array) != 'object') a_auto_array = new Object();
    if (typeof(from) == 'object') from = from.value;
    to = $('#' + to);

    var may_change = true;
    if (from) {
        if (!to.val()) may_change = true;
        else if (a_auto_array[to.attr('id')] == to.val()) may_change = true;

        if (to.attr('readonly')) may_change = true;
    }

    if (may_change) {


        to.val(from);
        a_auto_array[to.attr('id')] = from;
    }
    else if (from) {

    }
}

function reload() {
    window.location.reload();
}

function parent_reload() {
    window.parent.location.reload();
}

function parent_reload_tab() {
    window.parent.reloadTab();
}

function format_price(price) {
    price = +price;
    return price.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, '$1 ');
}

function StringToFloat(str) {
    String.prototype.replaceAll = function (search, replace) {
        return this.split(search).join(replace);
    };

    var delimiter = " "; // replace comma if desired
    amount = new String(str);
    amount = amount.replaceAll(' ', '');
    amount = amount.replaceAll('ю', ',');
    amount = amount.replaceAll('б', ',');
    amount = amount.replaceAll(',', '.');
    return parseFloat(amount).toFixed(2);
}

function getFloatFormat(str) {
    str = str.replace(" ", "");
    str = str.replace(",", ".");
    return +str;
}


function gaussRound(num, decimalPlaces) {
    var d = decimalPlaces || 0,
        m = Math.pow(10, d),
        n = +(d ? num * m : num).toFixed(8),
        i = Math.floor(n), f = n - i,
        e = 1e-8,
        r = (f > 0.5 - e && f < 0.5 + e) ?
            ((i % 2 == 0) ? i : i + 1) : Math.round(n);
    return d ? r / m : r;
}

function getSumToProcent(tariff, amount) {
    tariff = parseFloat(StringToFloat(tariff));
    amount = parseFloat(StringToFloat(amount));
    return (amount/100)*tariff;
}

function set_end_dates(start_date) {
    var cur_date_tmp = start_date.split(".");

    var cur_date = new Date(cur_date_tmp[2], cur_date_tmp[1] - 1, cur_date_tmp[0]);

    var new_date = new Date(cur_date.setYear(cur_date.getFullYear() + 1));

    var new_date2 = new Date(new_date.setDate(new_date.getDate() - 1));

    $('.datepicker_end').val(getFormattedDate(new_date2));
}


function getFormattedDate(date) {
    var year = date.getFullYear();
    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;
    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;
    return day + '.' + month + '.' + year;
}

function getSelectOptions(array, selectedValue) {
    return array.reduce(function (prev, item) {
        var selected = item.id == selectedValue ? 'selected' : '';
        return prev + "<option value='" + item.id + "' " + selected + ">" + item.title + "</option>";
    }, []);
}

function resetFilter() {
    window.location = window.location.href.split('?')[0];
}


function getPlanningTimeHous(km){

    hour = Math.ceil(km / 70);
    hour_rest = Math.ceil(hour / 10)*8;
    hour = (hour+hour_rest);

    return hour;
}

function getPlanningTimeHousToDay(hour){
    temp = parseInt(hour/24);
    if(temp>0){
        temp_hour = hour-parseInt(temp*24);
        return temp+" д "+temp_hour+"ч";
    }
    return hour+" ч";
}


function exportString(type){
    head = $('#export_head').html();
    data = $('#export_data').html();

    res = myPostAjax('/export/', "type="+type+"&head="+head+"&data="+data);
    return res;
}


function exportXls(){
    str = exportString(1);
    exportSend(str, 1);
}

function exportCsv(){
    str = exportString(2);
    exportSend(str, 2);
}

function exportSend(str, type){
    $('#export_type').val(type);
    $('#export_str').val(str);
    $("#export_file").submit();
}



function sendSecurityService(id, type){
    res = myPostAjax('/security/create', "id="+id+"&types="+type);
    if(res) reload();
}

function sendUnderwritingService(id, type){
    res = myPostAjax('/underwriting/create', "id="+id+"&types="+type);
    if(res) reload();
}



function makeSelectOptions(optionsArray, selectedId) {
    var options = '';
    $.map(optionsArray, function (item) {
        var selected = item.id == selectedId ? 'selected' : '';
        options += "<option value='" + item.id + "' " + selected + ">" + item.title + "</option>";
    });
    return options;
}

function openFancyBoxFrame(href) {
    $('.fancybox').fancybox(
        parent.$.fancybox({
            type: 'iframe',
            href: href,
            fitToView: false,
            autoSize: true
        })
    );
}

function openFancyBoxFrameSize(href, width, height) {
    $('.fancybox').fancybox(
        parent.$.fancybox({
            type: 'iframe',
            href: href,
            width:width,
            height: height,
            autoSize: false,
        })
    );
}

function closeFancyBoxFrame() {
    window.parent.jQuery.fancybox.close();
}

function openPage(href) {
    window.location = href;
}

function openPageBlank(href) {
    window.open(href, '_blank');
}

function electronic_journal(id, type) {
    view_panel = 'plan';
    if(parseInt(type) == 1){
        view_panel = 'emergency';
    }
    openFancyBoxFrame("/objs/electronic_journal/"+view_panel+"/"+id+"/");
}


function setSelectUsers(select_name, val, response) {

    var options = "<option value='0'>Не выбрано</option>";
    response.map(function (item) {
        options += "<option value='" + item.id + "'>" + item.name + "</option>";
    });
    $("select."+select_name).html(options).select2('val', val);

}


function reloadTab() {

    window.parent.jQuery.fancybox.close();
    if(window.parent.TAB_INDEX){
        window.parent.selectTab(window.parent.TAB_INDEX);
    }

    if(window.parent.pageRelode){
        window.parent.initReload();
    }


}

function validate() {

    var msg_arr = [];

    $('.validate').each(function() {
        val = ($(this).val())?$(this).val():$(this).html();
        if(val.length<1){
            msg_arr.push('Заполните все поля');
            $(this).css("border-color","red");
        }

        $(this).click(function() {
            $(this).css("border-color", "");
        });

    });

    $('.valid_accept').each(function() {
        val = ($(this).val())?$(this).val():$(this).html();
        if(val.length<1){
            msg_arr.push('Заполните все поля');
            $(this).css("border-color","red");
            parent_div = $(this).data('parent');
            if(parent_div && parent_div.length>0){
                $("#"+parent_div).css("border-color","red");
            }

        }

        $(this).click(function() {
            $(this).css("border-color", "");
        });

    });

    $('.select2-all.valid_accept').each(function() {
        if($(this).val() == 0){
            msg_arr.push('Заполните все поля');
            $(this).css("border-color","red");
            field = $(this).parent().find('a');
            if(field){
                field.css("border-color","red");
            }
        }
        $(this).click(function() {
            $(this).css("border-color", "");

            field = $(this).parent().find('a');
            if(field){
                field.css("border-color","");
            }
        });
    });


    if($('*').is('.valid_phone')) {
        $('.valid_phone').each(function() {
            val = ($(this).val())?$(this).val():$(this).html();
            if(val.length > 1 && val.length<18){
                msg_arr.push('Заполните все поля');
                $(this).css("border-color","red");
            }

            $(this).click(function() {
                $(this).css("border-color", "");
            });

        });
    }

    if($('*').is('.valid_email')) {
        $('.valid_email').each(function() {
            val = ($(this).val())?$(this).val():$(this).html();
            if(val.length > 1 && isEmail(val) == false){
                msg_arr.push('Заполните все поля');
                $(this).css("border-color","red");
            }

            $(this).click(function() {
                $(this).css("border-color", "");
            });

        });
    }


    if (msg_arr.length) {
        return false;
    }
    return true;

}

function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}

function deleteItem(url, id) {
    if (!customConfirm()) return false;

    $.post(url + id, {
        _method: 'delete'
    }, function () {
        parent_reload();
    });
}


function openLogEvents(object_id, type_id, view_all) {

    urls = '/log/events/?object_id='+object_id+'&type_id='+type_id+'&view_all='+view_all;
    //view_all 0 по объекту, 1 Видеть по родителю, 2 Видеть все
    if(window.parent){
        window.parent.jQuery.fancybox.close();
        window.parent.openFancyBoxFrame(urls);
    }else{
        openFancyBoxFrame(urls);
    }

    
}



function getOptionInstallmentAlgorithms(object_id, insurance_companies_id, select_id)
{

    $.getJSON("/bso/actions/get_installment_algorithms/", {insurance_companies_id: insurance_companies_id}, function (response) {
        var options = "<option value='0'>Не выбрано</option>";
        response.map(function (item) {
            atrr = '';

            if(parseInt(select_id) == item.id) { 
                atrr = 'selected';
                $('#payment_algo').show();
            }

            options += "<option "+atrr+" value='" + item.id + "'>" + item.title + "</option>";
        });
        $("#"+object_id).html(options);
    });

}

function getOptionFinancialPolicy(object_id, insurance_companies_id, bso_supplier_id, product_id, select_id)
{

    $.getJSON("/bso/actions/get_financial_policy/", {insurance_companies_id: insurance_companies_id, bso_supplier_id: bso_supplier_id, product_id: product_id}, function (response) {
        var options = "<option value='0'>Не выбрано</option>";
        response.map(function (item) {
            atrr = '';
            if(parseInt(select_id) == item.id)  atrr = 'selected';

            options += "<option "+atrr+" value='" + item.id + "'>" + item.title + "</option>";
        });
        $("#"+object_id).html(options);
    });

}



function build_query(params){
    var esc = encodeURIComponent;
    return Object.keys(params).map(function(k){return esc(k)+'='+esc(params[k])}).join('&');
}



function flashMessage(type, msg){
    var msg_block = $('<div class="alert alert-'+type+'"><button class="close" data-close="alert"></button><span>'+msg+'</span>\n</div>');
    setTimeout(function () {
        msg_block.animate({
            opacity: 0.1,
            //top: "-=250"
        }, 800, function () {
            $(this).remove();
        });
    }, 4000);
    $('#messages').append($(msg_block));
    $('.flash-message').append($(msg_block));

}


function flashValidationErrors(response_errors){
    $.each(response_errors, function(k,v){
        flashMessage('danger', v[0]);
    })
}


function setLastMonth(){
    var date = new Date();
    var todayDate = (date.getDate().toString().length > 1 ? date.getDate() : "0" + date.getDate()) + '.' + ((date.getMonth()+1).toString().length > 1 ? date.getMonth()+1 : "0" + (date.getMonth()+1)) + '.' + date.getFullYear();
    date.setMonth(date.getMonth()-1);
    var prevDate = (date.getDate().toString().length > 1 ? date.getDate() : "0" + date.getDate()) + '.' + ((date.getMonth()+1).toString().length > 1 ? date.getMonth()+1 : "0" + (date.getMonth()+1)) + '.' + date.getFullYear();
    $('#begin_date').val(prevDate);
    $('#end_date').val(todayDate);
    $('#end_date').blur();
}

$('.collapse').on('show.bs.collapse', function () {
 $('.collapse.in').each(function(){
   $(this).collapse('hide');
 });
});


function setUserTextSize(obj) {
    text_size = $(obj).val();

    myPostAjax("/account/size","text_size="+text_size);

    var style = $(window.parent.document.head).children('style[data-html="user"]');
    var new_style = style.clone();
    style.remove();

    new_style.text('\n\r body>*, body { \n font-size:' + text_size + 'px!important; }');
    $(window.document.head).append(new_style);


}

function copyContract(id){
    loaderShow();

    $.post("/contracts/online/"+id+"/copy", {}, function (response) {
        loaderHide();
        if (Boolean(response.state) === true) {

            window.location = "/contracts/online/"+response.id;

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

    }).done(function() {
        loaderShow();
    }).fail(function() {
        loaderHide();
    }).always(function() {
        loaderHide();
    });


}

function prolongationContract(id){
    loaderShow();

    $.post("/contracts/online/"+id+"/prolongation", {}, function (response) {
        loaderHide();
        if (Boolean(response.state) === true) {

            window.location = "/contracts/online/"+response.id;

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

    }).done(function() {
        loaderShow();
    }).fail(function() {
        loaderHide();
    }).always(function() {
        loaderHide();
    });


}

function prolongationToContract(id, is_id) {

    if (!customConfirm()) {
        return false;
    }


    loaderShow();

    $.post("/contracts/online/"+id+"/prolongation", {is_id:is_id}, function (response) {
        loaderHide();
        if (Boolean(response.state) === true) {

            reload();

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

    }).done(function() {
        loaderShow();
    }).fail(function() {
        loaderHide();
    }).always(function() {
        loaderHide();
    });
}


function editStatusContract(id){
    loaderShow();

    $.post("/contracts/online/"+id+"/edit-status", {}, function (response) {
        loaderHide();
        if (Boolean(response.state) === true) {

            window.location = "/contracts/online/"+response.id;

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

    }).done(function() {
        loaderShow();
    }).fail(function() {
        loaderHide();
    }).always(function() {
        loaderHide();
    });


}



function cancelContract(id){
    openFancyBoxFrame("/contracts/online/"+id+"/cancel");
}




function days_between(start_date, end_date) {

    if(start_date.length <=0 ) return '';
    if(end_date.length <=0 ) return '';

    var cur_date_tmp1 = start_date.split(".");
    var cur_date_tmp2 = end_date.split(".");

    date1 = new Date(cur_date_tmp1[2], cur_date_tmp1[1] - 1, cur_date_tmp1[0]);
    date2 = new Date(cur_date_tmp2[2], cur_date_tmp2[1] - 1, cur_date_tmp2[0]);

    // The number of milliseconds in one day
    var ONE_DAY = 1000 * 60 * 60 * 24;

    // Convert both dates to milliseconds
    var date1_ms = date1.getTime();
    var date2_ms = date2.getTime();

    // Calculate the difference in milliseconds
    var difference_ms = Math.abs(date1_ms - date2_ms);

    // Convert back to days and return
    return Math.round(difference_ms/ONE_DAY);

}


function get_end_dates_day(start_date, day) {
    var cur_date_tmp = start_date.split(".");

    var cur_date = new Date(cur_date_tmp[2], cur_date_tmp[1]-1, cur_date_tmp[0]);
    var new_date = cur_date;//new Date(cur_date.setYear(cur_date.getFullYear() + 1));
    var new_date2 = new Date(new_date.setDate(new_date.getDate() + day));
    return getFormattedDate(new_date2);
}



function clear_all_messages() {

    if(myGetAjax("/account/notification/clear-all")){
        $("#user-messages-form").html('<p>Нет уведомлений</p>');
        $(".user-alerts-counter").html('<span class="round-big"></span><span class="round-small">0</span>');
    }


}




function searchGeneralOrganization() {
    $('.searchGeneralOrganization').suggestions({
        serviceUrl: "/suggestions/dadata/general",
        token: "",
        type: "PARTY",
        count: 5,
        formatResult: function(e, t, n, i) {
            var s = this;
            e = s.highlightMatches(e, t, n, i), s.wrapFormattedValue(e, n);
            return e;
        },

        onSelect: function (suggestion) {

            $("#"+$(this).data("set-id")).val(suggestion.id);

        }
    });
}

function searchGeneralUser() {
    $('.searchGeneralUser').suggestions({
        serviceUrl: "/suggestions/dadata/general",
        token: "",
        type: "NAME",
        count: 5,
        formatResult: function(e, t, n, i) {
            var s = this;
            e = s.highlightMatches(e, t, n, i), s.wrapFormattedValue(e, n);
            return e;
        },

        onSelect: function (suggestion) {

            $("#"+$(this).data("set-id")).val(suggestion.id);
            $(this).change();

        }
    });
}




function searchGeneralAll() {
    $('.searchGeneralAll').suggestions({
        serviceUrl: "/suggestions/dadata/general",
        token: "",
        type: "PARTY",
        count: 5,
        params:{type:-1},
        formatResult: function(e, t, n, i) {
            var s = this;
            e = s.highlightMatches(e, t, n, i), s.wrapFormattedValue(e, n);
            return e;
        },

        onSelect: function (suggestion) {

            $("#"+$(this).data("set-id")).val(suggestion.id);

        }
    });
}

function viewCitizenship(subject_name) {

    if($('#'+subject_name+'_is_resident').prop('checked')){
        $('.'+subject_name+'_is_not_resident').hide();
    }else{
        $('.'+subject_name+'_is_not_resident').show();
    }

}


function checkAll(obj) {
    $('.item_checkbox').prop('checked', $(obj).is(':checked'));
    showCheckedOptions();
}

function showCheckedOptions() {
    if ($('.item_checkbox:checked').length > 0) {
        $('.event_form').show();
    } else {
        $('.event_form').hide();
    }
}

function getCheckedOptions() {

    var item = [];
    $('.item_checkbox:checked').each(function () {
        item.push($(this).val());
    });

    return item;
}

function resetCheckedOptions() {
    $('.item_checkbox').prop('checked', false);
    $('#check_all').prop('checked', false);
    showCheckedOptions();
}