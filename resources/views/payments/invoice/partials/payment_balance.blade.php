<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <label class="control-label">Баланс</label>
    <div>
        {{ Form::select('invoice_payment_balance', collect($invoice->agent->getBalanceList())->pluck('title', 'id'), session()->get('invoice.invoice_payment_balance')?:0, ['class' => 'form-control', 'id'=>'invoice_payment_balance']) }}
    </div>
</div>
