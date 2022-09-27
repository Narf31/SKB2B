@extends('layouts.app')

@section('content')

{{ Form::open(['url' => url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}/financial_policy/".(int)$financial_policy->id."/"), 'method' => 'post',  'class' => 'form-horizontal', 'id' => 'formContract', 'files' => true]) }}
<div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">
    <div class="page-subheading">
        <h2>Финансовая политика
            <a href="{{url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}/")}}">{{$bso_supplier->title}}</a>
        </h2>
    </div>
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">

                <div class="form-group">
                    <label  class="col-sm-4 control-label">Актуально</label>
                    <div class="col-sm-8">
                        {{ Form::checkbox('is_actual', 1, $financial_policy->is_actual) }}
                    </div>
                </div>

                <div class="form-group">
                    <label  class="col-sm-4 control-label">Продукт</label>
                    <div class="col-sm-8">
                        {{ Form::select('product_id', \App\Models\Directories\Products::where('is_actual', '=', '1')->get()->pluck('title', 'id')->prepend('Все', 0), $financial_policy->product_id, ['class' => 'form-control', 'required']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label  class="col-sm-4 control-label">Дата действия</label>
                    <div class="col-sm-8">
                        {{ Form::text('date_active', setDateTimeFormatRu($financial_policy->date_active, 1), ['class' => 'form-control datepicker date']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label  class="col-sm-4 control-label">Название</label>
                    <div class="col-sm-8">
                        {{ Form::text('title', $financial_policy->title, ['class' => 'form-control']) }}
                    </div>
                </div>

                <div class="form-group">
                    <label  class="col-sm-4 control-label">КВ Бордеро</label>
                    <div class="col-sm-8">
                        {{ Form::text('kv_bordereau', $financial_policy->kv_bordereau, ['class' => 'form-control sum']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-sm-4 control-label" >КВ Двоу</label>
                    <div class="col-sm-8">
                        {{ Form::text('kv_dvou', $financial_policy->kv_dvou, ['class' => 'form-control sum']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-sm-4 control-label">КВ СК</label>
                    <div class="col-sm-8">
                        {{ Form::text('kv_sk', $financial_policy->kv_sk, ['class' => 'form-control sum', 'disabled']) }}
                    </div>
                </div>
                {{--
                <div class="form-group">
                    <label  class="col-sm-4 control-label">Базовое КВ Агента / Менеджера</label>
                    <div class="col-sm-8">
                        {{ Form::text('kv_agent', $financial_policy->kv_agent, ['class' => 'form-control sum']) }}
                    </div>
                </div>
                --}}
                <div class="form-group">
                    <label  class="col-sm-4 control-label">Базовое КВ Руководителя</label>
                    <div class="col-sm-8">
                        {{ Form::text('kv_parent', $financial_policy->kv_parent, ['class' => 'form-control sum']) }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <input class="pull-right btn btn-primary" type="submit" value="Сохранить"/>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>




    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <div class="page-subheading">
            <h2>Финансовые группы</h2>
        </div>
        <div class="block-main">
            <div class="block-sub">
                <div class="form-horizontal">

                    <table class="tov-table">
                        <tr>
                            <th></th>
                            <th>Группа</th>
                            <th>КВ Бордеро</th>
                            <th>КВ Двоу</th>
                            {{--<th>КВ агента / менеджера</th>--}}
                            <th>КВ руководителя</th>
                        </tr>
                        @foreach($financial_policy->availableGroups() as $financialPolicyGroup)
                            <tr>
                                <td class="text-center">
                                    {{ Form::checkbox("financialPolicyGroups[$financialPolicyGroup->id][is_actual]", 1, $financialPolicyGroup->is_actual, ['class' => 'is_actual']) }}
                                </td>
                                <td>{{ $financialPolicyGroup->title }}</td>

                                <td>
                                    {{ Form::text("financialPolicyGroups[$financialPolicyGroup->id][kv_borderau]", titleFloatFormat($financialPolicyGroup->kv_borderau), ['class' => 'form-control sum']) }}
                                </td>
                                <td>
                                    {{ Form::text("financialPolicyGroups[$financialPolicyGroup->id][kv_dvou]", titleFloatFormat($financialPolicyGroup->kv_dvou), ['class' => 'form-control sum']) }}
                                </td>
                                {{--<td>
                                    {{ Form::text("financialPolicyGroups[$financialPolicyGroup->id][kv_agent]", titleFloatFormat($financialPolicyGroup->kv_agent), ['class' => 'form-control sum']) }}
                                </td>--}}
                                <td>
                                    {{ Form::text("financialPolicyGroups[$financialPolicyGroup->id][kv_parent]", titleFloatFormat($financialPolicyGroup->kv_parent), ['class' => 'form-control sum']) }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="form-group"></div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input class="pull-right btn btn-primary" type="submit" value="Сохранить"/>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


{{Form::close()}}


@if($financial_policy->id > 0 )

<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12 hidden">
    <div class="page-subheading">
        <h2>Сегментация</h2>
        <a href="{{url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}/financial_policy/{$financial_policy->id}/segments/0/")}}" class="fancybox fancybox.iframe btn btn-primary pull-right">
            {{ trans('form.buttons.add') }}
        </a>
    </div>
    <div class="block-main">
        <div class="block-sub">
            @if(sizeof($financial_policy->segments))
            <table class="tov-table" >
                @foreach($financial_policy->segments as $segments)
                <tr class="clickable-row fancybox fancybox.iframe" href="{{url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}/financial_policy/{$financial_policy->id}/segments/{$segments->id}/")}}">
                    <td>{{$segments->getTitleAttribute()}}</td>
                </tr>
                @endforeach
            </table>
            @else
            {{ trans('form.empty') }}
            @endif
        </div>
    </div>
</div>


@endif




@endsection


@section('js')
<script>
    $(function () {

        $("input[name=kv_bordereau], input[name=kv_dvou]").change(function () {
            var val1 = +parseFloat(getFloatFormat($("input[name=kv_bordereau]").val())).toFixed(2);
            var val2 = +parseFloat(getFloatFormat($("input[name=kv_dvou]").val())).toFixed(2);

            var val3 = (val1 + val2).toFixed(2);
            $("input[name=kv_sk]").val(val3);
        });


    });



</script>
@append