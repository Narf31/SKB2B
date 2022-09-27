

@if(isset($request->program) && $request->program >= 0)

    @include('directories.products.special_settings.tariff.liabilityArbitrationManager.value', [
    'url'=>$url,
    'json'=>$json
    ])

@else
    @include('directories.products.special_settings.tariff.liabilityArbitrationManager.table', [
    'url'=>$url,
    'json'=>$json
    ])
@endif