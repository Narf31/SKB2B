<table class="table table-bordered text-left payments_table huck">
    <thead>
    <tr>
        <th>Номер договора</th>
        <th>Статус</th>
        <th>Дата заключения</th>
        <th>Период действия</th>

        <th>Продукт</th>
        <th>Страхователь</th>
        <th>Взнос</th>

        <th>Статус оплаты</th>
        <th>Дата оплаты</th>

        <th>Метод оплаты</th>
        <th>Сумма платежа</th>

        <th>Агент</th>
        <th>Агент - Организация</th>
    </tr>
    </thead>
    <tbody>


    @foreach($payments as $key => $payment)

        <tr style="cursor: pointer;" class="{{getContractStatusColor($payment->contract)}}" @if($is_xls == 0) onclick="openPageBlank('{{url("/contracts/online/{$payment->contract->id}")}}')" @endif>
            <td>{{($payment->bso)?$payment->bso->bso_title:''}}</td>
                <td>{{$payment->contract->getContractsStatusTitle()}}
                    @if($payment->contract->statys_id == 2)
                        @if($payment->contract->calculation && $payment->contract->calculation->matching)
                            - {{\App\Models\Contracts\Matching::STATYS[$payment->contract->calculation->matching->status_id]}}
                        @endif
                    @endif
                </td>
            <td>
                {{setDateTimeFormatRu($payment->contract->sign_date)}}
            </td>
            <td>
                {{setDateTimeFormatRu($payment->contract->begin_date, 1)}} - {{setDateTimeFormatRu($payment->contract->end_date, 1)}}
            </td>



            <td>{{$payment->contract->product->title}}</td>
            <td>{{($payment->contract->insurer)?$payment->contract->insurer->title:''}}</td>
            <td>Взнос {{$payment->payment_number}}</td>
            <td>{{\App\Models\Contracts\Payments::STATUS[$payment->statys_id]}}</td>

            <td>
                {{setDateTimeFormatRu($payment->invoice_payment_date, 1)}}
            </td>

            <td>

                @if($payment->bso_receipt_id > 0)
                    {{$payment->receipt->bso_title}}
                @else
                    {{($payment->payment_method)?$payment->payment_method->title:''}}
                @endif
            </td>

            <td>{{(strlen($payment->invoice_payment_total) > 0) ? titleFloatFormat($payment->invoice_payment_total) : titleFloatFormat($payment->payment_total)}}</td>

            <td>{{($payment->agent)?$payment->agent->name:''}}</td>
            <td>{{($payment->agent)?$payment->agent->organization->title:''}}</td>

        </tr>

    @endforeach
    </tbody>
</table>
