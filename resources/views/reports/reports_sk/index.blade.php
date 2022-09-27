@extends('layouts.app')

@section('content')



    <div class="form-horizontal block-inner col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="filter-group">
                    <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        {{ Form::select('organization_id', \App\Models\Organizations\Organization::getALLOrg()->get()->pluck('title', 'id')->prepend('Организация', -1), \Request::get('organization_id'), ['class' => 'form-control select2', 'id'=>'organization_id']) }}
                    </div>
                    <div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        {{ Form::select('curator_id', \App\Models\User::getALLUserWhere()->where('is_parent',1)->get()->pluck('name', 'id')->prepend('Куратор', -1), \Request::get('curator_id'), ['class' => 'form-control select2', 'id'=>'curator_id']) }}
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                        <span class="btn btn-success pull-left" onclick="loadItems()">Применить</span>

                        <span class="btn btn-primary pull-right" onclick="refreshItems()">Сбросить</span>
                    </div>




                    <div class="btn-group col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div id="table">
                <table class="table table-striped table-bordered">
                    <thead>

                    <tr>
                        <th rowspan="2">Организация</th>
                        <th rowspan="2">Куратор</th>
                        <th colspan="5">Отчеты</th>
                        <th colspan="2">Всего</th>
                    </tr>

                    <tr>

                        <th rowspan="2">Бордеро</th>
                        <th rowspan="2">ДВОУ</th>
                        <th rowspan="2">К перечислению в СК</th>
                        <th rowspan="2">Долг перед агентом (Отчеты)</th>

                        <th rowspan="2">Дебит</th>
                        <th rowspan="2">Кредит</th>
                    </tr>
                    </thead>
                    <tbody>

                    @php($all_bordereauTotal = 0)
                    @php($all_dvoyTotal = 0)
                    @php($all_to_transferTotal = 0)
                    @php($all_to_returnTotal = 0)
                    @php($all_to_depTotal = 0)
                    @php($all_to_kredTotal = 0)

                    @if(sizeof($organizations))
                        @foreach($organizations as $organization)

                            @php($organization_payments = $organization->getDebtBrokerToSk())

                            @php($all_bordereauTotal += getFloatFormat($organization->getPaymentsTotalKV(0)))
                            @php($all_dvoyTotal += getFloatFormat($organization->getPaymentsTotalKV(1)))
                            @php($all_to_transferTotal += getFloatFormat($organization_payments['to_transfer_total']))
                            @php($all_to_returnTotal += getFloatFormat($organization_payments['to_return_total']))
                            @php($all_to_depTotal += getFloatFormat($organization->getPaymentsTotal(0)+$organization_payments['to_transfer_total']))
                            @php($all_to_kredTotal += getFloatFormat($organization->getPaymentsTotal(1)+$organization_payments['to_return_total']))


                            <tr class="clickable-row">
                                <td>
                                    <a href="{{url("/reports/reports_sk/{$organization->id}/info/")}}" target="_blank">
                                        {{ $organization->title }}
                                    </a> (всего отчетов {{$organization->reports->count()}})
                                </td>
                                <td>{{$organization->curator?$organization->curator->name:''}}</td>
                                <td class="text-center">
                                    <a href="{{url("/reports/reports_sk/{$organization->id}/bordereau/")}}">
                                        {{titleFloatFormat($organization->getPaymentsTotalKV(0))}}
                                    </a>
                                </td>
                                <td class="text-center">

                                    <a href="{{url("/reports/reports_sk/{$organization->id}/dvoy/")}}">
                                        {{titleFloatFormat($organization->getPaymentsTotalKV(1))}}
                                    </a>
                                </td>
                                <td>
                                    {{ titleFloatFormat($organization_payments['to_transfer_total']) }}
                                </td>
                                <td>
                                    {{ titleFloatFormat($organization_payments['to_return_total']) }}
                                </td>

                                <td class="text-center">{{titleFloatFormat($organization->getPaymentsTotal(0)+$organization_payments['to_transfer_total'])}}</td>
                                <td class="text-center">{{titleFloatFormat($organization->getPaymentsTotal(1)+$organization_payments['to_return_total'])}}</td>


                            </tr>
                        @endforeach
                    @endif

                    <tr>
                        <td colspan="2">
                            <b>Итого</b>
                        </td>
                        <td class="text-center"><b>{{titleFloatFormat($all_bordereauTotal)}}</b></td>
                        <td class="text-center"><b>{{titleFloatFormat($all_dvoyTotal)}}</b></td>
                        <td>{{titleFloatFormat($all_to_transferTotal)}}</td>
                        <td>{{titleFloatFormat($all_to_returnTotal)}}</td>
                        <td class="text-center"><b>{{titleFloatFormat($all_to_depTotal)}}</b></td>
                        <td class="text-center"><b>{{titleFloatFormat($all_to_kredTotal)}}</b></td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>





@stop

@section('js')

    <script>


        $(function () {

        });

        function loadItems()
        {
            organization_id = $("#organization_id").val();
            curator_id = $("#curator_id").val();
            window.location = '{{url("/reports/reports_sk")}}?organization_id='+organization_id+'&curator_id='+curator_id;

        }

        function refreshItems() {
            window.location = '{{url("/reports/reports_sk")}}';
        }


    </script>

@stop