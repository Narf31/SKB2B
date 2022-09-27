<table class="table table-bordered text-left payments_table huck">
    <thead>
    <tr>

        @foreach($user_columns as $column)
            <th>{{ $column['column_name'] }}</th>
        @endforeach

    </tr>
    </thead>
    <tbody>
    <tr>
        @foreach($user_columns as $column)

            @if((int)$column['is_summary'] == 1)
                <td>{{getSqlSumRow($payments_sql, $column['column_key'], $is_xls)}}</td>
            @else
                <td></td>
            @endif
        @endforeach
    </tr>

    @foreach($payments as $key => $payment)


        <tr>
            @foreach($user_columns as $column)

                <td @if($payment->is_deleted == 1) style="background-color: #ffcccc" @endif>
                    @if($column['column_name'] == '№')
                        {{ $key+1 }}
                    @else


                        @if(!is_array($column['_key']) && isset($payment[$column['_key']]) )

                            @if($column['_key'] == 'payments_payment_flow')
                                {{ \App\Models\Contracts\Payments::PAYMENT_FLOW[$payment[$column['_key']]] }}
                            @elseif($column['_key'] == 'contracts_kind_accepance')
                                {{ \App\Models\Contracts\Contracts::KIND_ACCEPTANCE[$payment[$column['_key']]] }}
                            @elseif($column['_key'] == 'date_begin_date' || $column['_key'] == 'date_end_date' || $column['_key'] == 'date_contract' || $column['_key'] == 'payments_payment_data' || $column['_key'] == 'payments_invoice_payment_date' || $column['_key'] == 'date_acceptance')
                                {{ $payment[$column['_key']] != null ? setDateTimeFormatRu($payment[$column['_key']], 1) : '' }}
                            @elseif($column['_key'] == 'date_sign_date')
                                {{ $payment[$column['_key']] != null ? setDateTimeFormatRu($payment[$column['_key']]) : '' }}
                            @elseif($column['_key'] == 'reports_orders_id')
                                {{$payment[$column['_key']]}}
                            @elseif($column['_key'] == 'contract_sale_condition')
                                {{ \App\Models\Contracts\Contracts::SALES_CONDITION[$payment[$column['_key']]] }}
                            @elseif($column['_key'] == 'payment_type')
                                {{ \App\Models\Contracts\Payments::PAYMENT_TYPE[$payment[$column['_key']]] }}
                            @elseif($column['_key'] == 'payment_type_id')
                                {{ \App\Models\Contracts\Payments::TRANSACTION_TYPE[$payment[$column['_key']]] }}
                            @elseif($column['_key'] == 'payment_status')
                                {{ \App\Models\Contracts\Payments::STATUS[$payment[$column['_key']]] }}
                            @elseif($column['_key'] == 'reports_orders_accept_status')
                                {{ \App\Models\Reports\ReportOrders::STATE[$payment[$column['_key']]] }}
                            @elseif($column['_key'] == 'contracts_type_id')
                                {{ \App\Models\Contracts\Contracts::TYPE[$payment[$column['_key']]] }}
                            @elseif($column['_key'] == 'bso_item_bso_title')

                                {{ $payment->bso_item_bso_title }}


                            @elseif($column['_key'] == 'margin_procent')
                                {{ $payment->margin_procent.'%' }}
                            @elseif($column['_key'] == 'official_discount')
                                {{ $payment->official_discount.'%' }}
                            @elseif($column['_key'] == 'broker_reward')
                                {{ $payment->broker_reward.'%' }}
                            @elseif($column['_key'] == 'payments_financial_policy_kv_bordereau')
                                {{ $payment->payments_financial_policy_kv_bordereau.'%' }}
                            @elseif($column['_key'] == 'payments_financial_policy_kv_dvoy')
                                {{ $payment->payments_financial_policy_kv_dvoy.'%' }}
                            @elseif($column['_key'] == 'payments_acquire_percent')
                                {{ $payment->payments_acquire_percent.'%' }}

                            @elseif($column['_key'] == 'receipt_number')
                                {{ $payment[$column['_key']] }}

                            @elseif($column['_key'] == 'official_discount_total' ||
                                    $column['_key'] == 'payment_informal_discount_total' ||
                                    $column['_key'] == 'payment_total' ||
                                    $column['_key'] == 'invoice_payment_total' ||
                                    $column['_key'] == 'payments_financial_policy_kv_bordereau_total' ||
                                    $column['_key'] == 'contracts_sum_sk' ||
                                    $column['_key'] == 'payment_informal_discount_total' ||
                                    $column['_key'] == 'payments_acquire_total' ||
                                    $column['_key'] == 'margin_sum' ||
                                    $column['_key'] == 'broker_reward_sum' ||
                                    $column['_key'] == 'payments_financial_policy_kv_agent_total')
                                {{ titleFloatFormat($payment[$column['_key']], $is_xls) }}
                            @else
                                {{ $payment[$column['_key']] }}
                            @endif


                        @endif
                    @endif
                </td>

            @endforeach
        </tr>

    @endforeach
    </tbody>
</table>
