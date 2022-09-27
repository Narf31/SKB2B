
<div class="page-heading">
    <h2 class="inline-h1">Водители</h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

                    @php
                        $calc_data = new stdClass();
                        $calc_data->birthdate_year = '';
                        $calc_data->birthdate_year_l = '';
                        $calc_data->exp_year = '';
                        $calc_data->exp_year_l = '';
                        if($contract->data->calc_data && strlen($contract->data->calc_data) > 5){
                            $calc_data = json_decode($contract->data->calc_data);
                        }

                    @endphp

                    <div class="col-xs-12 col-sm-4 col-md-6 col-lg-3" >
                        <label class="control-label">Тип</label>
                        {{Form::select("contract[insurers][is_multidriver]", collect([0 => 'Ограниченный список', 1 => 'Мультидрайв', 2 => 'Мультидрайв: Ограниченный список']), $contract->data->is_multidriver, ['class' => 'select2-ws', "id"=>"is_multidriver", 'onchange'=>"getModelsObjectInsurerCalc();"])}}
                    </div>


                    <div class="form-equally col-xs-12 col-sm-4 col-md-6 col-lg-3 is_multidriver_0 " >


                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
                            <label class="control-label">Возраст (лет) <span class="required">*</span></label>
                            {{ Form::text("contract[insurers][birthdate_year]", $calc_data->birthdate_year, ['class' => 'form-control valid_accept', "id"=>"birthdate_year", "onkeyup"=>"var int = Math.round(".date('Y')."-$('#birthdate_year').val()); if(!isNaN(int)) $('#birthdate_year_l').val(int);"]) }}
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
                            <label class="control-label">Стаж (лет) <span class="required">*</span></label>
                            {{ Form::text("contract[insurers][exp_year]", $calc_data->exp_year, ['class' => 'form-control valid_accept', "id"=>"exp_year", "onkeyup"=>"var int = Math.round(".date('Y')."-$('#exp_year').val()); if(!isNaN(int)) $('#exp_year_l').val(int);"]) }}
                        </div>

                    </div>

                    <div class="form-equally col-xs-12 col-sm-4 col-md-6 col-lg-3 is_multidriver_0 " >
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 hidden" >
                            <label class="control-label">Стаж (год) <span class="required">*</span></label>
                            {{ Form::text("contract[insurers][exp_year_l]", $calc_data->exp_year_l, ['class' => 'form-control valid_accept', "id"=>"exp_year_l", "onkeyup"=>"var int = Math.round(".date('Y')."-$('#exp_year_l').val()); if(!isNaN(int)) $('#exp_year').val(int);"]) }}

                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 hidden" >
                            <label class="control-label">Возраст (год) <span class="required">*</span></label>
                            {{ Form::text("contract[insurers][birthdate_year_l]", $calc_data->birthdate_year_l, ['class' => 'form-control valid_accept', "id"=>"birthdate_year_l", "onkeyup"=>"var int = Math.round(".date('Y')."-$('#birthdate_year_l').val()); if(!isNaN(int)) $('#birthdate_year').val(int);"]) }}

                        </div>



                    </div>


                    <div class="clear"></div>



                    <div class="clear"></div>

                    <div class="col-lg-6" >
                        {{ Form::checkbox('contract[insurers][is_only_spouses]', 1, $contract->data->is_only_spouses,['style' => 'width:18px;height:18px;']) }}
                        <label  class="control-label" style="max-width:100%;">Допущены только супруги</label>
                    </div>




                </div>
            </div>
        </div>
    </div>
</div>




