<table id="tableControl" class="tov-table">
    <thead>
        <tr>
            <td>Стоимость автомобиля</td>
            <td>Максимальная сумма возмещения</td>
            <td>Нетто-Премия</td>
            <td>Маркетинговая Комиссия</td>
            <td>ГРОСС-Техническая Премия</td>
        </tr>
    </thead>
    <tbody>
    @foreach(\App\Models\Directories\Products\Data\GAP\BaseRateGap::CONF_GAP as $key => $_conf)

        @php
            $baseRate = \App\Models\Directories\Products\Data\GAP\BaseRateGap::getBaseRateList($risks_id, $key);
        @endphp

        <tr>
            <td>
                {{titleNumberFormat($_conf['amount_from'])}} - {{titleNumberFormat($_conf['amount_to'])}}
            </td>
            <td>
                {{ Form::text("conf[$key][max_amount]", titleFloatFormat($baseRate->max_amount, 0, 1), ['class' => 'form-control sum']) }}
            </td>
            <td>
                {{ Form::text("conf[$key][net_premium]", titleFloatFormat($baseRate->net_premium, 0, 1), ['class' => 'form-control sum', 'id'=>"net_premium_{$key}", "onchange"=>"setSumTechnicalPayment({$key})"]) }}
            </td>
            <td>
                {{ Form::text("conf[$key][marketing_kv]", titleFloatFormat($baseRate->marketing_kv, 0, 1), ['class' => 'form-control sum', 'id'=>"marketing_kv_{$key}", "onchange"=>"setSumTechnicalPayment({$key})"]) }}
            </td>
            <td>
                {{ Form::text("conf[$key][technical_payment]", titleFloatFormat($baseRate->technical_payment, 0, 1), ['readonly', 'class' => 'form-control sum', 'id'=>"technical_payment_{$key}"]) }}
            </td>
        </tr>
    @endforeach

    </tbody>
</table>



<script>

    function setSumTechnicalPayment(key) {


        net_premium = parseFloat(StringToFloat($("#net_premium_" + key).val()));
        marketing_kv = parseFloat(StringToFloat($("#marketing_kv_" + key).val()));
        technical_payment = net_premium+marketing_kv;
        $("#technical_payment_" + key).val(CommaFormatted(technical_payment));

    }

</script>