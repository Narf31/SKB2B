<div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">

    <div class="row col-sm-12">
        <label class="col-sm-12 control-label" style="max-width: none;">Цели финансово-хозяйственной деятельности клиента</label>
        <div class="col-sm-12">
            {{Form::text("financial_business_objectives", $general->podft->financial_business_objectives, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="row col-sm-12">
        <label class="col-sm-12 control-label" style="max-width: none;">Сведения о деловой репутации клиента</label>
        <div class="col-sm-12">
            {{Form::text("information_business_reputation", $general->podft->information_business_reputation, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="row col-sm-12">
        <label class="col-sm-12 control-label" style="max-width: none;">Предполагаемый характер отношений</label>
        <div class="col-sm-12">
            {{Form::text("alleged_nature_relationship", $general->podft->alleged_nature_relationship, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="row col-sm-12">
        <label class="col-sm-12 control-label" style="max-width: none;">Происхождение ДС и (или) иного имущества</label>
        <div class="col-sm-12">
            {{Form::text("origin_ds_other_property", $general->podft->origin_ds_other_property, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="row col-sm-12">
        <label class="col-sm-12 control-label" style="max-width: none;">Цель установления отношений</label>
        <div class="col-sm-12">
            {{Form::text("purpose_establishing_relationship", $general->podft->purpose_establishing_relationship, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="row col-sm-12">
        <label class="col-sm-12 control-label" style="max-width: none;">Финансовое положение клиента</label>
        <div class="col-sm-12">
            {{Form::text("financial_position", $general->podft->financial_position, ['class' => 'form-control']) }}
        </div>
    </div>


    <div class="row col-sm-12">

    @include("general.subjects.info.fl.podft.subscription_lists", ['lists' => ((strlen($general->json_data) > 5)?json_decode($general->json_data):null)])

    </div>


</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

    <div class="col-sm-12">
        <label class="col-sm-10 control-label" style="max-width: none;">Является исполнителем по государственному или муниципальным</label>
        <div class="col-sm-2">
            <input @if($general->podft->is_executor_state_municipal == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="is_executor_state_municipal" value="1" type="checkbox">
        </div>
    </div>

    <div class="col-sm-12">
        <label class="col-sm-10 control-label" style="max-width: none;">Является получателем грантов</label>
        <div class="col-sm-2">
            <input @if($general->podft->is_recipient_grants == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="is_recipient_grants" value="1" type="checkbox">
        </div>
    </div>

    <div class="col-sm-12">
        <label class="col-sm-10 control-label" style="max-width: none;">Является участником целевых программ или национальных</label>
        <div class="col-sm-2">
            <input @if($general->podft->is_participant_targeted_programs_national == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="is_participant_targeted_programs_national" value="1" type="checkbox">
        </div>
    </div>

    <div class="col-sm-12">
        <label class="col-sm-10 control-label" style="max-width: none;">Является получателем государственной поддержки</label>
        <div class="col-sm-2">
            <input @if($general->podft->is_recipient_state_support == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="is_recipient_state_support" value="1" type="checkbox">
        </div>
    </div>

    <div class="col-sm-12">
        <label class="col-sm-4 control-label">Основной вид занятости</label>
        <div class="col-sm-8">
            {{Form::select("main_type_employment_id", collect(\App\Models\Clients\GeneralPodftFl::MAIN_TYPE_EMPLOYMENT), $general->podft->main_type_employment_id, ['class' => 'form-control select2-ws', 'id'=>'main_type_employment_id', 'onchange'=>'viewMainType()']) }}
        </div>
    </div>


    <div class="col-sm-12 main_type main_type_5">
        <label class="col-sm-4 control-label">Основной вид занятости</label>
        <div class="col-sm-8">
            {{Form::text("main_type_employment_text", $general->podft->main_type_employment_text, ['class' => 'form-control']) }}
        </div>
    </div>



    <div class="col-sm-12 main_type main_type_2 main_type_1">
        <label class="col-sm-4 control-label">
            <a href="{{url("/general/subjects/edit/{$general->podft->general_organization_id}")}}">Контрагент (Организация)</a>
        </label>
        <div class="col-sm-8">


            {{Form::text("general_organization_title", ($general->podft->general_organization?$general->podft->general_organization->title:''), ['class' => 'form-control searchGeneralOrganization', 'data-set-id'=>"general_organization_id"]) }}
            <input type="hidden" name="general_organization_id" id="general_organization_id" value="{{$general->podft->general_organization_id}}"/>


        </div>
    </div>

    <div class="col-sm-12 main_type main_type_2 main_type_1">
        <label class="col-sm-4 control-label">Отдел | Подразделение</label>
        <div class="col-sm-8">
            {{Form::text("job_department_subdivision", $general->podft->job_department_subdivision, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="col-sm-12 main_type main_type_2 main_type_1">
        <label class="col-sm-4 control-label">Рабочий тел.</label>
        <div class="col-sm-8">
            {{Form::text("job_phone", $general->podft->job_phone, ['class' => 'form-control phone']) }}
        </div>
    </div>

    <div class="col-sm-12 main_type main_type_2 main_type_1">
        <label class="col-sm-4 control-label">Должность</label>
        <div class="col-sm-8">
            {{Form::text("job_position", $general->podft->job_position, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="col-sm-12 main_type main_type_2 main_type_1">
        <label class="col-sm-4 control-label">Полномочия</label>
        <div class="col-sm-8">
            {{Form::select("job_credentials_id", collect(\App\Models\Clients\GeneralPodftFl::JOB_CREDENTIALS), $general->podft->job_credentials_id, ['class' => 'form-control select2-ws']) }}
        </div>
    </div>

    <div class="col-sm-12 main_type main_type_2 main_type_1">
        <label class="col-sm-4 control-label">Вид деят-ти</label>
        <div class="col-sm-8">
            {{Form::select("job_type_activity_id", collect(\App\Models\Clients\GeneralPodftFl::JOB_TYPE_ACTIVITY), $general->podft->job_type_activity_id, ['class' => 'form-control select2-ws']) }}
        </div>
    </div>

</div>




<script src="/js/jquery.easyui.min.js"></script>
<link rel="stylesheet" type="text/css" href="/css/themes/material-teal/easyui.css">

<script>
    
    function initDataSubjects() {

        $('.phone').mask('+7 (999) 999-99-99');
        viewMainType();

        searchGeneralOrganization();


    }

    function viewMainType()
    {
        $(".main_type").hide();
        $(".main_type_"+$("#main_type_employment_id").val()).show();

    }




</script>