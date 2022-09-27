

    <div class="divider"></div>


    <table class="tov-table">
        <tr class="sort-row">
            <th>Тип</th>
            <th>Счёт</th>
            <th>Дата договора</th>
            <th>Дата оплаты</th>
            <th>Приход в кассу</th>
            <th>Тип оплаты</th>
            <th>Поток оплаты</th>
            <th>Квитанция А7</th>
            <th>Сумма взноcа</th>
            <th>КВ, %</th>
            <th>КВ агента</th>
            <th>Сумма в кассу</th>
            <th>Отчёт в СК</th>
            <th>Отчёт ДВОУ</th>
            <th></th>
        </tr>
        <tbody>


        @if(sizeof($payments))

            @foreach($payments as $payment)

                <tr class="clickable-row" style="background-color: {{$payment->getPaymentsColor()}}">
                    <td>{{\App\Models\Contracts\Payments::TRANSACTION_TYPE[$payment->type_id]}} @if($payment->type_id == 0) {{$payment->payment_number}} @endif</td>
                    <td><a href="{{url("/finance/invoice/{$payment->invoice_id}/view")}}" target="_blank"> {{$payment->invoice_id>0?$payment->invoice_id:''}} </a></td>
                    <td>{{setDateTimeFormatRu($payment->contract->sign_date, 1)}}</td>
                    <td>{{setDateTimeFormatRu($payment->payment_data, 1)}}</td>
                    <td>{{setDateTimeFormatRu($payment->invoice_payment_date, 1)}}</td>
                    <td>{{\App\Models\Contracts\Payments::PAYMENT_TYPE[$payment->payment_type]}}</td>
                    <td>{{\App\Models\Contracts\Payments::PAYMENT_FLOW[$payment->payment_flow]}}</td>
                    <td>@if($payment->bso_receipt_id > 0) <a href="{{url("/bso/items/{$payment->bso_receipt_id}/")}}" target="_blank">{{$payment->bso_receipt}}</a> @endif</td>
                    <td>{{titleFloatFormat($payment->payment_total)}}</td>

                    <td>{{titleFloatFormat($payment->getPaymentAgentKVProc())}}</td>
                    <td>{{titleFloatFormat($payment->getPaymentAgentKV())}}</td>
                    <td>{{titleFloatFormat($payment->getPaymentAgentSum())}}</td>

                    <td>
                        @if($payment->reports_order_id > 0)
                            <a href="{{url("/reports/order/{$payment->reports_order_id}/")}}" target="_blank">{{$payment->reports_border->title}}</a>
                        @else
                            ---
                        @endif
                    </td>
                    <td>
                        @if($payment->reports_dvou_id > 0)
                            <a href="{{url("/reports/order/{$payment->reports_dvou_id}/")}}" target="_blank">{{$payment->reports_dvoy->title}}</a>
                        @else
                            ---
                        @endif
                    </td>
                    <td>
                        <span class="btn btn-primary" onclick="openFancyBoxFrame('{{url("/payment/{$payment->id}/")}}')">Открыть</span>
                    </td>
                </tr>

            @endforeach
        @endif


        </tbody>
    </table>


    <div class="divider"></div>

    <br/>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


        <div class="form-horizontal">
            <div class="form-group">


                <div class="col-sm-3">
                    <div class="col-sm-8">
                        {{Form::select('transaction_type_id', collect(\App\Models\Contracts\Payments::TRANSACTION_TYPE), 0,  ['class' => 'form-control', 'id'=>'transaction_type_id'])}}
                    </div>
                    <div class="col-sm-4">
                        <span class="btn btn-success pull-right" onclick="addTransaction()">Добавить</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>


        function addTransaction() {

            openFancyBoxFrame('{{url("/add_payment/{$bso->id}/")}}/?transaction_type_id='+$("#transaction_type_id").val());

        }


    </script>