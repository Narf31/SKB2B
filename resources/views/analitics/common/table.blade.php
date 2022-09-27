@php
    $analiticsVisibility = auth()->user()->role->visibility('analitics');
    /*Не доделаны значения Акцепт 	Дата по кассе 	Условие продажи 	Тип оплаты 	Тип платежа 	Личная продажа 	Маржа и тд*/

    $all_payment_total = 0;
    $all_invoice_payment_total = 0;
    $all_official_discount_total = 0;
    $all_informal_discount_total = 0;
    $all_kv_agent_total = 0;
    $all_kv_borderau_total = 0;
    $all_kv_parent_total = 0;
    $all_kv_total = 0;
    $all_margin_total = 0;

@endphp

<table class="table table-bordered text-left payments_table">
    <tbody>
        <tr>
            <th>№</th>
            <th>Организация</th>
            <th>СК</th>
            <th>Продукт</th>
            <th>Полис №</th>
            <th>Квитанция №</th>
            <th>Страхователь</th>
            <th>Дата договора</th>
            <th>Дата оплаты</th>
            <th>Акцепт</th>
            <th>Условие продажи</th>
            <th>Тип</th>
            <th>Взнос</th>
            <th>Тип оплаты</th>
            <th>Поток оплаты</th>
            <th>Статус оплаты</th>
            <th>Дата по кассе</th>
            <th>Сумма в кассу</th>
            <th>Счет №</th>
            <th>Кассир</th>
            <th colspan="2">Оф. скидка</th>
            <th colspan="2">Неоф. скидка</th>
            {{--<th>КВ агента %</th>
            <th colspan="2">Вознаграждение агента</th>--}}
            <th>КВ бордеро %</th>
            <th colspan="2">КВ Бордеро сумма</th>

            @if(in_array($analiticsVisibility, [0,1,3]))
                <th>КВ Руководителя %</th>
                <th>Вознаграждение руководитея</th>
                <th>Агент</th>
                <th>Менеджер</th>
            @endif

            @if(in_array($analiticsVisibility, [0,1]))

                <th>КВ Входящая %</th>
                <th>Вознаграждение брокера</th>

                <th>Маржа %</th>
                <th>Маржа сумма</th>

            @endif

        </tr>

        @foreach($payments as $key => $payment)
            @php
                $all_payment_total += $payment->payment_total;
                $all_invoice_payment_total += $payment->invoice_payment_total;
                $all_official_discount_total += $payment->official_discount_total;
                $all_informal_discount_total += $payment->informal_discount_total;
                $all_kv_agent_total += $payment->financial_policy_kv_agent_total;
                $all_kv_borderau_total += $payment->financial_policy_kv_bordereau_total;
                $all_kv_parent_total += $payment->financial_policy_kv_parent_total;
                $all_kv_total += $payment->financial_policy_kv_bordereau_total + $payment->financial_policy_kv_dvoy_total;
                $all_margin_total += $payment->getMarginAmount(1);
            @endphp

            <tr>
                <td>{{$key+1}}</td>
                <td>{{$payment->org ? $payment->org->title : ""}}</td>
                <td>{{ $payment->bso && $payment->bso->insurance ? $payment->bso->insurance->title : "" }}</td>
                <td>{{ $payment->bso && $payment->bso->product ? $payment->bso->product->title : ""}}</td>
                <td><a href="{{url("/contracts/online/{$payment->contract_id}")}}">{{$payment->bso->bso_title}}</a></td>
                <td>@if($payment->bso_receipt_id > 0) <a href="{{url("/bso/items/{$payment->bso_receipt_id}/")}}" target="_blank">{{$payment->bso_receipt}}</a> @endif</td>
                <td>{{ $payment->getInsurer() }}</td>
                <td>{{ setDateTimeFormatRu($payment->contract->sign_date,1) }}</td>
                <td>{{ setDateTimeFormatRu($payment->payment_data,1) }}</td>
                <td>{{ $payment->contract ? $payment->contract->kind_acceptance_ru('kind_acceptance') :"" }}</td>
                <td>{{ $payment->contract ? $payment->contract->sales_condition_ru('sales_condition') :"" }}</td>
                <td>{{\App\Models\Contracts\Payments::TRANSACTION_TYPE[$payment->type_id]}} @if($payment->type_id == 0) {{$payment->payment_number}} @endif</td>
                <td>{{titleFloatFormat($payment->payment_total)}}</td>
                <td>{{\App\Models\Contracts\Payments::PAYMENT_TYPE[$payment->payment_type]}}</td>
                <td>{{\App\Models\Contracts\Payments::PAYMENT_FLOW[$payment->payment_flow]}}</td>
                <td>{{ $payment->status_ru('statys_id') }}</td>
                <td>{{ $payment->invoice_payment_date ? getDateFormatRu($payment->invoice_payment_date) : "" }}</td>
                <td>{{ getPriceFormat($payment->invoice_payment_total) }}</td>
                <td><a href="{{url("/finance/invoice/{$payment->invoice_id}/view")}}" target="_blank"> {{$payment->invoice_id>0?$payment->invoice_id:''}} </a></td>
                <td>{{($payment->invoice)?(($payment->invoice->invoice_payment_user)?$payment->invoice->invoice_payment_user->name:''):''}}</td>
                <td colspan="2">{{ getPriceFormat($payment->official_discount_total) }}</td>
                <td colspan="2">{{ getPriceFormat($payment->informal_discount_total) }}</td>

                {{--<td>{{$payment->financial_policy_kv_agent}}</td>
                <td colspan="2">{{ getPriceFormat($payment->financial_policy_kv_agent_total) }}</td>--}}
                <td>{{$payment->financial_policy_kv_bordereau}}</td>
                <td colspan="2">{{ getPriceFormat($payment->financial_policy_kv_bordereau_total) }}</td>


                @if(in_array($analiticsVisibility, [0,1,3]))

                    <td>{{$payment->financial_policy_kv_parent}}</td>
                    <td>{{getPriceFormat($payment->financial_policy_kv_parent_total)}}</td>

                    <td>{{$payment->agent->name}}</td>
                    <td>{{$payment->manager ? $payment->manager->name : "" }}</td>

                @endif

                @if(in_array($analiticsVisibility, [0,1]))



                    <td>{{$payment->financial_policy_kv_bordereau + $payment->financial_policy_kv_dvoy}}</td>
                    <td>{{getPriceFormat($payment->financial_policy_kv_bordereau_total + $payment->financial_policy_kv_dvoy_total)}}</td>

                    <td>{{($payment->getMarginAmount(0))}}</td>
                    <td>{{getPriceFormat($payment->getMarginAmount(1))}}</td>

                @endif

            </tr>
        @endforeach

        <tr>
            <th colspan="12"></th>
            <th>{{getPriceFormat($all_payment_total)}}</th>
            <th colspan="4"></th>
            <th>{{getPriceFormat($all_invoice_payment_total)}}</th>
            <th colspan="7"></th>
            {{--<th>{{getPriceFormat($all_kv_agent_total)}}</th>--}}
            <th>{{getPriceFormat($all_kv_borderau_total)}}</th>

            @if(in_array($analiticsVisibility, [0,1,3]))

                <th colspan="2"></th>
                <th>{{getPriceFormat($all_kv_parent_total)}}</th>

            @endif

            @if(in_array($analiticsVisibility, [0,1]))

                <th colspan="3"></th>
                <th>{{getPriceFormat($all_kv_total)}}</th>

                <th ></th>
                <th>{{getPriceFormat($all_margin_total)}}</th>

            @endif

        </tr>


    </tbody>
</table>
