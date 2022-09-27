<br/>
<div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">

    @include('orders.default.chat', [
       'view' => $view,
       'order' => $damage,
   ])

</div>

<div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">

    @include('orders.default.documents', [
            'view' => $view,
            'order' => $damage,
            'url_scan' => url("/orders/actions/{$damage->id}/scan_damages"),
    ])

</div>



<script>

    function startMainFunctions() {
        initDocuments();

        var messagesContainer = $('.messages');
        messagesContainer.scrollTop(messagesContainer[0].scrollHeight);

    }




</script>

