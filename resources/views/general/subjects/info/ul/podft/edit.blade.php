<div class="row col-xs-12 col-sm-6 col-md-6 col-lg-6">

    <div class="row col-sm-12">
        <label class="col-sm-12 control-label" style="max-width: none;">Цель установления отношений</label>
        <div class="col-sm-12">
            {{Form::text("purpose_establishing_relationship", $general->podft->purpose_establishing_relationship, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="row col-sm-12">
        <label class="col-sm-12 control-label" style="max-width: none;">Цели финансово-хозяйственной деятельности клиента</label>
        <div class="col-sm-12">
            {{Form::text("financial_business_objectives", $general->podft->financial_business_objectives, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="row col-sm-12">
        <label class="col-sm-12 control-label" style="max-width: none;">Финансовое положение клиента</label>
        <div class="col-sm-12">
            {{Form::text("financial_position", $general->podft->financial_position, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="row col-sm-12">
        <label class="col-sm-12 control-label" style="max-width: none;">Сведения о деловой репутации клиента</label>
        <div class="col-sm-12">
            {{Form::text("information_business_reputation", $general->podft->information_business_reputation, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="row col-sm-12">
        <label class="col-sm-12 control-label" style="max-width: none;">В чьих интересах действует</label>
        <div class="col-sm-12">
            {{Form::select("in_whose_interests_id", collect(\App\Models\Clients\GeneralPodftUl::IN_WHOSE_INTERESTS), $general->podft->in_whose_interests_id, ['class' => 'form-control']) }}
        </div>
    </div>









</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">


    <div class="col-sm-12">
        <label class="col-sm-10 control-label" style="max-width: none;">Клиент является получателем грантов или иных видов</label>
        <div class="col-sm-2">
            <input @if($general->podft->is_recipient_grants == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="is_recipient_grants" value="1" type="checkbox">
        </div>
    </div>

    <div class="col-sm-12">
        <label class="col-sm-10 control-label" style="max-width: none;">Клиент имеет контракты с бюджетным учреждением</label>
        <div class="col-sm-2">
            <input @if($general->podft->is_budgetary_institution == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="is_budgetary_institution" value="1" type="checkbox">
        </div>
    </div>

    <div class="col-sm-12">
        <label class="col-sm-10 control-label" style="max-width: none;">Клиент учредитель</label>
        <div class="col-sm-2">
            <input @if($general->podft->is_founder == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="is_founder" value="1" type="checkbox">
        </div>
    </div>

    <div class="col-sm-12">
        <label class="col-sm-10 control-label" style="max-width: none;">Клиент выгодоприобретатель</label>
        <div class="col-sm-2">
            <input @if($general->podft->is_beneficiary == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="is_beneficiary" value="1" type="checkbox">
        </div>
    </div>

    <div class="col-sm-12">
        <label class="col-sm-10 control-label" style="max-width: none;">Документы переданы на бумажном носителе</label>
        <div class="col-sm-2">
            <input @if($general->podft->is_documents_submitted_paper == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="is_documents_submitted_paper" value="1" type="checkbox">
        </div>
    </div>






</div>




<script src="/js/jquery.easyui.min.js"></script>
<link rel="stylesheet" type="text/css" href="/css/themes/material-teal/easyui.css">

<script>
    
    function initDataSubjects() {



    }





</script>