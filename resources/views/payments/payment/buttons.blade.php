@if($payment->reports_order_id <= 0 && $payment->reports_dvou_id <=0)


    @if((int)$payment->id > 0 && (int)$payment->is_deleted == 0 && ((int)$payment->statys_id == 0 && auth()->user()->hasPermission('reports', 'payment_delete') || auth()->user()->hasPermission('reports', 'payment_pay_delete')))
        <button class="btn btn-danger pull-left" onclick="deletePaymentId({{$payment->id}})">{{ trans('form.buttons.delete') }}</button>
    @endif



    @if(((int)$payment->statys_id == 0 && auth()->user()->hasPermission('reports', 'payment_edit')) || auth()->user()->hasPermission('reports', 'payment_pay_edit'))

        <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>
    @endif


    <script>



        function deletePaymentId(id) {

            res = myGetAjax("{{url("/payment/")}}/"+id+"/delete/");

            if(parseInt(res) == 1){
                window.parent.location.reload();
            }else{
                alert(res);
            }
        }


    </script>

@endif