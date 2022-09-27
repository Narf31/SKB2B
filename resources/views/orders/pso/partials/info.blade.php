<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">



    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        @include("orders.pso.partials.order_view", ['order' => $order])
    </div>

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        @include("orders.pso.partials.button_work", ['order' => $order])
    </div>

</div>


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        {{--Информация по договору--}}
        @include('contracts.default.info.view_contract', [
            'contract' => $order->contract,
            'is_link' => 1,
        ])

        <br/>
    </div>

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        {{--Страхователь--}}
        @include('contracts.default.subject.view', [
            'subject_title' => 'Страхователь',
            'subject_name' => 'insurer',
            'subject' => $order->contract->insurer
        ])
    </div>

    @if(View::exists("orders.pso.partials.product.{$order->contract->product->slug}"))

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

        @include("orders.pso.partials.product.{$order->contract->product->slug}", [
            'contract' => $order->contract
        ])
    </div>
    @endif
</div>

<script>

    function startMainFunctions()
    {
        initActivForms();
    }

</script>