@extends('layouts.app')


@section('content')


    @include("payments.invoice.head", ["invoice" => $invoice])

    {{ Form::model($invoice, ['url' => url("/cashbox/invoice/{$invoice->id}/save"), 'method' => 'post', 'class' => 'form-horizontal', 'files' => true]) }}


    @include("payments.invoice.body", ["invoice" => $invoice])

    {{Form::close()}}

    @include("cashbox.invoice.payment_info", ["invoice" => $invoice])

    <div id="action_table" style="display: none">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3 pull-right">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Операции</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <a href="#" class="btn btn-danger btn-right" style="max-width: 140px" id="remove_from_invoice">Исключить</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@stop



@section('js')
    <script>


        $(function(){

            initInvoce();


            $(document).on('change', '[name="payment[]"]', function(){
                var uncheckeds = $('[name="payment[]"]').length - $('[name="payment[]"]:checked').length;
                $('[name="all_payments"]').prop('checked', uncheckeds === 0);
                showActions();
            });

            $(document).on('change', '[name="all_payments"]', function(){
                var checked = $(this).prop('checked');
                $('[name="payment[]"]').prop('checked', checked);
                showActions();
            });

            $(document).on('click', '#remove_from_invoice', function(){


                if(confirm('Исключить платёж из счёта?')){
                    $.post('/finance/invoice/invoices/{{$invoice->id}}/delete_payments/', getActionData(), function(res){
                        if(res.type === 'invoice'){
                            location.href = '/cashbox/invoice'
                        }else if(res.type === 'payment'){
                            location.reload();
                        }
                    });
                }


            });

        });


        function getActionData(){
            var data = {
                payment: []
            };

            $.each($('[name="payment[]"]:checked'), function(k,v){
                data.payment.push($(v).val())
            });
            return data;

        }


        function showActions(){
            if($('[name="payment[]"]:checked').length > 0){
                $('#action_table').show();
            }else{
                $('#action_table').hide();
            }
        }



    </script>

@endsection


