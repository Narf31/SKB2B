<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">




    <div class="view-field">
        <span class="view-label">Организационная форма</span>
        <span class="view-value">{{($general->data->of)?$general->data->of->full_title:''}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Краткое название</span>
        <span class="view-value">{{$general->title}}</span>
    </div>


    <div class="view-field">
        <span class="view-label">Полное название</span>
        <span class="view-value">{{$general->data->full_title}}</span>
    </div>


    <div class="view-field">
        <span class="view-label">Полное наименование на ин.языке</span>
        <span class="view-value">{{$general->data->full_title_en}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Форма собственности (ОКФС)</span>
        <span class="view-value">{{\App\Models\Clients\GeneralSubjectsUl::OWNERSHIP[$general->data->ownership_id]}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Полное наименование на ин.языке</span>
        <span class="view-value">{{$general->data->full_title_en}}</span>
    </div>


    <div class="view-field">
        <span class="view-label">Гражданство</span>
        <span class="view-value">{{($general->citizenship)?$general->citizenship->title:''}}</span>
    </div>


    <div class="view-field">
        <span class="view-label">ИНН</span>
        <span class="view-value">{{$general->data->inn}}</span>
    </div>
    <div class="view-field">
        <span class="view-label">КПП</span>
        <span class="view-value">{{$general->data->kpp}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">ОГРН</span>
        <span class="view-value">{{$general->data->ogrn}}</span>
    </div>


    <div class="view-field">
        <span class="view-label">Дата присвоения ОГРН</span>
        <span class="view-value">{{setDateTimeFormatRu($general->data->date_orgn, 1)}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Наименование рег. органа</span>
        <span class="view-value">{{$general->data->issued}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Место гос. регистрации</span>
        <span class="view-value">{{$general->data->place_registration}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Банк</span>
        <span class="view-value">{{($general->data->bank)?$general->data->bank->title:''}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">БИК</span>
        <span class="view-value">{{$general->data->bik}}</span>
    </div>


    <div class="view-field">
        <span class="view-label">№ расчетного счета</span>
        <span class="view-value">{{$general->data->rs}}</span>
    </div>


    <div class="view-field">
        <span class="view-label">№ к/с</span>
        <span class="view-value">{{$general->data->ks}}</span>
    </div>


    <div class="view-field">
        <span class="view-label">Адрес регистрации</span>
        <span class="view-value">{{$general->getAddressType(1)->address}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Адрес фактический</span>
        <span class="view-value">{{$general->getAddressType(2)->address}}</span>
    </div>



    @include("general.subjects.info.documents.view", [
        'docs' => \App\Models\Contracts\Subjects::DOC_TYPE_UL,
        'index' => 0,
        'doc' => $general->getDocumentsType(0),
    ])


</div>

<div class="clear"></div>





