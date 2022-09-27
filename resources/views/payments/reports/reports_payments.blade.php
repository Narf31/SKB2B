@php
    $is_reports = stristr(request()->route()->getPrefix(), 'reports/');
@endphp

<table class="table table-bordered bso_items_table">
    <thead>
    <tr>
        <th rowspan="2" class="text-center">
            <input type="checkbox" name="all_payments">
            (<span class="total_count">{{$payments->count()}}</span>)
        </th>
        <th rowspan="2">Филиал</th>
        <th rowspan="2">Продавец</th>
        <th rowspan="2">№ договора</th>

        <th rowspan="2">Метод оплаты</th>
        <th rowspan="2">Тип платежа</th>
        <th rowspan="2">Поток оплаты</th>

        <th rowspan="2">Сумма</th>
        <th rowspan="2">Оф.скидка</th>

        <th colspan="3">Бордеро</th>
        <th colspan="3">ДВОУ</th>

        <th rowspan="2">Продукт</th>
        <th rowspan="2">Страхователь</th>

        <th colspan="3">Даты договора</th>
        <th rowspan="2">Маркер &nbsp; &nbsp;</th>
    </tr>
    <tr>

        <th class="text-center">КВ %</th>
        <th class="text-center">КВ Сумма</th>
        <th class="text-center">Отчет</th>

        <th class="text-center">КВ %</th>
        <th class="text-center">КВ Сумма</th>
        <th class="text-center">Отчет</th>


        <th class="text-center">Заведён</th>
        <th class="text-center">Начало</th>
        <th class="text-center">Окончание</th>

    </tr>
    </thead>
    <tbody class="payments_table_tbody">
    @if(sizeof($payments))
        @foreach($payments as $reporst_payment)
            @php
                $payment = $reporst_payment->payment;
            @endphp
            <tr @if($payment->contract && $payment->contract->is_all_docs_exist == 0) style="background-color: #ffcccc;" @elseif(strlen($reporst_payment->marker_color) > 3) style="background-color: {{$reporst_payment->marker_color}}" @elseif($payment->payment_flow == 1) style="background-color: #fffae6;" @endif>
                <td class="text-center">
                    <input class="payment_checkbox" type="checkbox"  name="payment[]" value="{{$reporst_payment->id}}">
                </td>

                <td>{{ $payment->bso ? $payment->bso->supplier->title : "" }}</td>
                <td>{{$reporst_payment->agent->name}}</td>
                <td>
                    <a href="{{url("/contracts/online/{$payment->contract->id}/")}}" target="_blank">
                        {{ $payment->bso ? $payment->bso->bso_title : "" }}
                    </a>
                </td>
                <td>

                    @if($payment->bso_receipt_id > 0)
                        {{$payment->receipt->bso_title}}
                    @else
                        {{($payment->payment_method)?$payment->payment_method->title:''}}
                    @endif
                </td>

                <td>{{ \App\Models\Contracts\Payments::PAYMENT_TYPE[$payment->payment_type] }}</td>
                <td>{{ \App\Models\Contracts\Payments::PAYMENT_FLOW[$payment->payment_flow] }}</td>

                <td nowrap>{{ getPriceFormat($payment->payment_total) }}</td>
                <td nowrap>{{ getPriceFormat($payment->official_discount_total) }}</td>

                <td nowrap class="text-center">{{ getPriceFormat($reporst_payment->financial_policy_kv_bordereau) }}</td>
                <td nowrap class="text-center">{{ getPriceFormat($reporst_payment->financial_policy_kv_bordereau_total) }}</td>
                <td>
                    <a href="{{url("/reports/order/{$reporst_payment->reports_order_id}/")}}" target="_blank">
                        {{($reporst_payment->reports_border)?$reporst_payment->reports_border->title:''}}
                    </a>
                </td>
                <td nowrap class="text-center">{{ getPriceFormat($reporst_payment->financial_policy_kv_dvoy) }}</td>
                <td nowrap class="text-center">{{ getPriceFormat($reporst_payment->financial_policy_kv_dvoy_total) }}</td>
                <td>
                    <a href="{{url("/reports/order/{$reporst_payment->reports_dvou_id}/")}}" target="_blank">
                        {{$reporst_payment->reports_dvoy?$reporst_payment->reports_dvoy->title:''}}
                    </a>
                </td>

                <td>{{ $payment->bso && $payment->bso->product ? $payment->bso->product->title : ""}}</td>

                <td>{{ $payment->contract->insurer->title }}</td>

                <td class="text-center">{{ $payment->contract ? getDateFormatRu($payment->contract->sign_date) : "" }}</td>
                <td class="text-center">{{ $payment->contract ? getDateFormatRu($payment->contract->begin_date) : "" }}</td>
                <td class="text-center">{{ $payment->contract ? getDateFormatRu($payment->contract->end_date) : "" }}</td>


                <td>{{ $reporst_payment->marker_text }}</td>

            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="24" class="text-center">Нет платежей</td>
        </tr>
    @endif
    </tbody>
</table>