<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <label class="control-label">Вид оплаты</label>
    <div>
        {{ Form::select('type_invoice_payment', collect(\App\Models\Finance\Invoice::TYPE_INVOICE_PAYMENT), session()->get('invoice.type_invoice_payment')?:$invoice->getInvoceDefaultPaymentType(), ['class' => 'form-control select2-all', 'id'=>'type_invoice_payment', 'required', 'onchange' => 'loadPaymentContent()']) }}
    </div>

</div>

<div id="invoice_payment">

</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div>
        <input type="checkbox" value="1" name="withdraw_documents" >
        <label class="control-label">Изъять документы</label>
    </div>
</div>


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <input type="submit" class="btn btn-primary btn-right" value="Подтвердить" onclick="loaderShow()"/>

</div>

<script>


    function initPayment() {

        loadPaymentContent();

    }

    function loadPaymentContent() {


        loaderShow();
        $.post("{{url("/cashbox/invoice/{$invoice->id}/data_invoice_payment")}}", {type_invoice_payment:$("#type_invoice_payment").val()}, function (response) {
            $('#invoice_payment').html(response);

            $('.sum')
                .change(function () {
                    $(this).val(CommaFormatted($(this).val()));
                })
                .blur(function () {
                    $(this).val(CommaFormatted($(this).val()));
                })
                .keyup(function () {
                    $(this).val(CommaFormatted($(this).val()));
                });

        }).fail(function(){
            $('#invoice_payment').html('');
        }).always(function() {
            loaderHide();
        });


    }


</script>