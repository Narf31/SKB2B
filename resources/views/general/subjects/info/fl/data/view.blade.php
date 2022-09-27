<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-left: 5px;">

    <div class="view-field">
        <span class="view-label">Пол</span>
        <span class="view-value"> {{[0=>"муж.", 1=>'жен.'][$general->data->sex]}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">ИНН</span>
        <span class="view-value">{{$general->inn}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">СНИЛС</span>
        <span class="view-value">{{$general->data->snils}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Гражданство</span>
        <span class="view-value">{{$general->citizenship->title}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Место рождения</span>
        <span class="view-value">{{$general->getAddressType(0)->address}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Адрес регистрации</span>
        <span class="view-value">{{$general->getAddressType(1)->address}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">Адрес фактический</span>
        <span class="view-value">{{$general->getAddressType(2)->address}}</span>
    </div>


    <div class="view-field">
        <span class="view-label">Моб. телефон</span>
        <span class="view-value">{{$general->phone}}</span>
    </div>

    <div class="view-field">
        <span class="view-label">E-mail</span>
        <span class="view-value">{{$general->email}}</span>
    </div>

    @include("general.subjects.info.documents.view", [
        'docs' => collect(\App\Models\Contracts\SubjectsFlDocType::getDocType()->pluck('title', 'isn')),
        '_pref' => '(Основной)',
        'is_main' => 1,
        'index' => 0,
        'doc' => $general->getDocumentsType(0),
    ])


</div>

<div class="clear"></div>





