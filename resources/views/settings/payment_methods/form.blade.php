
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/banks.title') }}</label>
    <div class="col-sm-8">
        {{ Form::text('title', old('banks'), ['class' => 'form-control', 'required']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/banks.is_actual') }}</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_actual', 1, old('is_actual')) }}
    </div>
</div>


<div class="form-group">
    <label class="col-sm-4 control-label">Тип оплаты</label>
    <div class="col-sm-8">
        {{ Form::select('payment_type', collect(\App\Models\Contracts\Payments::PAYMENT_TYPE), old('payment_type'), ['class' => 'form-control select2-ws']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Поток оплаты</label>
    <div class="col-sm-8">
        {{ Form::select('payment_flow', collect(\App\Models\Contracts\Payments::PAYMENT_FLOW), old('payment_flow'), ['class' => 'form-control select2-ws']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Интерфейс</label>
    <div class="col-sm-8">
        {{ Form::select('key_type', collect(\App\Models\Settings\PaymentMethods::KEY_TYPE), old('key_type'), ['class' => 'form-control select2-ws']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Комиссия</label>
    <div class="col-sm-8">
        {{ Form::text('acquiring', old('acquiring'), ['class' => 'form-control sum']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Контроллер</label>
    <div class="col-sm-8">
        {{ Form::select('control_type', collect(\App\Models\Settings\PaymentMethods::CONTROLL), old('control_type'), ['class' => 'form-control select2-ws']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">
        Шаблон <br/>
        @if(isset($pay_method) && $pay_method->id > 0)
            @if($pay_method->template)
                <a href="{{ $pay_method->template->getUrlAttribute() }}" target="_blank" style="float: left">{{ $pay_method->template->original_name }}</a>
            @endif
        @endif
    </label>
    <div class="col-sm-8">
        {{ Form::file('template', ['class' => 'file-input']) }}
    </div>
</div>