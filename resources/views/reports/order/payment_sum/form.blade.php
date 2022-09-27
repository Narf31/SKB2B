<div class="form-group">
    <label class="col-sm-4 control-label">Тип</label>
    <div class="col-sm-8">
        {{ Form::select('type_id', \App\Models\Reports\ReportPaymentSum::TYPES ,$payment_sum->type_id, ['class' => 'form-control ', 'required']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Сумма</label>
    <div class="col-sm-8">
        {{ Form::text('amount', ($payment_sum->amount>0)?titleFloatFormat($payment_sum->amount):'', ['class' => 'form-control sum', 'required']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-12 control-label">Комментарий</label>
    <div class="col-sm-12">
        {{ Form::textarea('comments', $payment_sum->comments, ['class' => 'form-control', '']) }}
    </div>
</div>
