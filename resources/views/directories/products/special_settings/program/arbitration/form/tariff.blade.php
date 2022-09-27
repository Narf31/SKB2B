@php

    $spec = \App\Models\Directories\Products\ProductsSpecialSsettings::where('product_id', $product->id)->where('program_id', $program->id)->get()->first();
    $info = null;
    if($spec && $spec->json && strlen($spec->json) > 0) $info = json_decode($spec->json, true);
@endphp

    <div class="page-heading product_form">
        <h2 class="inline-h1">Тарифы</h2>
    </div><br/>

    <div class="row form-horizontal col-xs-12 col-sm-12 col-md-12 col-lg-12">



        {{ Form::open(['url' => url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/arbitration/tariff/"), 'method' => 'post', "id" =>"product_form"]) }}



            @include('directories.products.special_settings.program.arbitration.form.tariff.value', [
                'program_slug'=> $program->slug,
                'json'=>(\App\Processes\Tariff\Settings\Product\TariffLiabilityArbitrationManager::defaultJson()),
                'json_data'=>(($info && isset($info['tariff']))?(array)$info['tariff']:\App\Processes\Tariff\Settings\Product\TariffLiabilityArbitrationManager::defaultJson())
            ])



        <div class="clear"></div>


        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
                <span class="btn btn-success pull-left" onclick="saveDefault()">Сохранить</span>
            </div>
        </div>


        {{ Form::close() }}


    </div>





<script>




    function initViewForm() {

    }


    function saveDefault() {

        loaderShow();


        $.post('{{url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/arbitration/tariff/")}}', $('#product_form').serialize(), function (response) {

            flashMessage('success', "Данные успешно сохранены!");

        }).always(function () {
            loaderHide();
        });


    }



</script>
