@extends('layouts.app')


@section('content')

    <div class="page-heading">
        <h2>Счёт {{ $invoice->id }}
            <span class="btn btn-info btn-right" onclick="openLogEvents('{{$invoice->id}}', 2, 0)"><i class="fa fa-history"></i></span>
            <a href="/finance/invoice/invoices/{{$invoice->id}}/direction" target="_blank" id="direction" type="submit" class="btn btn-success btn-right">Направление</a>
            <a href="/finance/invoice/invoices/{{$invoice->id}}/act_export" id="act_export_xls" type="submit" class="btn btn-success btn-right doc_export_btn">Акт сдачи в кассу</a>
        </h2>
        <br/>
    </div>

    <div class="divider"></div>
    <br/>

    <style>
        table.table tr{line-height: 30px;}
        .btn-sm{cursor: pointer;}
    </style>

    {{ Form::model($invoice, ['url' => url("/finance/invoice/invoices/{$invoice->id}/save"), 'method' => 'post', 'class' => 'form-horizontal', 'files' => true]) }}

    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="block-view">
                <div class="block-sub">
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Тип</label>
                        <div class="col-sm-8">
                            @php($type_select = collect(\App\Models\Finance\Invoice::TYPES))
                            {{ Form::select('type', $type_select, $invoice->type, ['class' => 'form-control select2 select2-all', 'id'=>'type', 'required']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Юр лицо</label>
                        <div class="col-sm-8">
                            @php($type_select = collect(\App\Models\Organizations\Organization::whereIn('org_type_id',[1,2])->where('is_actual', '=', 1)->get()->pluck('title', 'id')))
                            {{ Form::select('org_id', $type_select, $invoice->org->id, ['class' => 'form-control select2 select2-all', 'id'=>'org_id', 'required']) }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <div class="block-view">
                <div class="block-sub">
                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="view-field">
                                <span class="view-label">К оплате</span>
                                <span class="view-value">{{ titleFloatFormat($invoice_info->total_sum) }}</span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="view-field">
                                <span class="view-label">Тип платежа</span>
                                <span class="view-value">{{ $invoice->types_ru('type') }}</span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="view-field">
                                <span class="view-label">Дата выставления</span>
                                <span class="view-value">{{ setDateTimeFormatRu($invoice->created_at) }}</span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="view-field">
                                <span class="view-label">Организация</span>
                                <span class="view-value">{{ $invoice->org ? $invoice->org->title : "" }}</span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="view-field">
                                <span class="view-label">Агент</span>
                                <span class="view-value">{{$invoice->agent?$invoice->agent->name:''}}</span>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <div class="view-field">
                                <span class="view-label">Удержание КВ</span>
                                <span class="view-value">{{ titleFloatFormat($invoice_info->total_kv_agent) }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>



        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Номер</th>
                    <th>Номер договора</th>
                    <th>Квитанция</th>
                    <th>Юр. лицо</th>
                    <th>СК</th>
                    <th>Продукт</th>
                    <th>Тип</th>
                    <th>Сумма</th>
                    <th>КВ %</th>
                    <th>КВ руб</th>
                    <th>К оплате</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php($all_total = 0)
                @if(sizeof($invoice->payments))
                    @foreach($invoice->payments as $payment)

                        @php($agent_total = $payment->getPaymentAgentSum())
                        @php($all_total += $agent_total)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $payment->bso ? $payment->bso->bso_title : "" }}</td>
                            <td>{{ $payment->bso_receipt ? : "" }}</td>
                            <td>{{ $invoice->org ? \Illuminate\Support\Str::limit($invoice->org->title,15) : "" }}</td>
                            <td>{{ $payment->bso && $payment->bso->insurance ? $payment->bso->insurance->title : "" }}</td>
                            <td>{{ $payment->bso && $payment->bso->product ? $payment->bso->product->title : ""}}</td>
                            <td>{{ $payment->type_ru() }}</td>
                            <td>{{ getPriceFormat($payment->payment_total) }}</td>

                            <td>{{ $payment->financial_policy_kv_bordereau }}</td>
                            <td>{{ $payment->financial_policy_kv_bordereau_total }}</td>

                            <td>{{ getPriceFormat($agent_total) }}</td>
                            <td><span class="btn-sm btn-danger" data-delete_payment="{{ $payment->id }}">Удалить</span></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="10" class="text-right">Итого</td>
                        <td><b>{{getPriceFormat($all_total)}}</b></td>
                        <td></td>
                    </tr>
                @endif
            </tbody>
        </table>

    <button id="delete_invoice" type="button" class="btn btn-danger pull-left">Расформировать счёт</button>
    <button type="submit" class="btn btn-primary btn-right">{{ trans('form.buttons.save') }}</button>


    {{Form::close()}}

@stop






@section('js')
    <script>
        $(function(){

            $(document).on('click', '[data-delete_payment]', function(){
                var payment_id = $(this).data('delete_payment');
                if(confirm('Исключить платёж из счёта?')){
                    $.post('/finance/invoice/invoices/{{$invoice->id}}/delete_payments/', {payment:[payment_id]}, function(res){
                        if(res.type === 'invoice'){
                            location.href = '/finance/invoice'
                        }else if(res.type === 'payment'){
                            location.reload();
                        }
                    });
                }

            });

            $(document).on('click', '#delete_invoice', function(){
                if(confirm('Расформировать счёт?')) {
                    $.post('/finance/invoice/invoices/{{$invoice->id}}/delete_invoice', {}, function (res) {
                        location.href = '/finance/invoice'
                    });
                }
            });


            // $(document).on('click', '#direction', function(){
            //
            // })

        })
    </script>

@endsection


