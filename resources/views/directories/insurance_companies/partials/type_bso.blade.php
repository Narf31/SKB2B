<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <div class="page-subheading">
        <h2>Типы БСО</h2>
        <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url("/directories/insurance_companies/{$insurance_companies->id}/type_bso/0/")  }}')">
            {{ trans('form.buttons.add') }}
        </span>
    </div>
    <div class="block-main">
        <div class="block-sub">
            @if($insurance_companies->type_bso)
                <table class="tov-table" >
                    <tbody>

                    <tr>
                        <th>Продукт</th>
                        <th>Актуальность</th>
                        <th>Мин. желтый</th>
                        <th>Мин. красный</th>
                        <th>Дней у агента</th>
                    </tr>

                    @if(sizeof($insurance_companies->type_bso))
                        @foreach($insurance_companies->type_bso as $type_bso)
                            <tr class="clickable-row" onclick="openFancyBoxFrame('{{ url("/directories/insurance_companies/{$insurance_companies->id}/type_bso/{$type_bso->id}/") }}')">
                                <td>{{ $type_bso->title  }}</td>
                                <td>{{ ($type_bso->is_actual==1)? trans('form.yes') :trans('form.no')  }}</td>
                                <td>{{ ($type_bso->min_yellow == 0)?'':$type_bso->min_yellow }}</td>
                                <td>{{ ($type_bso->min_red == 0)?'':$type_bso->min_red }}</td>
                                <td>{{ ($type_bso->day_agent == 0)?'':$type_bso->day_agent }}</td>
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