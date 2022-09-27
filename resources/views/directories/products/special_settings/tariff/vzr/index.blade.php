

@if(isset($request->program) && $request->program >= 0)

    @include('directories.products.special_settings.tariff.vzr.value', [
    'url'=>$url,
    'json'=>$json
    ])

@else
    @include('directories.products.special_settings.tariff.vzr.table', [
    'url'=>$url,
    'json'=>$json
    ])
@endif