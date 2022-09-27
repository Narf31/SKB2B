@if(isset($doc) && (isset($doc->serie) || isset($doc->number)))
<div class="view-field">
    <span class="view-label">{{$doc->type_id>0?$docs[$doc->type_id]:''}} @if(isset($_pref)) {{$_pref}} @endif</span>
    <span class="view-value">{{$doc->serie}} {{$doc->number}}</span>
</div>
<div class="view-field">
    <span class="view-label">Код подразделения</span>
    <span class="view-value">{{$doc->unit_code}}</span>
</div>
<div class="view-field">
    <span class="view-label">Дата выдачи</span>
    <span class="view-value">{{getDateFormatRu($doc->date_issue)}}</span>
</div>
<div class="view-field">
    <span class="view-label">Кем выдан</span>
    <span class="view-value">{{$doc->issued}}</span>
</div>
@endif