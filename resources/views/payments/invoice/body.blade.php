<div class="row">

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="block-view">
            <div class="block-sub">
                <div class="row">
                    @if($invoice->status_id == 1 && auth()->user()->hasPermission('cashbox', 'invoice'))

                        @include("payments.invoice.partials.set_payment", ["invoice" => $invoice])

                    @else

                        @include("payments.invoice.partials.get_payment", ["invoice" => $invoice])

                    @endif

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
                            <span class="view-value">{{ $invoice->payment_method->title }}</span>
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
                            <span class="view-label">Куратор</span>
                            <span class="view-value">{{$invoice->agent && $invoice->agent->curator?$invoice->agent->curator->name:''}}</span>
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
                            <span class="view-label">Агент организация</span>
                            <span class="view-value">{{$invoice->agent?$invoice->agent->organization->title:''}}</span>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="view-field">
                            <span class="view-label">Агентское КВ</span>
                            <span class="view-value">{{ titleFloatFormat($invoice_info->total_kv_agent) }}</span>
                        </div>
                    </div>



                </div>
            </div>
        </div>

    </div>
</div>



<script>


    function initInvoce() {

        initPayment();

    }




</script>