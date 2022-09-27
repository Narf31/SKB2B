@php
    $total_sum = collect($invoice->payments)->sum('payment_total');
    $total_kv_agent = collect($invoice->payments)->sum('financial_policy_kv_agent_total');

    $sales_condition = $invoice->payments->first()->contact ? $invoice->payments->first()->contact->sales_condition : 0;

    $sale_to_org = $sales_condition == 1;


@endphp


<html>

<head>
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>


<body>
<div>
    <table style="width: 80%;float: left">
        <tr class='td_head'>
            <td>
                <h2 style="display: inline">СЧЕТ № {{$invoice->id}} </h2><a class="sign-name">от {{date('d.m.Y H:i', time())}}</a>

                <a href="#" class="btn btn-success btn-right" id="print_btn">Печать</a>

                <p class="up">Настоящий Отчет является основанием того, что Агент</p>
                <h2 style="margin: 0;">{{$invoice->user->name}},</h2>

                <p class="up">действующий на основании Договора №<a class="sign-name"> 7022-0391 от 14.02.10</a></p>
                произвел взаиморасчеты с Обществом по следующим работам:
            </td>
        </tr>
    </table>
    <div style="width:20%;float: left;text-align: right">
        <div id="bcTarget"></div>
    </div>
</div>

<div style="margin-bottom: 10px;">
</div>

