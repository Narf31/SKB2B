<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <label class="control-label">Сумма</label>
    <div>
        {{ Form::text('invoice_payment_total', titleFloatFormat($invoice_info->total_sum), ['class' => 'form-control sum', 'id'=>'invoice_payment_total']) }}
    </div>
</div>
