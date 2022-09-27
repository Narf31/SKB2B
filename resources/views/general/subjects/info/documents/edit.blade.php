<input type="hidden" name="doc[{{$index}}][is_main]" value="{{$is_main}}"/>

<div class="row col-sm-8">
    <label class="col-sm-12 control-label" style="max-width: none;">Тип документа @if(isset($_pref)) {{$_pref}} @endif
        <div class="pull-right">
            @if($is_main == 1)
                <input type="hidden" name="doc[{{$index}}][is_actual]" value="1"/>
            @else
                Актуально <input type="checkbox" name="{{"doc[{$index}][is_actual]"}}" value="1" @if($doc->is_actual == 1) checked @endif/>
            @endif

        </div>
    </label>
    <div class="col-sm-12">
        {{Form::select("doc[{$index}][type_id]", collect($docs), $doc->type_id, ['class' => 'form-control select2-ws'])}}
    </div>
</div>

<div class="row form-equally col-sm-4">
    <label class="col-sm-12 control-label">Код подразделения <span class="required">*</span></label>
    <div class="col-sm-12">
        {{Form::text("doc[{$index}][unit_code]", $doc->unit_code, ['class' => 'form-control validate'])}}
    </div>
</div>
<div class="clear"></div>

<div class="row col-sm-4">
    <label class="col-sm-12 control-label">Серия <span class="required">*</span></label>
    <div class="col-sm-12">
        {{Form::text("doc[{$index}][serie]", $doc->serie, ['class' => 'form-control validate'])}}
    </div>
</div>

<div class="col-sm-4">
    <label class="col-sm-12 control-label">Номер <span class="required">*</span></label>
    <div class="col-sm-12">
        {{Form::text("doc[{$index}][number]", $doc->number, ['class' => 'form-control validate'])}}
    </div>
</div>

<div class="row form-equally col-sm-4">
    <label class="col-sm-12 control-label">Дата выдачи <span class="required">*</span></label>
    <div class="col-sm-12">
        {{Form::text("doc[{$index}][date_issue]", setDateTimeFormatRu($doc->date_issue, 1), ['class' => 'form-control format-date validate'])}}
    </div>
</div>

<div class="row col-sm-12">
    <label class="col-sm-12 control-label">Кем выдан <span class="required">*</span></label>
    <div class="col-sm-12">
        {{Form::text("doc[{$index}][issued]", $doc->issued, ['class' => 'form-control validate'])}}
    </div>
</div>