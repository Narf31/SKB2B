

@if(isset($request->program) && $request->program >= 0)

    @include('directories.products.special_settings.tariff.prf.value', [
    'url'=>$url,
    'json'=>$json
    ])

@else
    @include('directories.products.special_settings.tariff.prf.table', [
    'url'=>$url,
    'json'=>$json
    ])
@endif