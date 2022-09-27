@extends('layouts.frame')


@section('title')

    Сегмент

@stop

@section('content')



    {{ Form::open(['url' => url("/directories/insurance_companies/{$insurance_companies->id}/bso_suppliers/{$bso_supplier->id}/financial_policy/$financial_policy->id/segments/".(int)$segment->id."/"), 'method' => 'post',  'class' => 'form-horizontal', 'id' => 'formContract', 'files' => true]) }}

    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default panel-outer">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="title">
                                        Страхователь
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="panel-body ">

                    <div class="col-lg-12">
                        <label>Тип страхователя</label>
                        <label class="pull-right">Любой
                            {{Form::checkbox('insurer_type_any', 1, $segment->insurer_type_any)}}
                        </label>
                        {{Form::select('insurer_type_id', [0 =>'Физ лицо', 1=>'Юр лицо'], $segment->insurer_type_id, ['class' => 'form-control insurer_type_any'])}}
                    </div>
                    <div class="col-lg-12">
                        <label>Адрес прописки собственника</label>
                        <label class="pull-right">Любой {{Form::checkbox('insurer_location_any', 1, $segment->insurer_location_any)}}</label>
                        {{Form::select('location_id', \App\Models\Settings\City::all()->pluck('title', 'id'), $segment->location_id, ['class' => 'form-control insurer_location_any'])}}
                    </div>
                    <div class="col-lg-12">
                        <label>Коэффициент территории</label>
                        <label class="pull-right">Любой {{Form::checkbox('insurer_kt_any', 1, $segment->insurer_kt_any)}}</label>
                        <input type="text" class="form-control sum insurer_kt_any" name="insurer_kt" value="{{$segment->insurer_kt}}">
                    </div>

                </div>

            </div>


        </div>
        <div class="col-md-6">


            <div class="panel panel-default panel-outer">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="title">
                                        Договор
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="panel-body ">

                    <div class="col-lg-12">
                        <label>Тип</label>
                        <label class="pull-right">Любой {{Form::checkbox('contract_type_any', 1, $segment->contract_type_any)}}</label>
                        {{Form::select('contract_type_id', collect([0 => 'Новый', 1 => 'Пролонгация']), $segment->contract_type_id, ['class' => 'form-control contract_type_any'])}}
                    </div>
                    <div class="col-lg-12">
                        <label>Период использования</label>
                        <label class="pull-right">Любой {{Form::checkbox('period_any', 1, $segment->period_any)}}</label>
                        {{Form::select('period', [12 => 'Год', 11 => 'Менее года'], $segment->period, ['class' => 'form-control period_any'])}}
                    </div>
                    <div class="col-lg-12">
                        <label>КБМ</label>
                        <label class="pull-right">Любой {{Form::checkbox('kbm_any', 1, $segment->kbm_any)}}</label>
                        <input type="text" class="form-control sum kbm_any" name="kbm" value="{{$segment->kbm}}">
                    </div>
                </div>

            </div>


        </div>
    </div>


    <div class="row">
        <div class="col-md-6">

            <div class="panel panel-default panel-outer">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label class="title">
                                        ТС
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="panel-body ">
                    <div class="row col-md-12">
                        <div class="col-lg-6">
                            <label>Категория ТС</label>
                            {{Form::select('vehicle_category_id', \App\Models\Vehicle\VehicleCategories::all()->pluck('title', 'id'), $segment->vehicle_category_id, ['class' => 'form-control'])}}
                        </div>
                        <div class="col-lg-6">
                            <label>Тип ТС</label>
                            <label class="pull-right">Любой {{Form::checkbox('vehicle_country_any', 1, $segment->vehicle_country_any)}}</label>
                            {{Form::select('vehicle_country_id', collect([1 => 'Иномарка', 2 => 'Отечественное']), $segment->vehicle_country_id, ['class' => 'form-control vehicle_country_any'])}}
                        </div>

                    </div>

                    <div class="row col-md-12" style="margin-top: 10px;">
                        <div class="col-md-4">
                            <label>Мощность ТС (Любая {{Form::checkbox('vehicle_power_any', 1, $segment->vehicle_power_any)}})</label>
                        </div>
                        <div class="col-md-1 vehicle_power_any">
                            <label>от > </label>
                        </div>
                        <div class="col-md-3 vehicle_power_any">
                            <input type="text" class="form-control" value="{{$segment->vehicle_power_from}}"
                                   name="vehicle_power_from">
                        </div>
                        <div class="col-md-1 vehicle_power_any">
                            <label>до</label>
                        </div>
                        <div class="col-md-3 vehicle_power_any">
                            <input type="text" class="form-control" value="{{$segment->vehicle_power_to}}"
                                   name="vehicle_power_to">
                        </div>
                    </div>

                    <div class="row col-md-12" style="margin-top: 10px;">
                        <div class="col-lg-4 ">
                            <label>Прицеп (Не важно {{Form::checkbox('has_trailer_any', 1, $segment->has_trailer_any)}}) <span class="has_trailer_any">Да {{Form::checkbox('has_trailer', 1, $segment->has_trailer)}}</span></label>
                        </div>
                        <div class="col-md-4">
                            <label>Возраст ТС  (Любой {{Form::checkbox('vehicle_age_any', 1, $segment->vehicle_age_any)}}) до < </label>
                        </div>
                        <div class="col-md-4 vehicle_age_any">
                            <input type="text" class="form-control" value="{{$segment->vehicle_age}}" name="vehicle_age">
                        </div>
                    </div>

                </div>

            </div>


        </div>
        <div class="col-md-6">


            <div class="panel panel-default panel-outer">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-8">
                            <label class="title">
                                Водители - неважно {{Form::checkbox('owner_age_any', 1, $segment->owner_age_any)}}
                            </label>

                            <label class="title pull-right" >

                                <label>Мультидрайв</label>
                                {{Form::checkbox('is_multi_drive_any', 1, $segment->is_multi_drive_any)}}

                            </label>
                        </div>
                    </div>
                </div>
                <div class="panel-body ">

                    <div class="col-lg-12 multi_driver_row">
                        <label>Минимальный возраст собственника</label>
                        <label class="pull-right"></label>
                        <input type="number" class="form-control owner_age_any" name="owner_age" value="{{$segment->owner_age}}">
                    </div>


                    <div class="col-lg-12 non_multi_driver_row">
                        <label>Минимальный возраст водителей</label>
                        <label class="pull-right">Любой {{Form::checkbox('drivers_age_any', 1, $segment->drivers_age_any)}}</label>
                        <input type="number" class="form-control drivers_age_any" name="drivers_min_age" value="{{$segment->drivers_min_age}}">
                    </div>

                    <div class="col-lg-12 non_multi_driver_row">
                        <label>Минимальный стаж водителей</label>
                        <label class="pull-right">Любой {{Form::checkbox('drivers_exp_any', 1, $segment->drivers_exp_any)}}</label>
                        <input type="number" class="form-control drivers_exp_any" name="drivers_min_exp" value="{{$segment->drivers_min_exp}}">
                    </div>

                </div>

            </div>


        </div>
    </div>

    {{Form::close()}}






