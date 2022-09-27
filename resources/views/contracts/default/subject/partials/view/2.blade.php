
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
    <span class="view-label">{{\App\Models\Contracts\Subjects::DOC_TYPE[$subject->doc_type]}}</span>
    <span class="view-value">{{$subject->doc_serie}} {{$subject->doc_number}}</span>
</div>


<div class="view-field">
    <span class="view-label">Телефон</span>
    <span class="view-value">{{$subject_original->phone}}</span>
</div>

<div class="view-field">
    <span class="view-label">Email</span>
    <span class="view-value">{{$subject_original->email}}</span>
</div>
