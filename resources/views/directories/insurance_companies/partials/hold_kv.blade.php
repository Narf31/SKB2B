<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <div class="page-subheading">
        <h2 class="inline-h1">Настройка продуктов, документов, интеграций</h2>
        <a href="/directories/insurance_companies/{{$insurance_companies->id}}/bso_suppliers/{{$bso_supplier->id}}/hold_kv/create"
           class="btn btn-primary pull-right">
            {{ trans('form.buttons.add') }}
        </a>
    </div>
    <div class="block-main">
        <div class="block-sub">
            @if($bso_supplier->hold_kv)
                <table class="tov-table" >
                    <tbody>
                        <tr>
                            <th style="width: 90%;">Продукт</th>
                            <th></th>
                        </tr>
                        @if(sizeof($bso_supplier->hold_kv))
                            @foreach($bso_supplier->hold_kv as $hold_kv)
                                <tr class="clickable-row">
                                    <td style="width: 90%;" onclick="openPage('/directories/insurance_companies/{{$insurance_companies->id}}/bso_suppliers/{{$bso_supplier->id}}/hold_kv/{{$hold_kv->id}}/edit')">
                                        {{ $hold_kv->product->title  }}
                                    </td>
                                    <td>
                                        <button class="btn btn-danger btn-right" onclick="deleteItem('{{url("/directories/insurance_companies/$insurance_companies->id/bso_suppliers/$bso_supplier->id/hold_kv/")}}/', '{{ $hold_kv->id }}')">{{ trans('form.buttons.delete') }}</button>
                                    </td>

                                </tr>
                            @endforeach
                        @endif
                    </tbody>

                </table>
            @else
                {{ trans('form.empty') }}
            @endif
        </div>
    </div>
</div>