@endsection


@section('footer')

    <button onclick="submitForm()" type="submit" class="btn btn-primary">{{ trans('form.buttons.save') }}</button>

@stop


@section('js')
    <script>
        $(function () {

            $('input:checkbox').change(function () {
                checkViewControl($(this).is(':checked'), $(this).attr('name'));
            });

            startViewControl();

        });

        function startViewControl(){

            $('#formContract').find("input:checkbox").each(function() {
                checkViewControl($(this).is(':checked'), $(this).attr('name'));
            });

        }


        function checkViewControl(checked, obj_name) {
            if('is_multi_drive_any' == obj_name){
                if (checked) {
                    $('.non_multi_driver_row').addClass('hidden');
                    $('.multi_driver_row').removeClass('hidden');
                } else {
                    $('.non_multi_driver_row').removeClass('hidden');
                    $('.multi_driver_row').addClass('hidden');
                }
            }else{
                $('.'+obj_name).toggleClass('hidden',  checked);
            }

        }



    </script>


    <style>




        .panel-body label{
            font-weight: normal;
        }




        .panel-heading label {
            color: #134C9F;
            font-weight: normal;


        }

        .panel-default > .panel-heading{
            background-color: #FFF;
            display: block;
            width: auto;
            padding: 0.4em 0 0.6em 0;
            font-size: 16px;

        }






    </style>

@append