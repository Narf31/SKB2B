<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][address_register]",  $subject->get_info()->address_register, ['class' => 'valid_accept', 'id' => "{$subject_name}_address_register"]) }}
        <div class="form__label">Адрес регистрации <span class="required">*</span></div>
    </div>
</div>

<div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][address_register_house]",  $subject->get_info()->address_register_house, ['class' => 'valid_accept', 'id' => "{$subject_name}_address_register_house"]) }}
        <div class="form__label">Дом <span class="required">*</span></div>
    </div>
</div>

<div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][address_register_block]",  $subject->get_info()->address_register_block, ['class' => '', 'id' => "{$subject_name}_address_register_block"]) }}
        <div class="form__label">Корпус</div>
    </div>
</div>

<div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][address_register_flat]",  $subject->get_info()->address_register_flat, ['class' => '', 'id' => "{$subject_name}_address_register_flat"]) }}
        <div class="form__label">Офис</div>
    </div>
</div>

<input name="contract[{{$subject_name}}][address_register_fias_code]" id="{{$subject_name}}_address_register_fias_code" value="{{$subject->get_info()->address_register_fias_code}}" type="hidden"/>
<input name="contract[{{$subject_name}}][address_register_fias_id]" id="{{$subject_name}}_address_register_fias_id" value="{{$subject->get_info()->address_register_fias_id}}" type="hidden"/>

<input name="contract[{{$subject_name}}][address_register_kladr]" id="{{$subject_name}}_address_register_kladr" value="{{$subject->get_info()->address_register_kladr}}" type="hidden"/>

<input name="contract[{{$subject_name}}][address_register_region]" id="{{$subject_name}}_address_register_region" value="{{$subject->get_info()->address_register_region}}" type="hidden"/>

<input name="contract[{{$subject_name}}][address_register_city]" id="{{$subject_name}}_address_register_city" value="{{$subject->get_info()->address_register_city}}" type="hidden"/>
<input name="contract[{{$subject_name}}][address_register_city_kladr_id]" id="{{$subject_name}}_address_register_city_kladr_id" value="{{$subject->get_info()->address_register_city_kladr_id}}" type="hidden"/>
<input name="contract[{{$subject_name}}][address_register_street]" id="{{$subject_name}}_address_register_street" value="{{$subject->get_info()->address_register_street}}" type="hidden"/>

<input name="contract[{{$subject_name}}][address_register_zip]" id="{{$subject_name}}_address_register_zip" value="{{$subject->get_info()->address_register_zip}}" type="hidden"/>
<input name="contract[{{$subject_name}}][address_register_okato]" id="{{$subject_name}}_address_register_okato" value="{{$subject->get_info()->address_register_okato}}" type="hidden"/>

<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][address_fact]", $subject->get_info()->address_fact, ['class' => 'valid_accept', 'id' => "{$subject_name}_address_fact"]) }}
        <div class="form__label">Адрес фактический <span class="required">*</span></div>
    </div>
</div>

<div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][address_fact_house]",  $subject->get_info()->address_fact_house, ['class' => 'valid_accept', 'id' => "{$subject_name}_address_fact_house"]) }}
        <div class="form__label">Дом <span class="required">*</span></div>
    </div>
</div>

<div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][address_fact_block]",  $subject->get_info()->address_fact_block, ['class' => '', 'id' => "{$subject_name}_address_fact_block"]) }}
        <div class="form__label">Корпус</div>
    </div>
</div>

<div class="col-xs-12 col-sm-4 col-md-4 col-lg-2 col__custom form__item">
    <div class="form__field">
        {{ Form::text("contract[{$subject_name}][address_fact_flat]",  $subject->get_info()->address_fact_flat, ['class' => '', 'id' => "{$subject_name}_address_fact_flat"]) }}
        <div class="form__label">Офис</div>
    </div>
</div>


<input name="contract[{{$subject_name}}][address_fact_fias_code]" id="{{$subject_name}}_address_fact_fias_code" value="{{$subject->get_info()->address_fact_fias_code}}" type="hidden"/>
<input name="contract[{{$subject_name}}][address_fact_fias_id]" id="{{$subject_name}}_address_fact_fias_id" value="{{$subject->get_info()->address_fact_fias_id}}" type="hidden"/>

<input name="contract[{{$subject_name}}][address_fact_kladr]" id="{{$subject_name}}_address_fact_kladr" value="{{$subject->get_info()->address_fact_kladr}}" type="hidden"/>

<input name="contract[{{$subject_name}}][address_fact_region]" id="{{$subject_name}}_address_fact_region" value="{{$subject->get_info()->address_fact_region}}" type="hidden"/>

<input name="contract[{{$subject_name}}][address_fact_city]" id="{{$subject_name}}_address_fact_city" value="{{$subject->get_info()->address_fact_city}}" type="hidden"/>
<input name="contract[{{$subject_name}}][address_fact_city_kladr_id]" id="{{$subject_name}}_address_fact_city_kladr_id" value="{{$subject->get_info()->address_fact_city_kladr_id}}" type="hidden"/>
<input name="contract[{{$subject_name}}][address_fact_street]" id="{{$subject_name}}_address_fact_street" value="{{$subject->get_info()->address_fact_street}}" type="hidden"/>

<input name="contract[{{$subject_name}}][address_fact_zip]" id="{{$subject_name}}_address_fact_zip" value="{{$subject->get_info()->address_fact_zip}}" type="hidden"/>
<input name="contract[{{$subject_name}}][address_fact_okato]" id="{{$subject_name}}_address_fact_okato" value="{{$subject->get_info()->address_fact_okato}}" type="hidden"/>

<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

