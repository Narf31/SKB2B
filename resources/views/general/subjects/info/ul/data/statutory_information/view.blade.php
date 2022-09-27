<div class="view-field">
    <span class="view-label">Размер уставного капитала (фонда)</span>
    <span class="view-value">{{titleFloatFormat($general->data->share_capital)}}</span>
</div>


<div class="view-field">
    <span class="view-label">Сведения о лицензии</span>
    <span class="view-value">{{$general->data->license_information}}</span>
</div>

<div class="view-field">
    <span class="view-label">Присутствие постоянно действующего органа управления</span>
    <span class="view-value">{{$general->data->presence_permanent_management_body}}</span>
</div>

<div class="view-field">
    <span class="view-label">Структура органов управления ЮЛ и их полномочия</span>
    <span class="view-value">{{$general->data->management_structure}}</span>
</div>

<div class="view-field">
    <span class="view-label">Меры предпринятые для установления бенефициарных</span>
    <span class="view-value">{{$general->data->undertaken_identify_beneficial}}</span>
</div>


<div class="view-field">
    <span class="view-label">ОКПО</span>
    <span class="view-value">{{$general->data->okpo}}</span>
</div>

<div class="view-field">
    <span class="view-label">ОКТМО</span>
    <span class="view-value">{{$general->data->oktmo}}</span>
</div>

<div class="view-field">
    <span class="view-label">ОКФС</span>
    <span class="view-value">{{$general->data->okfs}}</span>
</div>



<div class="view-field">
    <span class="view-label">ОКАТО</span>
    <span class="view-value">{{$general->data->okato}}</span>
</div>
<div class="view-field">
    <span class="view-label">ОКОГУ</span>
    <span class="view-value">{{$general->data->okogy}}</span>
</div>

<div class="view-field">
    <span class="view-label">ОКОПФ</span>
    <span class="view-value">{{$general->data->okopf}}</span>
</div>

<div class="view-field">
    <span class="view-label">ОКВЭД</span>
    <span class="view-value">{{$general->data->okved_code}} {{$general->data->okved_title}}</span>
</div>

@if(strlen($general->data->okved_complementary) > 3)
    @foreach(json_decode($general->data->okved_complementary) as $okved)

        <label class="control-label" style="max-width: none;">{{ $okved->code }} {{ $okved->name }}</label>

    @endforeach
@endif

<div class="form-group">
    <label class="col-sm-12 control-label">Описание</label>
    <div class="col-sm-12">
        {{ $general->comments }}
    </div>
</div>

<div class="clear"></div>