<div class="form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row col-sm-6">
        <label class="col-sm-12 control-label" style="max-width: none;">Размер уставного капитала (фонда)</label>
        <div class="col-sm-12">
            {{ Form::text("share_capital", titleFloatFormat($general->data->share_capital), ['class' => 'form-control sum']) }}
        </div>
    </div>

    <div class="form-equally row col-sm-6">
        <label class="col-sm-12 control-label" style="max-width: none;">Сведения о лицензии</label>
        <div class="col-sm-12">
            {{ Form::text("license_information", $general->data->license_information, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="clear"></div>

    <div class="row col-sm-12">
        <label class="col-sm-12 control-label" style="max-width: none;">Присутствие постоянно действующего органа управления</label>
        <div class="col-sm-12">
            {{ Form::text("presence_permanent_management_body", $general->data->presence_permanent_management_body, ['class' => 'form-control']) }}
        </div>
    </div>


    <div class="row col-sm-12">
        <label class="col-sm-12 control-label" style="max-width: none;">Структура органов управления ЮЛ и их полномочия</label>
        <div class="col-sm-12">
            {{ Form::text("management_structure", $general->data->management_structure, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="row col-sm-12">
        <label class="col-sm-12 control-label" style="max-width: none;">Меры предпринятые для установления бенефициарных</label>
        <div class="col-sm-12">
            {{ Form::text("undertaken_identify_beneficial", $general->data->undertaken_identify_beneficial, ['class' => 'form-control']) }}
        </div>
    </div>
</div>

<div class="clear"></div>

<div class="form-equally col-xs-12 col-sm-12 col-md-12 col-lg-12">

    <div class="row col-sm-4">
        <label class="col-sm-12 control-label" style="max-width: none;">ОКПО</label>
        <div class="col-sm-12">
            {{ Form::text("okpo", $general->data->okpo, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="col-sm-4">
        <label class="col-sm-12 control-label" style="max-width: none;">ОКТМО</label>
        <div class="col-sm-12">
            {{ Form::text("oktmo", $general->data->oktmo, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="form-equally row col-sm-4">
        <label class="col-sm-12 control-label" style="max-width: none;">ОКФС</label>
        <div class="col-sm-12">
            {{ Form::text("okfs", $general->data->okfs, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="clear"></div>

    <div class="row col-sm-4">
        <label class="col-sm-12 control-label" style="max-width: none;">ОКОПФ</label>
        <div class="col-sm-12">
            {{ Form::text("okopf", $general->data->okopf, ['class' => 'form-control']) }}
        </div>
    </div>


    <div class="col-sm-4">
        <label class="col-sm-12 control-label" style="max-width: none;">ОКАТО</label>
        <div class="col-sm-12">
            {{ Form::text("okato", $general->data->okato, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="form-equally row col-sm-4">
        <label class="col-sm-12 control-label" style="max-width: none;">ОКОГУ</label>
        <div class="col-sm-12">
            {{ Form::text("okogy", $general->data->okogy, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="clear"></div>

    <div class="row col-sm-4">
        <label class="col-sm-12 control-label" style="max-width: none;">ОКВЭД код</label>
        <div class="col-sm-12">
            {{ Form::text("okved_code", $general->data->okved_code, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="row form-equally col-sm-8">
        <label class="col-sm-12 control-label" style="max-width: none;">ОКВЭД описание</label>
        <div class="col-sm-12">
            {{ Form::text("okved_title", $general->data->okved_title, ['class' => 'form-control']) }}
        </div>
    </div>

    <div class="clear"></div>

    <div class="col-sm-12">
    @if(strlen($general->data->okved_complementary) > 3)
        @foreach(json_decode($general->data->okved_complementary) as $okved)
            <label class="control-label" style="max-width: none;">{{ $okved->code }} {{ $okved->name }}</label>
        @endforeach
    @endif

    </div>
    <div class="clear"></div>

    <div class="row col-sm-12">
        <label class="col-sm-12 control-label">Описание</label>
        <div class="col-sm-12">
            {{ Form::textarea('comments',  $general->comments,  ['class' => 'form-control']) }}
        </div>
    </div>

</div>

<div class="clear"></div>