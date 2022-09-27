
<div class="page-heading" data-intro='Если застрахованный является страхователем нажмите для автоматической подстановки'>
    <h2 class="inline-h1">Водители
        {{ Form::checkbox("contract[insurers][is_multidriver]", 1, (int)$contract->data->is_multidriver,['onclick' => "viewMultidriver()", 'id'=>"is_multidriver", "class" => "clear_offers"]) }} <span style="font-size: 13px;">Мультидрайв</span>

        {{ Form::checkbox("contract[insurers][is_only_spouses]", 1, (int)$contract->data->is_only_spouses,["class" => "clear_offers"]) }} <span style="font-size: 13px;">Допущены только супруги</span>
    </h2>
</div>

<div id="clone-insurer" class="hidden">
    <div class="row form-horizontal insurer_[[:KEY:]]">
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-6" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Фамилия имя отчество <span class="required">*</span>

                        <label class="hint--bottom" aria-label='Скопировать страхователя'>
                            <i class="fa fa-user" aria-hidden="true" style="font-size: 16px;cursor: pointer;color: #00aeef;" onclick="isInsurer([[:KEY:]])" ></i>
                        </label>

                        <label class="hint--bottom" aria-label='Скопировать собственника'>
                            <i class="fa fa-key" aria-hidden="true" style="font-size: 16px;cursor: pointer;color: #00aeef;" onclick="isOwner([[:KEY:]])" ></i>
                        </label>


                    </label>
                    <input type="hidden" name="contract[insurers][[[:KEY:]]][is_insurer]" value="0"/>
                    <input type="hidden" name="contract[insurers][[[:KEY:]]][is_owner]" value="0"/>
                    {{ Form::text("contract[insurers][[[:KEY:]]][title]", '', ['class' => 'form-control [[:VALID:]] clear_offers', 'data-key'=>"insurers", 'placeholder' => '']) }}
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Пол <span class="required">*</span>
                    </label>
                    {{Form::select("contract[insurers][[[:KEY:]]][sex]", collect([0=>"муж.", 1=>'жен.']), 0, ['class' => 'form-control [[:SELECT2:]] valid_accept clear_offers', 'data-key'=>"insurers"]) }}
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Дата рождения <span class="required">*</span>
                    </label>
                    {{ Form::text("contract[insurers][[[:KEY:]]][birthdate]", '', ['class' => 'form-control [[:VALID:]] format-date clear_offers', 'placeholder' => '18.05.1976','onchange'=>"setInsurerYear([[:KEY:]], 'birthdate')"]) }}
                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4 col-md-2 col-lg-1" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Возраст
                    </label>
                    <h2 id="birthdate_[[:KEY:]]">0</h2>
                </div>
            </div>
        </div>

        <div class="col-md-1 col-lg-1 delete-block" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        &nbsp;
                    </label>
                    <span class="btn btn-info" onclick="deleteInsured('[[:KEY:]]')">
                         <i class="fa fa-close"></i>
                    </span>
                </div>
            </div>
        </div>

        <div class="clear"></div>

        <div class="col-xs-12 col-sm-4 col-md-5 col-lg-2" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Тип В.У.
                    </label>
                    {{Form::select("contract[insurers][[[:KEY:]]][doc_type]", collect([1145 => 'Водительское удостоверение']), 1145, ['class' => 'form-control [[:SELECT2:]] clear_offers']) }}
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Серия В.У. <span class="required">*</span>
                    </label>
                    {{ Form::text('contract[insurers][[[:KEY:]]][doc_serie]', '', ['class' => "form-control to_up_letters [[:VALID:]] clear_offers"]) }}
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Номер В.У. <span class="required">*</span>
                    </label>
                    {{ Form::text('contract[insurers][[[:KEY:]]][doc_number]', '', ['class' => "form-control to_up_letters [[:VALID:]] clear_offers"]) }}
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Дата выдачи В.У. <span class="required">*</span>
                    </label>
                    {{ Form::text("contract[insurers][[[:KEY:]]][doc_date]", '', ['class' => 'form-control [[:VALID:]] format-date clear_offers', 'placeholder' => '18.05.1976']) }}
                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>

                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
            <div class="field form-col">
                <div>
                    <label class="control-label" style="max-width: none;">
                        Начало стажа <span class="required">*</span>
                    </label>
                    {{ Form::text("contract[insurers][[[:KEY:]]][exp_date]", '', ['class' => 'form-control [[:VALID:]] format-date clear_offers', 'placeholder' => '18.05.1976', 'onchange'=>"setInsurerYear([[:KEY:]], 'exp_date')"]) }}
                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-1" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Стаж
                    </label>
                    <h2 id="exp_date_[[:KEY:]]">0</h2>
                </div>
            </div>
        </div>


        <div class="clear"></div>

        <br/>
        <div class="divider"></div>
        <br/>

    </div>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12 view-not-multidriver" id="main-insurers">


                @foreach($insurers as $key => $insurer)
                    @php
                        $key++
                    @endphp
                    <div class="row form-horizontal insurer_{{$key}}">


                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-6" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Фамилия имя отчество <span class="required">*</span>

                                        <label class="hint--bottom" aria-label='Скопировать страхователя'>
                                            <i class="fa fa-user" aria-hidden="true" style="font-size: 16px;cursor: pointer;color: #00aeef;" onclick="isInsurer({{$key}})" ></i>
                                        </label>

                                        <label class="hint--bottom" aria-label='Скопировать собственника'>
                                            <i class="fa fa-key" aria-hidden="true" style="font-size: 16px;cursor: pointer;color: #00aeef;" onclick="isOwner({{$key}})" ></i>
                                        </label>


                                    </label>
                                    <input type="hidden" name="contract[insurers][{{$key}}][is_insurer]" value="0"/>
                                    <input type="hidden" name="contract[insurers][{{$key}}][is_owner]" value="0"/>
                                    {{ Form::text("contract[insurers][{$key}][title]", $insurer->title, ['class' => 'form-control valid_accept']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Пол <span class="required">*</span>
                                    </label>
                                    {{Form::select("contract[insurers][{$key}][sex]", collect([0=>"муж.", 1=>'жен.']), $insurer->sex, ['class' => 'form-control select2-ws valid_accept', 'data-key'=>"insurers"]) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-2 col-lg-2" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Дата рождения <span class="required">*</span>
                                    </label>
                                    {{ Form::text("contract[insurers][{$key}][birthdate]", setDateTimeFormatRu($insurer->birthdate, 1), ['class' => 'form-control valid_accept format-date', 'onchange'=>"setInsurerYear({$key}, 'birthdate')"]) }}
                                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-2 col-lg-1" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Возраст
                                    </label>
                                    <h2 id="birthdate_{{$key}}">{{$insurer->birthyear}}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-1 col-lg-1 delete-block" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        &nbsp;
                                    </label>
                                    <span class="btn btn-info" onclick="deleteInsured({{$key}})">
                                        <i class="fa fa-close"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="clear"></div>

                        <div class="col-xs-12 col-sm-4 col-md-5 col-lg-2" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Тип В.У.
                                    </label>
                                    {{Form::select("contract[insurers][{$key}][doc_type]", collect([1145 => 'Водительское удостоверение']), $insurer->doc_type, ['class' => 'form-control select2-ws ']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Серия В.У. <span class="required">*</span>
                                    </label>
                                    {{ Form::text("contract[insurers][{$key}][doc_serie]", $insurer->doc_serie, ['class' => "form-control to_up_letters valid_accept"]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Номер В.У. <span class="required">*</span>
                                    </label>
                                    {{ Form::text("contract[insurers][{$key}][doc_number]", $insurer->doc_number, ['class' => "form-control to_up_letters valid_accept"]) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Дата выдачи В.У. <span class="required">*</span>
                                    </label>
                                    {{ Form::text("contract[insurers][{$key}][doc_date]",  setDateTimeFormatRu($insurer->doc_date,1), ['class' => 'form-control valid_accept format-date', 'placeholder' => '18.05.1976']) }}
                                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>

                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-2" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label" style="max-width: none;">
                                        Начало стажа <span class="required">*</span>
                                    </label>
                                    {{ Form::text("contract[insurers][{$key}][exp_date]", setDateTimeFormatRu($insurer->exp_date, 1), ['class' => 'form-control [[:VALID:]] format-date', 'placeholder' => '18.05.1976', 'onchange'=>"setInsurerYear({$key}, 'exp_date')"]) }}
                                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-1" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Стаж
                                    </label>
                                    <h2 id="exp_date_{{$key}}">{{$insurer->expyear}}</h2>
                                </div>
                            </div>
                        </div>


                        <div class="clear"></div>

                        <br/>
                        <div class="divider"></div>
                        <br/>



                    </div>
                @endforeach






            </div>

            <div class="clear"></div>
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12 view-not-multidriver">
                <a href="javascript:void(0);" onclick="addInsured()">
                    <i class="fa fa-plus"></i>
                    Добавить водителя
                </a>
            </div>

            <div class="driver_unlimited row form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12 view-is-multidriver" style="display: none;">


                <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                    <div class="row form-horizontal">

                        @php
                            $calc_data = new stdClass();
                            $calc_data->birthdate_year = '';
                            $calc_data->birthdate_year_l = '';
                            $calc_data->exp_year = '';
                            $calc_data->exp_year_l = '';
                            $calc_data->type_multidriver = 1;
                            if($contract->data->calc_data && strlen($contract->data->calc_data) > 5){
                                $calc_data = json_decode($contract->data->calc_data);
                            }

                        @endphp

                        <div class="col-xs-12 col-sm-4 col-md-6 col-lg-3" >
                            <label class="control-label">Тип</label>
                            {{Form::select("contract[insurers][type_multidriver]", collect([0 => 'Ограниченный список', 1 => 'Мультидрайв']), $calc_data->type_multidriver, ['class' => 'select2-ws clear_offers', "id"=>"type_multidriver", 'onchange'=>"getModelsObjectInsurerCalc();"])}}
                        </div>


                        <div class="form-equally col-xs-12 col-sm-4 col-md-6 col-lg-3 type_multidriver_0 " >

                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
                                <label class="control-label">Возраст (лет) <span class="required">*</span></label>
                                {{ Form::text("contract[insurers][birthdate_year]", $calc_data->birthdate_year, ['class' => 'form-control clear_offers', "id"=>"birthdate_year", "onkeyup"=>"var int = Math.round(".date('Y')."-$('#birthdate_year').val()); if(!isNaN(int)) $('#birthdate_year_l').val(int);"]) }}
                            </div>




                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6" >
                                <label class="control-label">Стаж (лет) <span class="required">*</span></label>
                                {{ Form::text("contract[insurers][exp_year]", $calc_data->exp_year, ['class' => 'form-control clear_offers', "id"=>"exp_year", "onkeyup"=>"var int = Math.round(".date('Y')."-$('#exp_year').val()); if(!isNaN(int)) $('#exp_year_l').val(int);"]) }}
                            </div>



                        </div>

                        <div class="form-equally col-xs-12 col-sm-4 col-md-6 col-lg-3 type_multidriver_0 " >
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 hidden" >
                                <label class="control-label">Стаж (год) <span class="required">*</span></label>
                                {{ Form::text("contract[insurers][exp_year_l]", $calc_data->exp_year_l, ['class' => 'form-control clear_offers', "id"=>"exp_year_l", "onkeyup"=>"var int = Math.round(".date('Y')."-$('#exp_year_l').val()); if(!isNaN(int)) $('#exp_year').val(int);"]) }}

                            </div>

                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 hidden" >
                                <label class="control-label">Возраст (год) <span class="required">*</span></label>
                                {{ Form::text("contract[insurers][birthdate_year_l]", $calc_data->birthdate_year_l, ['class' => 'form-control clear_offers', "id"=>"birthdate_year_l", "onkeyup"=>"var int = Math.round(".date('Y')."-$('#birthdate_year_l').val()); if(!isNaN(int)) $('#birthdate_year').val(int);"]) }}

                            </div>

                        </div>






                    </div>
                </div>



                <div class="clear"></div>



                <h3 class="type_multidriver_1">Договор будет заключен на условиях неограниченного списка лиц, допущенных к управлению</h3>


            </div>


        </div>
    </div>
</div>


<script>

    var INDEXInsurer = '{{sizeof($insurers)}}';



    function initStartInsureds(){

        //$('[data-toggle="tooltip"]').tooltip();

        getModelsObjectInsurerCalc();

        INDEXInsurer = parseInt(INDEXInsurer);

        if(INDEXInsurer == 0){
            addInsured();
        }
        viewMultidriver();

        $('.clear_offers').change(function() {
            $('#offers').html('');
        });

    }

    function isInsurer(key)
    {
        setDataSubject(key, 'insurer');
    }


    function isOwner(key)
    {
        setDataSubject(key, 'owner');
    }

    function setDataSubject(key, name) {

        $('[name="contract[insurers]['+key+'][is_owner]"').val(1);
        $('[name="contract[insurers]['+key+'][is_insurer]"').val(0);
        $('[name="contract[insurers]['+key+'][title]"').val($('#'+name+'_fio').val());
        $('[name="contract[insurers]['+key+'][sex]"').select2('val', $('#'+name+'_sex').val());
        $('[name="contract[insurers]['+key+'][birthdate]"').val($('#'+name+'_birthdate').val());
        setInsurerYear(key, 'birthdate');

        general_subject_id = $('#'+name+'_general_subject_id').val()
        if(parseInt(general_subject_id) > 0){

            doc_type = $('[name="contract[insurers]['+key+'][doc_type]"').val();

            $.getJSON("/contracts/online/{{$contract->id}}/action/get-document-general?document="+doc_type+"&general_id="+general_subject_id, {}, function (response) {

                if(response.state == true){

                    $('[name="contract[insurers]['+key+'][doc_serie]"').val(response.data.serie);
                    $('[name="contract[insurers]['+key+'][doc_number]"').val(response.data.number);
                    $('[name="contract[insurers]['+key+'][doc_date]"').val(response.data.date_issue);
                    $('[name="contract[insurers]['+key+'][exp_date]"').val(response.data.driver_exp_date);

                    setInsurerYear(key, 'exp_date');
                }

            });



        }

    }


    function addInsured()
    {

        String.prototype.replaceAll = function (search, replace) {
            return this.split(search).join(replace);
        }

        INDEXInsurer = parseInt(INDEXInsurer)+1;
        formInsurers = $('#clone-insurer').html().replaceAll('[[:KEY:]]', INDEXInsurer);
        formInsurers = formInsurers.replaceAll('[[:VALID:]]', 'valid_accept');
        formInsurers = formInsurers.replaceAll('[[:SELECT2:]]', 'select2-ws');

        $('#main-insurers').append(formInsurers);
        $('.select2-ws').select2("destroy").select2({
            width: '100%',
            dropdownCssClass: "bigdrop",
            dropdownAutoWidth: true,
            minimumResultsForSearch: -1
        });

        formatDate();
        initTextControll();

        //$('[data-toggle="tooltip"]').tooltip();

        return INDEXInsurer;
    }

    function deleteInsured(key)
    {

        $('.insurer_'+key).remove();
        INDEXInsurer = INDEXInsurer-1;

    }

    function setInsurerYear(key, obj_name)
    {

        obj_data = $('[name="contract[insurers]['+key+']['+obj_name+']"').val();
        if(obj_data && obj_data.length > 0 ) {

            var cur_date_tmp1 = obj_data.split(".");

            objyear = parseInt('{{date("Y")}}')-parseInt(cur_date_tmp1[2]);

            $("#"+obj_name+"_"+key).html(objyear);
        }

        return false;


    }

    
    function viewMultidriver() {
        if($('#is_multidriver').prop('checked')){
            $('.view-not-multidriver').hide();
            $('.view-is-multidriver').show();
            $('#main-insurers').html('');
            INDEXInsurer = 0;
        }else{
            $('.view-is-multidriver').hide();
            $('.view-not-multidriver').show();
            if(INDEXInsurer == 0){
                addInsured();
            }

        }
    }



    function getModelsObjectInsurerCalc() {

        if($('#type_multidriver').val() == 0){
            $('.type_multidriver_0').show();
            $('.type_multidriver_1').hide();
            $('.type_multidriver_0').find('input').addClass('valid_accept');
        }else{
            $('.type_multidriver_1').show();
            $('.type_multidriver_0').hide();
            $('.type_multidriver_0').find('input').removeClass('valid_accept');
        }

    }



</script>