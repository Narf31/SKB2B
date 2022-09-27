
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div id="tt" class="easyui-tabs" data-options="tools:'#tab-tools'">

        <div title="Основная информация" data-view="contracts.default.terms.liabilityArbitrationManager"></div>
        <div title="Процедуры" data-view="contracts.default.insurance_object.liabilityArbitrationManager"></div>
        <div title="Согласования" data-view="contracts.default.matching.liabilityArbitrationManager"></div>
        <!-- div title="Документы" data-view="contracts.default.documentation"></div -->
        <div title="История" data-view="contracts.default.history"></div>

    </div>
    <div class="block-main" >
        <div class="block-sub">
            <div class="form-horizontal">
                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main_container" >

                </div>
            </div>
        </div>
    </div>
</div>



@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>

    <script src="/js/online.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">


    {{ Html::script("https://js.pusher.com/5.0/pusher.min.js") }}

    @include('contracts.product.liabilityArbitrationManager.js')
@stop

