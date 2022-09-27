
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        @include("orders.pso.partials.order_view", ['order' => $order])
    </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row form-horizontal">
            <h2 class="inline-h1">
                <span>Документы по осмотру #{{$order->id}}</span>
            </h2>
            <br/>
            @include("orders.default.documents",
            [
                'order' => $order,
                'view' => 'view',
                'url_scan' => '',
                'url_rel' => '',
            ])
        </div>
    </div>
</div>

