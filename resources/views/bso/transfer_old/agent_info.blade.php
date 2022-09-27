





<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="block-main">
        <div class="block-sub">

            <div class="row form-horizontal">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="view-field">
                        <span class="view-label">Агентский договор</span>
                        <span class="view-value">{{ $agent->agent_contract_title }} действует {{ setDateTimeFormatRu($agent->agent_contract_begin_date, 1) }} - {{ setDateTimeFormatRu($agent->agent_contract_begin_date, 1) }}</span>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="view-field">
                        <span class="view-label">Куратор</span>
                        <span class="view-value">{{ $agent->curator?$agent->curator->name:'' }}</span>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="view-field">
                        <span class="view-label">Выдача БСО</span>
                        <span class="view-value">{{ collect([-1 => 'Отсутствует', 0=>'По умолчанию', 1=>'Частичная выдача', 2=>'Запрет'])[$agent->ban_level] }}</span>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="view-field">
                        <span class="view-label">Примечания</span>
                        <span class="view-value">{{ $agent->ban_reason }}</span>
                    </div>
                </div>

                <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label class="col-sm-12 control-label">Продукты</label>
                    <div class="col-sm-12">
                        {!! $agent->getProductsSale(1) !!}
                    </div>
                </div>
            </div>



            <input type="hidden" value="0" id="limit_ban">
            <table class="bso_table">
                <tbody><tr>
                    <td rowspan="2">БСО На руках</td>
                    <td rowspan="2">Из них просроченных</td>
                    <td colspan="3" class="center">Фин. долги</td>
                </tr>
                <tr>
                    <td class="center">Нал</td>
                    <td class="center">Безнал</td>
                    <td class="center">Всего</td>
                </tr>
                <tr>
                    <td class="right"><a target="_blank" href="{{url("/bso/inventory_agents/details/?agent_id={$agent->id}&point_sale_id=-1&type_bso_id=-1&nop_id=-1&types=bso_in")}}">{{$agent->getUserLimitBSOToProduct(0, 1)}}</a></td>
                    <td class="right"><a target="_blank" href="{{url("/bso/inventory_agents/details/?agent_id={$agent->id}&point_sale_id=-1&type_bso_id=-1&nop_id=-1&types=bso_in")}}">{{$agent->getUserLimitBSOToProduct(0, 1, 30)}}</a></td>

                    <td class="right"><a target="_blank" href="{{url("/finance/debts/{$agent->id}/detail")}}">{{ isset($agent_summary['cash']) ? getPriceFormat($agent_summary['cash']) : "0,00" }}</a></td>
                    <td class="right"><a target="_blank" href="{{url("/finance/debts/{$agent->id}/detail")}}">{{ isset($agent_summary['sk']) ? getPriceFormat($agent_summary['sk']) : "0,00" }}</a></td>
                    <td class="right"><a target="_blank" href="{{url("/finance/debts/{$agent->id}/detail")}}">{{ isset($agent_summary['all']) ? getPriceFormat($agent_summary['all']) : "0,00" }}</a></td>

                </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>


<style>


    .bso_table {
        font: 12px arial;
        border: 1px solid #777;
        border-collapse: collapse;
    }
    .bso_table td, th {
        border: 1px solid #777;
        padding: 5px;
        font: 12px arial;
    }

    .bso_table th {
        background-color: #EEE;
    }


    .center {
        text-align: center !important;
    }

    .right {
        text-align: right !important;
    }

    .gray {
        background-color: #EEE !important;
    }




</style>




