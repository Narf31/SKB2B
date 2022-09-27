<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="view-field">
        <span class="view-label">Вид оплаты</span>
        <span class="view-value">{{ \App\Models\Finance\Invoice::TYPE_INVOICE_PAYMENT[$invoice->type_invoice_payment_id] }}</span>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="view-field">
        <span class="view-label">Сумма оплаты</span>
        <span class="view-value">{{ titleFloatFormat($invoice->invoice_payment_total) }}</span>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="view-field">
        <span class="view-label">Дата время</span>
        <span class="view-value">{{ setDateTimeFormatRu($invoice->invoice_payment_date) }}</span>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="view-field">
        <span class="view-label">Сотрудник</span>
        <span class="view-value">{{ ($invoice->invoice_payment_user)?$invoice->invoice_payment_user->name:''  }}</span>
    </div>
</div>


@if($invoice->type_invoice_payment_id == 2)

    {{--
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Баланс</span>
            <span class="view-value">{{ $invoice->agent->getBalance($invoice->invoice_payment_balance_id)->type_balanse->title }}</span>
        </div>
    </div>

--}}

@elseif($invoice->type_invoice_payment_id == 3 || $invoice->type_invoice_payment_id == 4)

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Подтверждения платежа</span>
            <span class="view-value">

                @if($invoice->file_id > 0)
                    <a href="{{ url($invoice->doc->url) }}" target="_blank">{{$invoice->doc->original_name}}</a>
                @endif

            </span>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">Комментарий</span>
            <span class="view-value">
            {{$invoice->invoice_payment_com}}
            </span>
        </div>
    </div>

@endif

<script>


    function initPayment() {


    }




</script>