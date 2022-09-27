<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="page-subheading">
            <h2>События безопасности: {{$events->type_title($events->types)}}</h2>
        </div>

        @include('orders.label_rfid.road', ['order' => $events->order])

    </div>
</div>