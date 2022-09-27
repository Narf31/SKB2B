
<div class="view-field">
    <span class="view-label">Статус сотрудничества</span>
    <span class="view-value">{{\App\Models\Clients\GeneralSubjects::STATUS_WORK[$general->status_work_id]}}</span>
</div>

<div class="view-field">
    <span class="view-label">Полное название</span>
    <span class="view-value">{{($general->data->full_title)?$general->data->full_title:''}}</span>
</div>


<div class="view-field">
    <span class="view-label">ОКВЭД</span>
    <span class="view-value">{{$general->data->okved_code}} {{$general->data->okved_title}}</span>
</div>


<div class="view-field">
    <span class="view-label">КПП</span>
    <span class="view-value">{{$general->data->kpp}}</span>
</div>


<div class="view-field">
    <span class="view-label">Адрес</span>
    <span class="view-value">{{$general->getAddressType(1)->address}}</span>
</div>


{{--

<div class="view-field">
    <span class="view-label">ОГРН</span>
    <span class="view-value">{{$general->data->ogrn}}</span>
</div>

<div class="view-field">
    <span class="view-label">ИНН</span>
    <span class="view-value">{{$general->data->inn}}</span>
</div>
<div class="view-field">
    <span class="view-label">Адрес фактический</span>
    <span class="view-value">{{$general->getAddressType(2)->address}}</span>
</div>

<h2 style="margin-bottom: 15px;">Свидетельство о регистрации</h2>

@php
    $gdoc = $general->getDocumentsType(1169);
@endphp
<div class="row">
    <div class="col-md-4 col-lg-4" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Серия <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][document][serie]", $gdoc->serie, ['class' => 'form-control valid_accept', 'placeholder' => '1234']) }}
            </div>
        </div>
    </div>


    <div class="col-md-4 col-lg-4" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Номер <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][document][number]", $gdoc->number, ['class' => 'form-control valid_accept', 'placeholder' => '567890']) }}
            </div>
        </div>
    </div>


    <div class="col-md-4 col-lg-4" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Дата выдачи <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][document][date_issue]", setDateTimeFormatRu($gdoc->date_issue, 1), ['class' => 'form-control valid_accept format-date ', 'placeholder' => '12.05.2006']) }}
                <span class="glyphicon glyphicon-calendar calendar-icon"></span>
            </div>
        </div>
    </div>


    <div class="clear"></div>


    <div class="col-lg-8" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Кем выдан <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][document][issued]", $gdoc->issued, ['class' => 'form-control valid_accept', 'placeholder' => 'РУВД Москвы', 'id' => "{$subject_name}_doc_info"]) }}
            </div>
        </div>
    </div>


    <div class="col-lg-4" >
        <div class="field form-col">
            <div>
                <label class="control-label">
                    Код подразделения <span class="required">*</span>
                </label>
                {{ Form::text("contract[{$subject_name}][document][unit_code]", $gdoc->unit_code, ['class' => 'form-control']) }}
            </div>
        </div>
    </div>
</div>

--}}