<div class="page-heading">
    <h2 class="inline-h1">Транспортное средство</h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3">
                        <label class="control-label">Категория <span class="required">*</span></label>
                        {{Form::select("contract[object][ts_category]", \App\Models\Vehicle\VehicleCategories::query()->where('is_actual', 1)->get()->pluck('title', 'id'), ($object->ts_category)?$object->ts_category:2, ['class' => 'form-control select2-ws', 'id'=>"object_ts_category", 'onchange'=>"viewCategory();"])}}
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3" >
                        <label class="control-label">Марка <span class="required">*</span></label>
                        {{Form::select("contract[object][mark_id]", \App\Models\Vehicle\VehicleMarks::orderBy('title')->get()->pluck('title', 'id')->prepend('Не выбрано', 0), $object->mark_id, ['class' => 'select2-all valid_accept', "id"=>"object_ts_mark_id", 'style'=>'width: 100%;', 'onchange'=>"getModelsObjectInsurer(0 ,0);"])}}
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3" >
                        <label class="control-label">Модель <span class="required">*</span></label>
                        {{Form::select("contract[object][model_id]", [0=>'Не выбрано'], $object->model_id, ['class' => 'select2-all valid_accept', "id"=>"object_ts_model_id", 'onchange'=>"getModelsClassificationObjectInsurer(0);"])}}
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3" >
                        <label class="control-label">Модификация</label>
                        {{Form::text("contract[object][model_classification_code]", $object->model_classification_code, ['class' => 'form-control'])}}
                    </div>

                    <div class="clear"></div>



                    <div class="form-equally col-xs-12 col-sm-12 col-md-12 col-lg-3" >
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                            <label class="control-label">Стоимость автомобиля <span class="required">*</span></label>
                            {{ Form::text("contract[object][car_price]", titleFloatFormat($object->car_price, 0, 1), ['class' => 'form-control sum valid_accept', 'id'=>'car_price']) }}

                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                            <label class="control-label">Год выпуска <span class="required">*</span></label>
                            {{Form::text("contract[object][car_year]", $object->car_year ? $object->car_year : "", ['class' => 'form-control valid_accept','id' => 'carYear', 'style'=>'width: 100%;', 'placeholder' => '2018', 'onchange'=>"viewDK();"])}}
                        </div>

                    </div>


                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" >
                        <label class="control-label">VIN</label>
                        {{ Form::text("contract[object][vin]", $object->vin, ['class' => 'form-control to_up_letters only_en', "id"=>"object_ts_vin", 'placeholder' => 'KL1UF756E6B195928']) }}

                    </div>





                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" >
                        <label class="control-label">Цвет</label>
                        {{Form::select("contract[object][color_id]", \App\Models\Vehicle\VehicleColor::all()->pluck('title', 'isn')->prepend('Не выбрано', 0), $object->color_id, ['class' => 'form-control select2-all'])}}
                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3" >
                        <label class="control-label">Противоугонное устройство</label>
                        {{Form::select("contract[object][anti_theft_system_id]", \App\Models\Vehicle\VehicleAntiTheftSystem::all()->pluck('title', 'id')->prepend('Не выбрано', 0), $object->anti_theft_system_id, ['class' => 'form-control select2-all'])}}
                    </div>

                    <div class="clear"></div>







                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" >
                        <label class="control-label">Коробка передач<span class="required">*</span></label>
                        {{Form::select("contract[object][transmission_type]", collect(\App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto::TRANSMISSION_TYPE), $object->transmission_type, ['class' => 'form-control select2-ws valid_accept'])}}
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" >
                        <label class="control-label">Тип двигателя</label>
                        {{Form::select("contract[object][engine_type_id]", collect(\App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto::ENGINE_TYPE_TS), $object->engine_type_id, ['class' => 'form-control select2-ws'])}}

                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" >
                        <label class="control-label">Источник приобретения</label>
                        {{Form::select("contract[object][source_acquisition_id]", collect(\App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto::SOURCE_ACQUISITION_TS), $object->source_acquisition_id, ['class' => 'form-control select2-ws'])}}

                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" >
                        <label class="control-label">Страна регистрации</label>
                        {{Form::select("contract[object][country_id]", \App\Models\Settings\Country::all()->pluck('title', 'id'), ($object->country_id?$object->country_id:51), ['class' => 'form-control select2-all'])}}
                    </div>






                    <div class="clear"></div>


                    <div class="form-equally col-xs-12 col-sm-6 col-md-6 col-lg-3" >


                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                            <label class="control-label">Пробег </label>
                            {{ Form::text("contract[object][mileage]", titleFloatFormat($object->mileage, 0, 1), ['class' => 'form-control sum ']) }}
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                            <label class="control-label">Объём (см3) </label>
                            {{ Form::text("contract[object][volume]", $object->volume, ['class' => 'form-control sum ']) }}
                        </div>



                    </div>


                    <div class="form-equally col-xs-12 col-sm-6 col-md-6 col-lg-3" >
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                            <label class="control-label">Мощность (л.с.) </label>
                            {{ Form::text("contract[object][power]", $object->power, ['class' => 'form-control sum ', "id"=>"object_ts_power", "onkeyup"=>"var int = Math.round($('#object_ts_power').val()/1.3596); if(!isNaN(int)) $('#object_ts_powerkw').val(int);"]) }}

                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                            <label class="control-label">Мощность (кВт)</label>
                            {{ Form::text("contract[object][powerkw]", $object->powerkw, ['class' => 'form-control sum ', "id"=>"object_ts_powerkw", "onkeyup"=>"var int = Math.round($('#object_ts_powerkw').val()*1.3596); if(!isNaN(int)) $('#object_ts_power').val(int);"]) }}
                        </div>

                    </div>


                    <div class="form-equally col-xs-12 col-sm-6 col-md-6 col-lg-3" >
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">Масса</label>
                            {{ Form::text("contract[object][weight]", $object->weight, ['class' => 'form-control', 'placeholder' => '1500']) }}
                        </div>


                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">Грузоподъемность</label>
                            {{ Form::text("contract[object][capacity]", $object->capacity, ['class' => 'form-control', 'placeholder' => '900']) }}
                        </div>
                    </div>

                    <div class="form-equally col-xs-12 col-sm-6 col-md-6 col-lg-3" >
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                            <label class="control-label">Кол-во собственников</label>
                            {{Form::select("contract[object][number_owners]", collect(\App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto::NUMBER_OWNERS), $object->number_owners, ['class' => 'form-control select2-ws'])}}
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                            <label class="control-label">Ключей (шт.) <span class="required">*</span></label>
                            {{ Form::text("contract[object][count_key]", ($object->count_key)?$object->count_key:2, ['class' => 'form-control sum valid_accept']) }}
                        </div>
                    </div>


                    <div class="clear"></div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3" >
                        <label class="control-label">Цель использования <span class="required">*</span></label>
                        {{Form::select("contract[object][purpose_id]", \App\Models\Vehicle\VehiclePurpose::PURPOSE, ($object->purpose_id?$object->purpose_id:1), ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                    </div>


                    <div class="clear"></div>



                    <div class="col-lg-6" >

                        {{ Form::checkbox('contract[object][is_trailer]', 1, $object->is_trailer,['style' => 'width:18px;height:18px;']) }}
                        <label  class="control-label" style="max-width:100%;">Автомобиль используется с прицепом</label>
                    </div>

                    <div class="clear"></div>

                    <input type="hidden" name="contract[object][is_credit]" value="0"/>


                    <div class="clear"></div>

                    <div class="col-lg-6" >
                        {{ Form::checkbox('contract[object][is_autostart]', 1, $object->is_autostart,['style' => 'width:18px;height:18px;']) }}
                        <label  class="control-label" style="max-width:100%;">Автозапуск</label>
                    </div>

                    <div class="clear"></div>

                    <div class="col-lg-6" >
                        {{ Form::checkbox('contract[object][is_right_drive]', 1, $object->is_right_drive,['style' => 'width:18px;height:18px;']) }}
                        <label  class="control-label" style="max-width:100%;">Праворульный автомобиль</label>
                    </div>

                    <div class="clear"></div>

                    <div class="col-lg-6" >
                        {{ Form::checkbox('contract[object][is_duplicate]', 1, $object->is_duplicate,['style' => 'width:18px;height:18px;']) }}
                        <label  class="control-label" style="max-width:100%;">Менялись номера агрегатов/Дубликат ПТС</label>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>





@include('contracts.default.insurance_object.auto.kasko.calculator.js')
