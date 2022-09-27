
<div class="page-heading" data-intro='Если застрахованный является страхователем нажмите для автоматической подстановки'>
    <h2 class="inline-h1">Застрахованный
        <i class="fa fa-user" style="font-size: 16px;cursor: pointer;color: rgb(234, 137, 58);" onclick="isInsurer()" ></i>
    </h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

                    <div class="col-md-6 col-lg-4" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    ФИО <span class="required">*</span>
                                </label>
                                {{ Form::text("contract[insurers][title]", $insurer->title, ['class' => 'form-control valid_accept', 'id'=>"insurers_fio", 'data-key'=>"insurers", 'placeholder' => '']) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Пол <span class="required">*</span>
                                </label>
                                {{Form::select("contract[insurers][sex]", collect([0=>"муж.", 1=>'жен.']), $insurer->sex, ['class' => 'form-control  select2-ws valid_accept', 'id' => "insurers_sex", 'data-key'=>"insurers"]) }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-2" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Дата рождения <span class="required">*</span>
                                </label>
                                {{ Form::text("contract[insurers][birthdate]", setDateTimeFormatRu($insurer->birthdate, 1), ['class' => 'form-control valid_accept format-date end-date', 'id'=>"insurers_birthdate", 'placeholder' => '18.05.1976']) }}
                                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
                            </div>
                        </div>
                    </div>

                    <div class="clear"></div>
                </div>

            </div>
        </div>
    </div>
</div>


<script>


    function initStartInsureds(){

        $('#insurers_fio').suggestions({
            serviceUrl: '{{url("/suggestions/dadata/")}}',
            token: "",
            type: "NAME",
            count: 5,
            onSelect: function (suggestion) {

            }
        });
    }

    function isInsurer()
    {
        $('#insurers_fio').val($('#insurer_fio').val());
        $('#insurers_sex').select2('val', $('#insurer_sex').val());
        $('#insurers_birthdate').val($('#insurer_birthdate').val());
    }



</script>