<br/>


<div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">

    @include('orders.default.documents', [
            'view' => $view,
            'order' => $order,
            'url_scan' => url("/orders/actions/{$order->id}/scan_damages"),
    ])

</div>

<div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">
    @include('orders.default.comment_pso', [
            'view' => $view,
            'order' => $order,
            'url_scan' => url("/orders/actions/{$order->id}/comment_pso"),
    ])

</div>


<script>

    function startMainFunctions() {
        initDocuments();
    }




</script>

