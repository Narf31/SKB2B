@php

    $monthes = getRuMonthes();


    $years = getYearsRange(-5, +1);

    $events = [
        0 => [ //acts_sk_id
            -1 => 'Реестр: Текущий',
            -2 => 'Реестр: Будущий', //event_id
        ],

        -1 => [
            1  => 'Создать отчёт',
            2  => 'Добавить в отчёт',
            -2 => 'Реестр: Будущий',
            0  => 'Реестр: Корзина',
        ],

        -2 => [
            -1 => 'Реестр: Текущий',
            0  => 'Реестр: Корзина',
        ],
    ];

@endphp


<table class="table table-bordered">
    <thead>
        <tr>
            <th>Доступные действия</th>
            <th width="20%">&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                {{Form::select('event_id', $events[$report_id], '', ['class' => 'form-control select2-all', 'id'=>'event_id'])}}
            </td>
            <td class="text-center">
                <a class="btn btn-primary" id="execute">Выполнить</a>
            </td>
        </tr>
    </tbody>
</table>


<div data-additional="1" style="display: none;">
    <div class="block-main">
        <div class="block-sub">
            <div class="filter-group">
                <div class="row">
                    <div class="btn-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label class="control-label" for="act_name">Название</label>
                        {{ Form::text('report_name', '', ['class' => 'form-control']) }}
                    </div>
                </div>

                <div class="row">
                    <div class="btn-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label class="control-label" for="user_id_from">Отчётный период</label>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                {{ Form::select('report_year', $years, (int) date('Y'), ['class' => 'form-control']) }}
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                {{ Form::select('report_month', $monthes, (int) date('m'), ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="btn-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label class="control-label" for="report_date_start">Дата заключения договора с</label>
                        {{ Form::text('report_date_start', '', ['class' => 'form-control datepicker date']) }}
                    </div>
                </div>

                <div class="row">
                    <div class="btn-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label class="control-label" for="report_date_end">Дата заключения договора по</label>
                        {{ Form::text('report_date_end', '', ['class' => 'form-control datepicker date']) }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<div data-additional="2" style="display: none;">
    Добавить в акт
    <div class="block-main">
        <div class="block-sub">
            <div class="filter-group">
                <div class="row">
                    <div class="btn-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <label class="control-label" for="act_name">№ Отчета</label>
                        {{ Form::select('to_report_id', $reports->pluck('title', 'id'), '', ['class' => 'form-control']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







<script>

    $(function(){
        $(document).on('change', '[name="event_id"]', function(){
            var val = $(this).val();
            $('[data-additional]').hide();
            $('[data-additional="'+val+'"]').show();
        })
    })
</script>
