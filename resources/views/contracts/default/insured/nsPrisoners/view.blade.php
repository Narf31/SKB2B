
<div class="row form-horizontal" >
    <h2 class="inline-h1">Застрахованный</h2>
    <br/><br/>

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="view-field">
            <span class="view-label">ФИО</span>
            <span class="view-value">{{$insurer->title}} ({{collect([0=>"муж.", 1=>'жен.'])[$insurer->sex]}})</span>
        </div>
        <div class="view-field">
            <span class="view-label">Дата рождения</span>
            <span class="view-value">{{setDateTimeFormatRu($insurer->birthdate, 1)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Гражданство</span>
            <span class="view-value">{{$insurer->citizenship->title}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Место рождения</span>
            <span class="view-value">{{$contract->data->address_born}}</span>
        </div>


        <div class="view-field">
            <span class="view-label">Телефон</span>
            <span class="view-value">{{$insurer->phone}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Email</span>
            <span class="view-value">{{$insurer->email}}</span>
        </div>


        <div class="view-field">
            <span class="view-label">{{\App\Models\Contracts\ContractsInsurer::DOC_TYPE[0]}}</span>
            <span class="view-value">{{$insurer->doc_serie}} {{$insurer->doc_number}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Дата выдачи</span>
            <span class="view-value">{{setDateTimeFormatRu($insurer->doc_date, 1)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Кем выдан</span>
            <span class="view-value">{{$insurer->doc_info}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Инкриминируемое деяние (осужден) по следующим статьям Уголовного Кодекса РФ</span>
            <span class="view-value">{{$contract->data->convicted_under_articles}}</span>
        </div>


        <div class="view-field">
            <span class="view-label">Срок, на который осужден</span>
            <span class="view-value">{{$contract->data->convicted_term}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Срок содержания Застрахованного лица</span>
            <span class="view-value">{{\App\Models\Directories\Products\Data\NSPrisoners::CONVICTED_TERM_CONTRSCT[$contract->data->convicted_term_contract]}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Адрес местонахождения</span>
            <span class="view-value">{{$contract->data->address_location}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Наличие хронических заболеваний</span>
            <span class="view-value">@if((int)$contract->data->is_chronic_diseases == 1) {{$contract->data->chronic_diseases}} @else Нет @endif</span>
        </div>

        <div class="view-field">
            <span class="view-label">Наличие инвалидности</span>
            <span class="view-value">@if((int)$contract->data->is_disabilities == 1) {{$contract->data->disabilities}} @else Нет @endif</span>
        </div>


        <div class="clear"></div>
    </div>
</div>





<script>


    function initStartInsureds(){


    }




</script>