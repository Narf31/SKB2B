<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="page-subheading">
        <h2 class="inline-h1">Финансовые политики</h2>
        <a href="{{url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}/financial_policy/0/")}}" class="btn btn-primary btn-right">
            {{ trans('form.buttons.add') }}
        </a>
    </div>
    <div class="block-main">
        <div class="block-sub">
            @if($bso_supplier->financial_policy)
                <table class="tov-table" >
                    <tbody>
                    <tr>
                        <th>Продукт</th>
                        <th>Дата действия</th>
                        <th>Название</th>
                        <th>КВ Бордеро</th>
                        <th>КВ Двоу</th>
                        <th>КВ СК</th>
                        <th>КВ Руководителя</th>
                        <th>Актуально</th>
                    </tr>
                    @if(sizeof($bso_supplier->financial_policy))
                        @foreach($bso_supplier->financial_policy as $finPolicy)
                            <tr class="clickable-row" data-href="{{url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}/financial_policy/{$finPolicy->id}/")}}">
                                <td>{{ $finPolicy->product ? $finPolicy->product->title : "Все"  }}</td>
                                <td>{{ setDateTimeFormatRu($finPolicy->date_active, 1) }}</td>
                                <td>{{ $finPolicy->title }}</td>
                                <td>{{ titleFloatFormat($finPolicy->kv_bordereau) }}</td>
                                <td>{{ titleFloatFormat($finPolicy->kv_dvou) }}</td>
                                <td>{{ titleFloatFormat($finPolicy->kv_sk) }}</td>
                                <td>{{ titleFloatFormat($finPolicy->kv_parent) }}</td>
                                <td>{{($finPolicy->is_actual==1)? trans('form.yes') :trans('form.no')}}</td>
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