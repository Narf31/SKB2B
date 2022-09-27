<script>


    $(function () {

        init();

    });


    function init() {

        initTerms();




    }


    // Замыкание
    (function() {
        /**
         * Корректировка округления десятичных дробей.
         *
         * @param {String}  type  Тип корректировки.
         * @param {Number}  value Число.
         * @param {Integer} exp   Показатель степени (десятичный логарифм основания корректировки).
         * @returns {Number} Скорректированное значение.
         */
        function decimalAdjust(type, value, exp) {
            // Если степень не определена, либо равна нулю...
            if (typeof exp === 'undefined' || +exp === 0) {
                return Math[type](value);
            }
            value = +value;
            exp = +exp;
            // Если значение не является числом, либо степень не является целым числом...
            if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
                return NaN;
            }
            // Сдвиг разрядов
            value = value.toString().split('e');
            value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
            // Обратный сдвиг
            value = value.toString().split('e');
            return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
        }

        // Десятичное округление к ближайшему
        if (!Math.round10) {
            Math.round10 = function(value, exp) {
                return decimalAdjust('round', value, exp);
            };
        }
        // Десятичное округление вниз
        if (!Math.floor10) {
            Math.floor10 = function(value, exp) {
                return decimalAdjust('floor', value, exp);
            };
        }
        // Десятичное округление вверх
        if (!Math.ceil10) {
            Math.ceil10 = function(value, exp) {
                return decimalAdjust('ceil', value, exp);
            };
        }
    })();


    function sumBaseTarifeToKV()
    {

        sum_kv = 0;
        $(".kv_sum").each(function () {

            if($(this).val().length > 0){
                sum_kv = parseFloat(StringToFloat(sum_kv)) + parseFloat(StringToFloat($(this).val()));
            }

        });


        original_tariff = parseFloat(StringToFloat($("#original_tariff").val())).toFixed(2);
        base_tariff = parseFloat(original_tariff/(1-(sum_kv/100))).toFixed(4);
        base_tariff = gaussRound(base_tariff, 2);


        ___base_tariff = parseFloat(StringToFloat($("#base_tariff").val())).toFixed(2);
        ___manager_tariff = parseFloat(StringToFloat($("#manager_tariff").val())).toFixed(2);

        if(___manager_tariff == 0 || ___base_tariff == ___manager_tariff){
            $("#manager_tariff").val(CommaFormatted(parseFloat(base_tariff).toFixed(2)));
        }

        $("#base_tariff").val(CommaFormatted(parseFloat(base_tariff).toFixed(2)));

        titleViewSum();

    }


    function titleViewSum() {

        original_payment_total = parseFloat(getSumToProcent($("#original_tariff").val(), $("#insurance_amount").val())).toFixed(2);
        base_payment_total = parseFloat(getSumToProcent($("#base_tariff").val(), $("#insurance_amount").val())).toFixed(2);
        manager_payment_total = parseFloat(getSumToProcent($("#manager_tariff").val(), $("#insurance_amount").val())).toFixed(2);



        ___base_payment_total = parseFloat(StringToFloat($("#base_payment_total").html())).toFixed(2);
        ___manager_payment_total = parseFloat(StringToFloat($("#manager_payment_total").val())).toFixed(2);

        if(___manager_payment_total == 0 || ___base_payment_total == ___manager_payment_total){
            $("#manager_payment_total").val(CommaFormatted(manager_payment_total));
        }

        $("#original_payment_total").html(CommaFormatted(original_payment_total));
        $("#base_payment_total").html(CommaFormatted(base_payment_total));

    }


    function setManagerTariff() {
        manager_payment_total = parseFloat(StringToFloat($("#manager_payment_total").val()));
        insurance_amount = parseFloat(StringToFloat($("#insurance_amount").val()));
        manager_tariff = (manager_payment_total/insurance_amount)*100;
        $("#manager_tariff").val(CommaFormatted(manager_tariff));
    }


    function setManagerPaymentTotal() {
        $("#manager_payment_total").val(CommaFormatted(getSumToProcent($("#manager_tariff").val(), $("#insurance_amount").val())));

    }




    function getOriginalTariff() {


        loaderShow();

        $.post('/contracts/online/{{$contract->id}}/action/view-control', $('#product_form').serialize(), function (response) {

            $("#original_tariff").val(CommaFormatted(response.tariff));
            sumBaseTarifeToKV();


        }).always(function () {
            loaderHide();
        });

        return true;

    }


    function setGeneralSubjects(name)
    {



    }





</script>

