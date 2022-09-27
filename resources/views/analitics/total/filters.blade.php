<div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
    <label class="control-label" for="period">Период</label>
    @php($period_select = collect([0 => 'Месяц', 1 => 'Год', 2 => 'Все', 3 => 'Вручную']))
    {{ Form::select('period', $period_select, request('period', 0), ['class' => 'form-control select2-all select2-ws', 'onchange' => 'loadItems()']) }}
</div>


@if(in_array(request('period'), [0,1]))
    <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <label class="control-label" for="year">Год</label>
        @php($year_select = collect(getYearsRange(-3, 0)))
        {{ Form::select('year', $year_select, request('year', date('Y')), ['class' => 'form-control select2-all select2-ws', 'onchange' => 'loadItems()']) }}
    </div>
@endif


@if(in_array(request('period'), [0]))
    <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <label class="control-label" for="month">Месяц</label>
        @php($month_select = collect(getRuMonthes()))
        {{ Form::select('month', $month_select, request('month',  date('n')), ['class' => 'form-control select2-all select2-ws', 'onchange' => 'loadItems()']) }}
    </div>
@endif

@if(in_array(request('period'), [3]))
    <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <label class="control-label" for="from">C</label>
        {{ Form::text('from', request('from', date('d.m.Y', time()-60*60*24*365)), ['class' => 'form-control datepicker date', 'onchange' => 'loadItems()']) }}
    </div>
    <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
        <label class="control-label" for="to">По</label>
        {{ Form::text('to', request('to', date('d.m.Y')), ['class' => 'form-control datepicker date', 'onchange' => 'loadItems()']) }}
    </div>
@endif


