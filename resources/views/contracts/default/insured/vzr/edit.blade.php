
<div class="page-heading" data-intro='Если застрахованный является страхователем нажмите для автоматической подстановки'>
    <h2 class="inline-h1">Застрахованный
        <i class="fa fa-user" style="font-size: 16px;cursor: pointer;color: rgb(234, 137, 58);" onclick="isInsurer()" ></i>
        <i class="fa fa-file-excel-o" style="font-size: 16px;cursor: pointer;color: rgb(60, 178, 25);" onclick="openFancyBoxFrame('{{url("/contracts/online/{$contract->id}/load/xls/vzr")}}')" ></i>
    </h2>
</div>

<div id="clone-insurer" class="hidden">
    <div class="row form-horizontal insurer_[[:KEY:]]">
        <div class="col-md-5 col-lg-6" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        ФИО лат. <span class="required">*</span>
                    </label>
                    {{ Form::text("contract[insurers][[[:KEY:]]][title_lat]", '', ['class' => 'form-control [[:VALID:]]', 'data-key'=>"insurers", 'placeholder' => '']) }}
                </div>
            </div>
        </div>

        <div class="col-md-2 col-lg-2" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Пол <span class="required">*</span>
                    </label>
                    {{Form::select("contract[insurers][[[:KEY:]]][sex]", collect([0=>"муж.", 1=>'жен.']), 1, ['class' => 'form-control [[:SELECT2:]] valid_accept', 'data-key'=>"insurers"]) }}
                </div>
            </div>
        </div>

        <div class="col-md-2 col-lg-2" >
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

        <div class="col-md-2 col-lg-1" >
            <div class="field form-col">
                <div>
                    <label class="control-label">
                        Возраст
                    </label>
                    <h2 id="birthyear_[[:KEY:]]"></h2>
                </div>
            </div>
        </div>

        <div class="col-md-1 col-lg-1" >
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


                        <div class="col-md-5 col-lg-6" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        ФИО лат. <span class="required">*</span>
                                    </label>
                                    {{ Form::text("contract[insurers][{$key}][title_lat]", $insurer->title_lat, ['class' => 'form-control valid_accept']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-lg-2" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Пол <span class="required">*</span>
                                    </label>
                                    {{Form::select("contract[insurers][{$key}][sex]", collect([0=>"муж.", 1=>'жен.']), $insurer->sex, ['class' => 'form-control  select2-ws valid_accept']) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-lg-2" >
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

                        <div class="col-md-2 col-lg-1" >
                            <div class="field form-col">
                                <div>
                                    <label class="control-label">
                                        Возраст
                                    </label>
                                    <h2 id="birthyear_{{$key}}">{{$insurer->birthyear}}</h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-1 col-lg-1" >
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
                    Добавить застрахованного
                </a>
            </div>



        </div>
    </div>
</div>


<script>

    var INDEXInsurer = '{{sizeof($insurers)}}';

    function initStartInsureds(){
        INDEXInsurer = parseInt(INDEXInsurer);

        if(INDEXInsurer == 0){
            addInsured();
        }
    }

    function isInsurer()
    {

        if(INDEXInsurer > 1){
            key = addInsured();
        }else{
            key = INDEXInsurer;
        }

        $('[name="contract[insurers]['+key+'][title_lat]"').val($('#insurer_fio_lat').val());
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

        $('#main-insurers').append(formInsurers);
        $('.select2-ws').select2("destroy").select2({
            width: '100%',
            dropdownCssClass: "bigdrop",
            dropdownAutoWidth: true,
            minimumResultsForSearch: -1
        });

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