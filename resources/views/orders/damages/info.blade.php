@if(sizeof($damages))

    @foreach($damages as $damage)
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                @include("orders.damages.partials.damages_view", ['damage' => $damage])
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="row form-horizontal">
                    <h2 class="inline-h1">
                        <span>Документы по убытку #{{$damage->id}}</span>
                    </h2>
                    <br/>
                    @include("orders.default.documents",
                    [
                        'order' => $damage,
                        'view' => 'view',
                        'url_scan' => '',
                        'url_rel' => '',
                    ])
                </div>
            </div>
        </div>

    @endforeach

@endif