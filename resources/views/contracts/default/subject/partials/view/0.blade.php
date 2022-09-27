
<div class="view-field">
    <span class="view-label">ФИО</span>
    <span class="view-value">{{$subject->fio}} ({{($subject->sex == 0)?'муж.':'жен.'}})</span>
</div>

@if(strlen($subject->fio_lat) > 0)
    <div class="view-field">
        <span class="view-label">ФИО лат.</span>
        <span class="view-value">{{$subject->fio_lat}}</span>
    </div>
@endif

<div class="view-field">
    <span class="view-label">Дата рождения</span>
    <span class="view-value">{{getDateFormatRu($subject->birthdate)}}</span>
</div>


<div class="view-field">
    <span class="view-label">{{\App\Models\Contracts\SubjectsFlDocType::getDocTypeTitle($subject->doc_type)}}</span>
    <span class="view-value">{{$subject->doc_serie}} {{$subject->doc_number}}</span>
</div>


<div class="view-field">
    <span class="view-label">Дата выдачи </span>
    <span class="view-value">{{setDateTimeFormatRu($subject->doc_date, 1)}}</span>
</div>

<div class="view-field">
    <span class="view-label">Код подразделения </span>
    <span class="view-value">{{$subject->doc_office}}</span>
</div>

<div class="view-field">
    <span class="view-label">Кем выдан </span>
    <span class="view-value">{{$subject->doc_info}}</span>
</div>

<div class="view-field">
    <span class="view-label">Место рождения</span>
    <span class="view-value">{{$subject->address_born}}</span>
</div>

<div class="view-field">
    <span class="view-label">Адрес регистрации</span>
    <span class="view-value">{{$subject->address_register}}</span>
</div>

<div class="view-field">
    <span class="view-label">Адрес фактический</span>
    <span class="view-value">{{$subject->address_fact}}</span>
</div>

<div class="view-field">
    <span class="view-label">Телефон</span>
    <span class="view-value">{{$subject_original->phone}}</span>
</div>

<div class="view-field">
    <span class="view-label">Email</span>
    <span class="view-value">{{$subject_original->email}}</span>
</div>
