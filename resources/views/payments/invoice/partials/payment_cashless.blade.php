<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <label class="col-sm-12">Подтверждения платежа</label>
    <div  class="col-sm-6">
        <input type="file" name="file"/>
    </div>
    @if(isset($invoice) && $invoice->file_id > 0)
        <div class="col-sm-6">
            <a href="{{ url($invoice->doc->url) }}" target="_blank">{{$invoice->doc->original_name}}</a>
        </div>
    @endif
</div>




<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <label class="control-label">Комментарий</label>
    <div>
        {{ Form::textarea('invoice_payment_comm', $invoice->invoice_payment_com, ['class' => 'form-control', 'id'=>'invoice_payment_comm']) }}
    </div>
</div>