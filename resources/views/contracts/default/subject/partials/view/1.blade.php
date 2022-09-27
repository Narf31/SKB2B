<div class="view-field">
    <span class="view-label">Название компании</span>
    <span class="view-value">{{$subject_original->title}}</span>
</div>

<div class="view-field">
    <span class="view-label">ИНН / КПП</span>
    <span class="view-value">{{$subject_original->inn}} / {{$subject_original->kpp}}</span>
</div>

<div class="view-field">
    <span class="view-label">ОГРН</span>
    <span class="view-value">{{$subject_original->ogrn}}</span>
</div>


<div class="view-field">
    <span class="view-label">Телефон</span>
    <span class="view-value">{{$subject_original->phone}}</span>
</div>

<div class="view-field">
    <span class="view-label">Email</span>
    <span class="view-value">{{$subject_original->email}}</span>
</div>

@if($subject->general)
    @include("contracts.default.subject.partials.view.gentral_1", ['general' => $subject->general])
@endif

