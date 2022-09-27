@if($view == 'edit')

<div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="col-lg-4">
            <div class="field form-col">
                <label class="control-label">Статус</label>
                {{ Form::select('order[status_payments_id]', \App\Models\Orders\DamageOrder::STATUS_PAYMENT, ($info)?$info->status_payments_id:0, ['class' => 'form-control select2-ws', 'id'=>'status_payments_id']) }}
            </div>
        </div>

    </div>


    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="col-lg-12">
            <div class="field form-col">
                <label class="control-label">Комментарий</label>
                {{ Form::textarea('order[payments_comments]', ($info)?$info->payments_comments:'', ['class' => 'form-control ', 'id'=>'payments_comments']) }}
            </div>
        </div>

    </div>


    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <span class="btn btn-success pull-left" onclick="saveStatusPayment()">
            Сохранить
        </span>

        <script>

            function saveStatusPayment() {
                loaderShow();

                $.post("{{url("/orders/damages/{$damage->id}/save-status-payment")}}", {status_payments_id:$("#status_payments_id").val(), payments_comments:$("#payments_comments").val()}, function (response) {


                    if (Boolean(response.state) === true) {

                        flashMessage('success', "Данные успешно сохранены!");

                    }else {
                        flashHeaderMessage(response.msg, 'danger');

                    }

                }).always(function () {
                    loaderHide();
                });
            }

        </script>

    </div>


</div>

<div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">


    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row form-horizontal">
            <h2 class="inline-h1">Платежи
                <span class="btn btn-primary pull-right" onclick="openFancyBoxFrame('{{url("/orders/damages/{$damage->id}/payment/0")}}')">Добавить</span>
            </h2>
            <br><br>



            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Дата оплаты</th>
                        <th>Сумма платежа</th>
                        <th>Комментарий</th>
                    </tr>
                </thead>
                <tbody>
                @if(sizeof($damage->payments))
                    @foreach($damage->payments as $key => $payment)
                    <tr onclick="openFancyBoxFrame('{{url("/orders/damages/{$damage->id}/payment/{$payment->id}")}}')" style="cursor: pointer;">
                        <td>{{$key+1}}</td>
                        <td>{{setDateTimeFormatRu($payment->payment_data, 1)}}</td>
                        <td>{{titleFloatFormat($payment->payment_total)}}</td>
                        <td>
                            {{$payment->comments}}
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Итого</th>
                        <th>{{titleFloatFormat($damage->payments->sum('payment_total'))}}</th>
                        <th></th>
                    </tr>
                @endif
                </tbody>

            </table>

        </div>


    </div>



</div>


@else



    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">


        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row form-horizontal">
                <h2 class="inline-h1">Платежи
                </h2>
                <br><br>
                <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <div class="view-field">
                        <span class="view-label">Статус</span>
                        <span class="view-value">{{\App\Models\Orders\DamageOrder::STATUS_PAYMENT[($info)?$info->status_payments_id:0]}}</span>
                    </div>
                </div>


                <table class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Дата оплаты</th>
                        <th>Сумма платежа</th>
                        <th>Комментарий</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(sizeof($damage->payments))
                        @foreach($damage->payments as $key => $payment)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{setDateTimeFormatRu($payment->payment_data, 1)}}</td>
                                <td>{{titleFloatFormat($payment->payment_total)}}</td>
                                <td>
                                    {{$payment->comments}}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="2">Итого</th>
                            <th>{{titleFloatFormat($damage->payments->sum('payment_total'))}}</th>
                            <th></th>
                        </tr>
                    @endif
                    </tbody>

                </table>

            </div>
        </div>



    </div>

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="row form-horizontal">
                <h2 class="inline-h1">Комментарий
                </h2>
                <br><br>
                <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <span style="font-size: 16px;">{{($info)?$info->payments_comments:''}}</span>
                </div>

            </div>
        </div>


    </div>



@endif