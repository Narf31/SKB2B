<div class="form-group">
    <label class="col-sm-4 control-label">Удержание КВ</label>
    <div class="col-sm-8">
        {{ Form::select('hold_type_id', collect(\App\Models\Directories\HoldKv::HOLD_TYPE), $group_payment_info->hold_type_id, ['class' => 'form-control', 'required']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Автоматическое списание БСО</label>
    <div class="col-sm-8">
        {{ Form::select('is_auto_bso', collect([0=>'Нет', 1=>'Да']), $group_payment_info->is_auto_bso, ['class' => 'form-control', 'required']) }}
    </div>
</div>

<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
@foreach($payments_type as $key => $type)


    <div class="form-group">
        <label class="col-sm-4 control-label">{{$type->title}}</label>
        <div class="col-sm-8">
            {{ Form::checkbox("payment_type[$type->id]", 1, $group_payment->isPayment($insurance_companies, $bso_supplier, $hold_kv, $group_id, $type->id, $bso_class_id), ['class'=>'pull-left']) }}
        </div>
    </div>
    <div class="clear"></div>


@endforeach
</div>
