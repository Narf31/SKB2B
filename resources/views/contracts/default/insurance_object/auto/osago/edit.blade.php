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
                        {{Form::select("contract[object][ts_category]", \App\Models\Vehicle\VehicleCategories::query()->get()->pluck('title', 'id'), ($object->ts_category)?$object->ts_category:2, ['class' => 'form-control select2-ws', 'id'=>"object_ts_category", 'onchange'=>"viewCategory();"])}}
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
                            <label class="control-label">Год выпуска <span class="required">*</span></label>
                            {{Form::text("contract[object][car_year]", $object->car_year ? $object->car_year : "", ['class' => 'form-control valid_accept','id' => 'carYear', 'style'=>'width: 100%;', 'placeholder' => '2018', 'onchange'=>"viewDK();"])}}

                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                            <label class="control-label">Рег. номер</label>
                            {{ Form::text("contract[object][reg_number]", $object->reg_number, ['class' => "form-control to_up_letters only_ru", "id"=>"object_ts_reg_number", 'placeholder' => 'Е050КХ99']) }}

                            <input type="hidden" name="contract[object][type_reg_number]" value="1"/>
                        </div>

                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3" >
                        <div class="form-equally col-xs-4 col-sm-4 col-md-4 col-lg-4" style="padding-bottom:0;">
                            <label class="control-label">VIN <span class="required">*</span></label>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8" style="text-align:right;padding-bottom:0;">
                            <label  class="control-label" style="padding-right:5px;max-width:100%;">отсутствует</label>
                            {{ Form::checkbox('contract[object][not_vin]', 1, $object->vin == 'ОТСУТСТВУЕТ' ,['style' => 'position:absolute;','onclick' => "setVIN()", 'id'=>"not_vin"]) }}
                        </div>

                        {{ Form::text("contract[object][vin]", $object->vin, ['class' => 'form-control to_up_letters only_en valid_accept', "id"=>"object_ts_vin", 'placeholder' => 'KL1UF756E6B195928']) }}
                    </div>

                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" >
                        <label class="control-label">Номер кузова</label>
                        {{ Form::text("contract[object][body_number]", $object->body_number, ['class' => 'form-control', 'placeholder' => '235347453234']) }}
                    </div>

                    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3" >
                        <label class="control-label">Номер шасси</label>
                        {{ Form::text("contract[object][body_chassis]", $object->body_chassis, ['class' => 'form-control', 'placeholder' => '235347453234']) }}
                    </div>


                    <div class="clear"></div>


                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3" >
                        <label class="control-label">Цель использования <span class="required">*</span></label>
                        {{Form::select("contract[object][purpose_id]", \App\Models\Vehicle\VehiclePurpose::PURPOSE, ($object->purpose_id?$object->purpose_id:1), ['class' => 'form-control select2-ws', 'style'=>'width: 100%;'])}}
                    </div>







                    <div class="form-equally col-xs-12 col-sm-6 col-md-6 col-lg-3" >
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                            <label class="control-label">Мощность (л.с.) <span class="required">*</span></label>
                            {{ Form::text("contract[object][power]", $object->power, ['class' => 'form-control sum valid_accept', "id"=>"object_ts_power", "onkeyup"=>"var int = Math.round($('#object_ts_power').val()/1.3596); if(!isNaN(int)) $('#object_ts_powerkw').val(int);"]) }}


                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
                            <label class="control-label">Мощность (кВт)</label>
                            {{ Form::text("contract[object][powerkw]", $object->powerkw, ['class' => 'form-control sum valid_accept', "id"=>"object_ts_powerkw", "onkeyup"=>"var int = Math.round($('#object_ts_powerkw').val()*1.3596); if(!isNaN(int)) $('#object_ts_power').val(int);"]) }}
                        </div>

                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-3" >
                        <label class="control-label">Страна регистрации</label>
                        {{Form::select("contract[object][country_id]", \App\Models\Settings\Country::all()->pluck('title', 'id'), ($object->country_id?$object->country_id:51), ['class' => 'form-control select2-all'])}}
                    </div>

                    {{--- Доп условия для типов автомобилей ----}}


                    <div class="form-equally col-xs-12 col-sm-12 col-md-6 col-lg-3 view-dop" >
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">Масса <span class="required">*</span></label>
                            {{ Form::text("contract[object][weight]", $object->weight, ['class' => 'form-control', 'placeholder' => '1500']) }}
                        </div>


                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 view-dop">
                            <label class="control-label">Грузоподъемность <span class="required">*</span></label>
                            {{ Form::text("contract[object][capacity]", $object->capacity, ['class' => 'form-control', 'placeholder' => '900']) }}
                        </div>
                    </div>

                    <div class="form-equally col-xs-12 col-sm-12 col-md-6 col-lg-3 view-dop-passengers">

                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            <label class="control-label">Кол-во мест</label>
                            {{ Form::text("contract[object][passengers_count]", $object->passengers_count, ['class' => 'form-control sum', 'placeholder' => '5']) }}
                        </div>
                    </div>



                    {{--- end ---}}



                    <div class="clear"></div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" >
                        <label  class="control-label" style="max-width:100%;">Автомобиль используется с прицепом</label>
                        {{ Form::checkbox('contract[object][is_trailer]', 1, $object->is_trailer,['style' => 'width:18px;height:18px;margin-left:5px;position:absolute;']) }}
                    </div>

                    <div class="clear"></div>

                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3" >
                        <label class="control-label">Тип документа<span class="required">*</span></label>
                        {{Form::select("contract[object][doc_type]", collect(\App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto::DOC_TYPE_TS), $object->doc_type , ['class' => 'form-control select2-ws', 'id'=>'object_ts_doc_type', 'onchange'=>'viewDocType()'])}}
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3" id="docserie">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Серия <span class="required">*</span>
                                </label>
                                {{ Form::text('contract[object][docserie]', $object->docserie, ['class' => 'form-control to_up_letters', 'placeholder' => '38MB']) }}
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3" id="docnumber">
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Номер <span class="required">*</span>
                                </label>
                                {{ Form::text('contract[object][docnumber]', $object->docnumber, ['class' => 'form-control to_up_letters', 'placeholder' => '587123']) }}
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата выдачи <span class="required">*</span>
                                </label>
                                {{ Form::text('contract[object][docdate]', getDateFormatRu($object->docdate), ['class' => 'form-control format-date end-date', 'placeholder' => '18.04.2012']) }}
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>

                    <div class="clear"></div>


                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 view-dk" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Номер диагностической карты <span class="required">*</span>
                                </label>
                                {{ Form::text('contract[object][dk_number]', $object->dk_number, ['class' => 'form-control to_up_letters form-dk', 'placeholder' => '345634286492751']) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 view-dk" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата начала <span class="required">*</span>
                                </label>
                                {{ Form::text('contract[object][dk_date_from]', getDateFormatRu($object->dk_date_from), ['class' => 'form-control form-dk format-date', 'placeholder' => '13.02.2018']) }}
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 view-dk" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата окончания <span class="required">*</span>
                                </label>
                                {{ Form::text('contract[object][dk_date_to]', getDateFormatRu($object->dk_date_to), ['class' => 'form-control form-dk format-date', 'placeholder' => '13.02.2019']) }}
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>





@include('contracts.default.insurance_object.auto/js')
