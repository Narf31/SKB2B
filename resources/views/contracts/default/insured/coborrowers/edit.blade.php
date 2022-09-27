
<div class="page-heading">
    <h2 class="inline-h1">Созаемщик</h2>
</div>

<div id="clone-insurer" class="hidden">
    <div class="row form-horizontal insurer_[[:KEY:]]">


        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-2" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Доля %<span class="required">*</span>
                    </label>
                    {{ Form::text("contract[insurers][[[:KEY:]]][share]", '', ['class' => 'form-control sum [[:VALID:]]']) }}
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        ФИО <span class="required">*</span>
                    </label>
                    {{ Form::text("contract[insurers][[[:KEY:]]][title]", '', ['class' => 'form-control [[:VALID:]]', 'data-key'=>"insurers", 'placeholder' => '']) }}
                </div>
            </div>
        </div>



        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Пол <span class="required">*</span>
                    </label>
                    {{Form::select("contract[insurers][[[:KEY:]]][sex]", collect([0=>"муж.", 1=>'жен.']), 1, ['class' => 'form-control [[:SELECT2:]] ', 'data-key'=>"insurers"]) }}
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Дата рождения <span class="required">*</span>
                    </label>
                    {{ Form::text("contract[insurers][[[:KEY:]]][birthdate]", '', ['class' => 'form-control [[:VALID:]] format-date', 'placeholder' => '18.05.1976','onchange'=>'setInsurerYear([[:KEY:]])']) }}
                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-1" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Возраст
                    </label>
                    <h2 id="birthyear_[[:KEY:]]"></h2>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-1" >
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
    </div>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="main-insurers">


                @foreach($insurers as $key => $insurer)
                    @php
                        $key++
                    @endphp
                    <div class="row form-horizontal insurer_{{$key}}">



                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-2" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Доля %<span class="required">*</span>
                                    </label>
                                    {{ Form::text("contract[insurers][{$key}][share]", $insurer->share, ['class' => 'form-control sum valid_accept']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-4" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        ФИО <span class="required">*</span>
                                    </label>
                                    {{ Form::text("contract[insurers][{$key}][title]", $insurer->title, ['class' => 'form-control valid_accept']) }}
                                </div>
                            </div>
                        </div>


                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Пол <span class="required">*</span>
                                    </label>
                                    {{Form::select("contract[insurers][{$key}][sex]", collect([0=>"муж.", 1=>'жен.']), $insurer->sex, ['class' => 'form-control  select2-ws valid_accept']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-2" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Дата рождения <span class="required">*</span>
                                    </label>
                                    {{ Form::text("contract[insurers][{$key}][birthdate]", setDateTimeFormatRu($insurer->birthdate, 1), ['class' => 'form-control valid_accept format-date', 'onchange'=>"setInsurerYear({$key})"]) }}
                                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-1" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Возраст
                                    </label>
                                    <h2 id="birthyear_{{$key}}">{{$insurer->birthyear}}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-3 col-md-3 col-lg-1" >
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
                    </div>
                @endforeach






            </div>

            <div class="clear"></div>
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <a href="javascript:void(0);" onclick="addInsured()">
                    <i class="fa fa-plus"></i>
                    Добавить созаемщика
                </a>
            </div>



        </div>
    </div>
</div>


<script>

    var INDEXInsurer = '{{sizeof($insurers)}}';

    function initStartInsureds(){
        INDEXInsurer = parseInt(INDEXInsurer);


    }

    function isInsurer()
    {

        if(INDEXInsurer > 1){
            key = addInsured();
        }else{
            key = INDEXInsurer;
        }

        $('[name="contract[insurers]['+key+'][title]"').val($('#insurer_fio').val());
        $('[name="contract[insurers]['+key+'][sex]"').select2('val', $('#insurer_sex').val());
        $('[name="contract[insurers]['+key+'][birthdate]"').val($('#insurer_birthdate').val());
        setInsurerYear(key);
    }

    function addInsured()
    {

        INDEXInsurer = INDEXInsurer+1;
        formInsurers = $('#clone-insurer').html().replaceAll('[[:KEY:]]', INDEXInsurer);
        formInsurers = formInsurers.replaceAll('[[:VALID:]]', 'valid_accept');
        formInsurers = formInsurers.replaceAll('[[:SELECT2:]]', 'select2-ws');
        formInsurers = formInsurers.replaceAll('[[:SELECT2-ALL:]]', 'select2-all');

        $('#main-insurers').append(formInsurers);
        initSelect2();
        formatDate();


        return INDEXInsurer;
    }

    function deleteInsured(key)
    {

        $('.insurer_'+key).remove();
        INDEXInsurer = INDEXInsurer-1;

    }

    function setInsurerYear(key)
    {

        birthdate = $('[name="contract[insurers]['+key+'][birthdate]"').val();
        if(birthdate.length <=0 ) return '';
        var cur_date_tmp1 = birthdate.split(".");

        birthyear = parseInt('{{date("Y")}}')-parseInt(cur_date_tmp1[2]);

        $("#birthyear_"+key).html(birthyear);

    }




</script>