<table class="table table-striped table-bordered">
    <thead>
    <tr>
        <th>№</th>
        <th>Индекс</th>
        <th>Организация</th>
        <th>СК</th>
        <th>Страхователь</th>
        <th>Продукт</th>
        <th>Тип платежа</th>
        <th>№ договора</th>
        <th>Квитанция</th>
        <th>Сумма</th>
        <th>Неофф.скидка %</th>
        <th>Неофф.скидка</th>

        @if(!$sale_to_org)
            <th>Оф.скидка</th>
            <th>КВ агента, %</th>
            <th>КВ агента, руб</th>
        @endif

        <th>К оплате</th>
    </tr>
    </thead>
    <tbody>
    @php($total = 0)
    @php($total_kv_agent = 0)
    @php($total_sum = 0)
    @if(sizeof($invoice->payments))
        @foreach($invoice->payments as $key => $payment)
            @php($total += $payment->payment_total)
            @php($total_kv_agent += $payment->financial_policy_kv_agent_total)
            @php($total_sum += $payment->getPaymentAgentSum())
            <tr @if($payment->is_deleted == 1) style="background-color: #ffcccc;" @endif>



                <td>{{ $key+1 }}</td>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->org ? $payment->org->title : "" }}</td>
                <td>{{ $payment->bso && $payment->bso->insurance ? $payment->bso->insurance->title : "" }}</td>
                <td>{{ $payment->getInsurer() }}</td>
                <td>{{ $payment->bso && $payment->bso->product ? $payment->bso->product->title : ""}}</td>
                <td>{{ $payment->type_ru() }}</td>
                <td>{{ $payment->bso ? $payment->bso->bso_title : "" }}</td>
                <td>{{ $payment->bso_receipt ? : "" }}</td>
                <td>{{ getPriceFormat($payment->payment_total) }}</td>
                <td>{{ getPriceFormat($payment->informal_discount) }}</td>
                <td>{{ getPriceFormat($payment->informal_discount_total) }}</td>

                @if(!$sale_to_org)
                    <td>{{ getPriceFormat($payment->official_discount_total) }}</td>
                    <td>{{ $payment->financial_policy_kv_agent }}</td>
                    <td>{{ getPriceFormat($payment->financial_policy_kv_agent_total) }}</td>
                @endif

                <td>{{ getPriceFormat($payment->getPaymentAgentSum()) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan='8'>&nbsp</td>
            <td><strong class="itogo">ИТОГО:</strong></td>
            <td><strong>{{ getPriceFormat($total) }}</strong></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><strong>{{ getPriceFormat($total_kv_agent) }}</strong></td>
            <td><strong>{{ getPriceFormat($total_sum) }}</strong></td>
        </tr>

    @endif
    </tbody>
</table>



<div class="list list-first">
    2. В данном Отчете отражены работы Агента на сумму взносов <a class="summ">{{ getPriceFormat($total_sum) }}</a> руб.
</div>
<div class="list">
    3. За проделанную работу в соотв. с п.10.2 Договора на оказание услуг Агенту полагается вознаграждение в размере
    <a class="summ">{{ getPriceFormat($total_kv_agent) }}</a> руб.
</div>
<div class="list">
    4. С учетом безналичных платежей и вознаграждения к передаче в Кассу по данному документу причитается
    <span class="summ">{{ getPriceFormat($total_sum-$total_kv_agent) }}</span> руб.
</div>
<div class="list">
    5. Итого с учетом соглашения об округлении платежей сумма <a class="kassa">К ПЕРЕДАЧЕ В КАССУ:</a>
</div>
<div class="full_summ">
    {{getPriceFormat($total_sum) }}({{num2str($total_sum)}}) рублей
</div>
<div class="list">
    6. В соответствии с гл. 4 Гражданского кодекса РФ от 12.08.2004 настоящий Отчет является Актом выполненных работ.
</div>
<div class="list">
    7. Стороны признают электронный вариант настоящего Отчета, пересланный с официального электронного адреса Общества
    на электронный адрес Агента.
</div>

<table width="100%" cellpadding="30" class="footer_table">
    <tr>
        <td cellpadding='50' width='50%'>От Общества отчет подготовил:</td>
        <td class="line" width='50%'>Агент:</td>
    </tr>
    <tr>
        <td class="sign-name">_______________{{$invoice->payments->first()->bso->supplier_org->general_manager}}</td>
        <td class="sign-name line"> _______________{{$invoice->user->name}}</td>
    </tr>
</table>



<style>

    .btn {
        font-size: 14px;
        outline: none;
        -webkit-text-size-adjust: none;
        color: #ffffff;
        text-transform: uppercase;
        height: 18px;
        padding:8px 12px;
        border: none;
        cursor: pointer;
        display: block;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        font-family: AgoraSansProRegular, sans-serif;
        max-height: 18px;
    }

    .btn-success{
        background-color: rgb(60, 178, 25);
        background-image: -o-linear-gradient(right, rgb(20, 130, 52) 0%, rgb(60, 178, 25) 100%);
        background-image: -moz-linear-gradient(right, rgb(20, 130, 52) 0%, rgb(59, 178, 25) 100%);
        background-image: -webkit-linear-gradient(right, rgb(20, 130, 52) 0%, rgb(59, 178, 25) 100%);
        background-image: -ms-linear-gradient(right, rgb(20, 130, 52) 0%, rgb(59, 178, 25) 100%);
        background-image: linear-gradient(to right, rgb(20, 130, 52) 0%, rgb(59, 178, 25) 100%);
    }

    .btn-success:hover, .btn-success:active, .btn-success:focus {
        background-color: rgb(20, 141, 52);
        background-image: -o-linear-gradient(right, rgb(20, 141, 52)  0%, rgb(59, 189, 25) 100%);
        background-image: -moz-linear-gradient(right, rgb(20, 141, 52) 0%, rgb(59, 189, 25) 100%);
        background-image: -webkit-linear-gradient(right, rgb(20, 141, 52) 0%, rgb(59, 189, 25) 100%);
        background-image: -ms-linear-gradient(right, rgb(20, 141, 52) 0%, rgb(59, 189, 25) 100%);
        background-image: linear-gradient(to right, rgb(20, 141, 52) 0%, rgb(59, 189, 25) 100%);
        background-image: -webkit-gradient(linear, left bottom, right bottom, color-stop(0%, rgb(20, 141, 52)), color-stop(100%, rgb(59, 189, 25)));
    }

    .btn-right{ float: right; position: absolute; right: 20px; top:20px;}

    .table {
        border-collapse: collapse;
        border: 1px solid #777;
        margin-bottom: 10px;
        font: 11px arial;
    }

    .table td, th {
        padding: 5px;
        border: 1px solid #777;
    }

    .delete_cashbox, .delete_payment {
        cursor: pointer;
        text-decoration: underline;
    }

    .blue {
        color: blue;
        font-size: 22px
    }

    .summ {
        color: blue;
    }

    .full_summ {
        color: blue;
        text-align: center
    }

    .list {
        margin: 5px;
        font-size: 12px
    }

    .list-first {
        margin: 20px 0 0 4px
    }

    .kassa {
        color: green;
    }

    .sign {
        margin: 80px 80px 0 180px;
        float: left;
    }

    .sign-name {
        text-align: right;
        font-size: 12px
    }

    .qr {
        text-align: right;
        width: 20%
    }

    .line {
        border-left: 1px #3366FF solid
    }

    .table {
        font-size: 12px;
        border-collapse: collapse
    }

    .table td, th {
        border: 1px solid black
    }

    .up {
        margin: 0;
    }

    .td_head {
        font-size: 11px
    }

    .footer_table {
        font-size: 12px;
        border-collapse: collapse
    }

    .itogo {
        float: right
    }

    @media print {
        @page { margin: 0; }
        body { margin: 0.8cm; }
        #print_btn{
            display:none;
        }
    }
</style>


<script src="/plugins/jquery/jquery.min.js"></script>

<script>
    $(document).on('click', '#print_btn', function(){
        window.print()
    });

</script>

</body>

</html>

