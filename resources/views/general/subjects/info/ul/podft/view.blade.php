<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">



    <div class="view-field">
        <span class="view-label">Цель установления отношений</span>
        <span class="view-value">{{$general->podft->purpose_establishing_relationship}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Цели финансово-хозяйственной деятельности клиента</span>
        <span class="view-value">{{$general->podft->financial_business_objectives}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Финансовое положение клиента</span>
        <span class="view-value">{{$general->podft->financial_position}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Сведения о деловой репутации клиента</span>
        <span class="view-value">{{$general->podft->information_business_reputationy}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">В чьих интересах действует</span>
        <span class="view-value">{{\App\Models\Clients\GeneralPodftUl::IN_WHOSE_INTERESTS[$general->podft->in_whose_interests_id]}}</span>
    </div>




</div>

<div class="row col-xs-12 col-sm-4 col-md-4 col-lg-4">




    <div class="view-field">
        <span class="view-label">Клиент является получателем грантов или иных видов</span>
        <span class="view-value">{{($general->podft->is_recipient_grants == 1)?"Да":"Нет"}}</span>
    </div>
    <div class="view-field">
        <span class="view-label">Клиент имеет контракты с бюджетным учреждением</span>
        <span class="view-value">{{($general->podft->is_budgetary_institution == 1)?"Да":"Нет"}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Клиент учредитель</span>
        <span class="view-value">{{($general->podft->is_founder == 1)?"Да":"Нет"}}</span>
    </div>
    <div class="view-field">
        <span class="view-label">Клиент выгодоприобретатель</span>
        <span class="view-value">{{($general->podft->is_beneficiary == 1)?"Да":"Нет"}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Документы переданы на бумажном носителе</span>
        <span class="view-value">{{($general->podft->is_documents_submitted_paper == 1)?"Да":"Нет"}}</span>
    </div>




</div>

<div class="clear"></div>





