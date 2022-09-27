<div class="row form-horizontal">
    <h2 class="inline-h1">Платежи</h2>
    <br/><br/>



    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>Дата оплаты</th>
            <th>Статус</th>
            <th>Метод оплаты</th>
            <th>Алгоритм рассрочки</th>
            <th>Взнос</th>
            <th>Скидка</th>
            <th>К оплате</th>
        </tr>
        </thead>
        <tbody>
        @php
            $status_button = true;
            $next_button = 0;
        @endphp
        @if(sizeof($payments))
            @foreach($payments as $payment)
                @php
                    if($payment->invoice) $status_button = false;
                    if($payment->statys_id == 0 && $next_button == 0) $next_button = $payment->id;
                @endphp
                <tr @if($payment->is_deleted == 1) class="bg-red" @elseif($payment->statys_id == 1) class="bg-green" @elseif($contract->statys_id != 2 && $payment->invoice) style="cursor: pointer;" onclick="openFancyBoxFrame('{{url("/contracts/online/{$contract->id}/payment/{$payment->id}/")}}')" @endif>
                    <td>{{$payment->payment_number}}</td>
                    <td>{{setDateTimeFormatRu($payment->payment_data, 1)}}</td>
                    <td>{{\App\Models\Contracts\Payments::STATUS[$payment->statys_id]}}</td>

                    <td>

                        @if($payment->bso_receipt_id > 0)
                            {{$payment->receipt->bso_title}}
                        @else
                            {{($payment->payment_method)?$payment->payment_method->title:''}}
                        @endif
                    </td>

                    <td>{{titleFloatFormat($payment->installment_algorithms_payment)}}%</td>
                    <td>{{titleFloatFormat($payment->payment_total)}}</td>
                    <td>{{titleFloatFormat($payment->official_discount_total)}}</td>
                    <td>{{titleFloatFormat($payment->invoice_payment_total)}}</td>
                </tr>
            @endforeach
                <tr>
                    <td colspan="5"></td>
                    <td>{{titleFloatFormat($payments->sum('payment_total'))}}</td>
                    <td>{{titleFloatFormat($payments->sum('official_discount_total'))}}</td>
                    <td>{{titleFloatFormat($payments->sum('invoice_payment_total'))}}</td>
                </tr>
        @endif
        </tbody>
    </table>

    @if($contract->statys_id >= 0 && $contract->statys_id < 4)
        <span class="btn btn-info btn-left" onclick="openFancyBoxFrame('{{url("/contracts/online/{$contract->id}/action/print")}}')">Печать</span>
    @endif

    @if($contract->statys_id == 3 && $status_button == true)
        <span class="btn btn-success btn-right" onclick="releaseContract({{$contract->id}})">Выпустить</span>
    @endif

    @if($contract->statys_id == 4 && $next_button > 0)
        <span class="btn btn-success btn-left" onclick="openFancyBoxFrame('{{url("/contracts/online/{$contract->id}/payment/{$next_button}")}}')">Оплатить очередной взнос</span>
    @endif

</div>


