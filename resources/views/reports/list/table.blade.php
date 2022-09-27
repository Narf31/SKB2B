<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th rowspan="2">Дата</th>
            <th rowspan="2">Название</th>
            <th rowspan="2">Месяц</th>
            <th rowspan="2">Год</th>
            <th rowspan="2">Тип</th>
            <th rowspan="2">Статус</th>


            <th colspan="3">Комиссия</th>
            <th colspan="2">Сумма</th>
            <th colspan="4">Фактические данные</th>


            <th rowspan="2">Комментарий</th>
        </tr>

        <tr>
            <th>Бордеро</th>
            <th>ДВОУ</th>
            <th>Общая</th>

            <th style="background-color: #ccd9ff">Перечисление в СК</th>
            <th style="background-color: #fffae6">Возврат агенту</th>

            <th style="background-color: #ccd9ff">Оплачено агентом</th>
            <th style="background-color: #ccd9ff">К оплате</th>

            <th style="background-color: #fffae6">Перечислено агенту</th>
            <th style="background-color: #fffae6">Долг перед агентом</th>

        </tr>

    </thead>
    <tbody>

        @php($all_to_transfer_total = 0)
        @php($all_to_return_total = 0)

        @php($all_sk_report_payment_sums = 0)
        @php($all_sk_to_transfer_total = 0)

        @php($all_agent_report_payment_sums = 0)
        @php($all_agent_to_return_total = 0)

        @if(sizeof($reports))
            @foreach($reports as $report)

                @php($all_to_transfer_total += $report->to_transfer_total)
                @php($all_to_return_total += $report->to_return_total)

                @php($all_sk_report_payment_sums += $report->report_payment_sums->where('type_id', 1)->sum('amount'))
                @php($all_sk_to_transfer_total += $report->to_transfer_total - $report->report_payment_sums->where('type_id', 1)->sum('amount'))

                @php($all_agent_report_payment_sums += $report->report_payment_sums->where('type_id', 0)->sum('amount'))
                @php($all_agent_to_return_total += $report->to_return_total - $report->report_payment_sums->where('type_id', 0)->sum('amount'))

                <tr class="clickable-row @if($report->accept_status == 4) bg-green @endif" data-href="/reports/order/{{$report->id}}/" >
                    <td>{{setDateTimeFormatRu($report->created_at, 1)}}</td>
                    <td>{{$report->title}}</td>
                    <td>{{getRuMonthes()[$report->report_month]}}</td>
                    <td>{{$report->report_year}}</td>
                    <td>{{\App\Models\Reports\ReportOrders::TYPE[$report->type_id]}}</td>
                    <td>{{\App\Models\Reports\ReportOrders::STATE[$report->accept_status]}}</td>

                    <td>{{titleFloatFormat($report->bordereau_total)}}</td>
                    <td>{{titleFloatFormat($report->dvoy_total)}}</td>
                    <td>{{titleFloatFormat($report->amount_total)}}</td>

                    <td>{{titleFloatFormat($report->to_transfer_total)}}</td>
                    <td>{{titleFloatFormat($report->to_return_total)}}</td>

                    <td>{{titleFloatFormat($report->report_payment_sums->where('type_id', 1)->sum('amount'))}}</td>
                    <td>{{titleFloatFormat($report->to_transfer_total - $report->report_payment_sums->where('type_id', 1)->sum('amount'))}}</td>
                    <td>{{titleFloatFormat($report->report_payment_sums->where('type_id', 0)->sum('amount'))}}</td>
                    <td>{{titleFloatFormat($report->to_return_total - $report->report_payment_sums->where('type_id', 0)->sum('amount'))}}</td>

                    <td>{{$report->comments}}</td>
                </tr>
            @endforeach


        @else
            <tr class="clickable-row">
                <td colspan="100" class="text-center">Нет отчетов</td>
            </tr>
        @endif
    </tbody>
    @if(sizeof($reports))
        <tfoot>
            <tr>
                <th colspan="9"><b class="pull-right">Итого</b></th>

                <th>{{titleFloatFormat($all_to_transfer_total)}}</th>
                <th>{{titleFloatFormat($all_to_return_total)}}</th>

                <th>{{titleFloatFormat($all_sk_report_payment_sums)}}</th>
                <th>{{titleFloatFormat($all_sk_to_transfer_total)}}</th>

                <th>{{titleFloatFormat($all_agent_report_payment_sums)}}</th>
                <th>{{titleFloatFormat($all_agent_to_return_total)}}</th>


                <th></th>
            </tr>
        </tfoot>
    @endif
</table>