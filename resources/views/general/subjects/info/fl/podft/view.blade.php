<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">


    <div class="view-field">
        <span class="view-label">Цели финансово-хозяйственной деятельности клиента</span>
        <span class="view-value">{{$general->podft->financial_business_objectives}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Сведения о деловой репутации клиента</span>
        <span class="view-value">{{$general->podft->information_business_reputation}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Предполагаемый характер отношений</span>
        <span class="view-value">{{$general->podft->alleged_nature_relationship}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Происхождение ДС и (или) иного имущества</span>
        <span class="view-value">{{$general->podft->origin_ds_other_property}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Цель установления отношений</span>
        <span class="view-value">{{$general->podft->purpose_establishing_relationships}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Финансовое положение клиента</span>
        <span class="view-value">{{$general->podft->financial_position}}</span>
    </div>

    @include("general.subjects.info.fl.podft.subscription_lists", ['lists' => ((strlen($general->json_data) > 5)?json_decode($general->json_data):null)])


</div>

<div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">


    <div class="view-field">
        <span class="view-label">Является исполнителем по государственному или муниципальным</span>
        <span class="view-value">{{($general->podft->is_executor_state_municipal == 1)?"Да":"Нет"}}</span>
    </div>
    <div class="view-field">
        <span class="view-label">Является получателем грантов</span>
        <span class="view-value">{{($general->podft->is_recipient_grants == 1)?"Да":"Нет"}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Является участником целевых программ или национальных</span>
        <span class="view-value">{{($general->podft->is_participant_targeted_programs_national == 1)?"Да":"Нет"}}</span>
    </div>
    <div class="view-field">
        <span class="view-label">Является получателем государственной поддержки</span>
        <span class="view-value">{{($general->podft->is_recipient_state_support == 1)?"Да":"Нет"}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Основной вид занятости</span>
        <span class="view-value">{{\App\Models\Clients\GeneralPodftFl::MAIN_TYPE_EMPLOYMENT[$general->podft->main_type_employment_id]}}</span>
    </div>

    @if($general->podft->main_type_employment_id == 5)
        <div class="view-field">
            <span class="view-label">Основной вид занятости</span>
            <span class="view-value">{{$general->podft->main_type_employment_text}}</span>
        </div>
    @endif

    @if($general->podft->main_type_employment_id == 1 || $general->podft->main_type_employment_id == 2)
        <div class="view-field">
            <span class="view-label">Контрагент (Организация)</span>
            <span class="view-value"><a href="{{url("/general/subjects/edit/{$general->podft->general_organization_id}")}}">{{$general->podft->general_organization->title}}</a></span>
        </div>
    @endif


    @if($general->podft->main_type_employment_id == 1 || $general->podft->main_type_employment_id == 2)


        <div class="view-field">
            <span class="view-label">Отдел | Подразделение</span>
            <span class="view-value">{{$general->podft->job_department_subdivision}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Рабочий тел.</span>
            <span class="view-value">{{$general->podft->job_phone}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Должность</span>
            <span class="view-value">{{$general->podft->job_position}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Полномочия</span>
            <span class="view-value">{{\App\Models\Clients\GeneralPodftFl::JOB_CREDENTIALS[$general->podft->job_credentials_id]}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Вид деят-ти</span>
            <span class="view-value">{{\App\Models\Clients\GeneralPodftFl::JOB_TYPE_ACTIVITY[$general->podft->job_type_activity_id]}}</span>
        </div>


    @endif


</div>

<div class="clear"></div>





