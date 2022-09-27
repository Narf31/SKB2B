<label class="control-label">
    {{$ad_title}} @if(!isset($not_valid_accept)) <span class="required">*</span> @endif
</label>


{{ Form::text("contract[{$subject_name}][address][{$ad_name}][title]",
    getIsArray($ad_data, "address_{$ad_name}"),
    [
        'class' => 'form-control '.(!isset($not_valid_accept)?'valid_accept':'not_valid').'',
        'id' => "{$subject_name}_address_{$ad_name}",
        'data-key' => $ad_name
    ])
}}

<input name="contract[{{$subject_name}}][address][{{$ad_name}}][fias_code]"
       id="{{$subject_name}}_{{$ad_name}}_fias_code"
       value="{{getIsArray($ad_data, "address_{$ad_name}_fias_code")}}"
       type="hidden"
       class="not_valid"/>

<input name="contract[{{$subject_name}}][address][{{$ad_name}}][fias_id]"
       id="{{$subject_name}}_{{$ad_name}}_fias_id"
       value="{{getIsArray($ad_data, "address_{$ad_name}_fias_id")}}"
       type="hidden"
       class="not_valid"/>

<input name="contract[{{$subject_name}}][address][{{$ad_name}}][kladr]"
       id="{{$subject_name}}_{{$ad_name}}_kladr"
       value="{{getIsArray($ad_data, "address_{$ad_name}_kladr")}}"
       type="hidden"
       data-parent="{{$subject_name}}_address_{{$ad_name}}"
       class="{{!isset($not_valid_accept)?'valid_accept':'not_valid'}}"/>

<input name="contract[{{$subject_name}}][address][{{$ad_name}}][region]"
       id="{{$subject_name}}_{{$ad_name}}_region"
       value="{{getIsArray($ad_data, "address_{$ad_name}_region")}}"
       type="hidden"
       class="not_valid"/>

<input name="contract[{{$subject_name}}][address][{{$ad_name}}][city]"
       id="{{$subject_name}}_{{$ad_name}}_city"
       value="{{getIsArray($ad_data, "address_{$ad_name}_city")}}"
       type="hidden"
       class="not_valid"/>

<input name="contract[{{$subject_name}}][address][{{$ad_name}}][city_kladr_id]"
       id="{{$subject_name}}_{{$ad_name}}_city_kladr_id"
       value="{{getIsArray($ad_data, "address_{$ad_name}_city_kladr_id")}}"
       type="hidden"
       class="not_valid"/>

<input name="contract[{{$subject_name}}][address][{{$ad_name}}][street]"
       id="{{$subject_name}}_{{$ad_name}}_street"
       value="{{getIsArray($ad_data, "address_{$ad_name}_street")}}"
       type="hidden"
       class="not_valid"/>

<input name="contract[{{$subject_name}}][address][{{$ad_name}}][house]"
       id="{{$subject_name}}_{{$ad_name}}_house"
       value="{{getIsArray($ad_data, "address_{$ad_name}_house")}}"
       type="hidden"
       data-parent="{{$subject_name}}_address_{{$ad_name}}"
       class="{{!isset($not_valid_accept)?'valid_accept':'not_valid'}}"/>

<input name="contract[{{$subject_name}}][address][{{$ad_name}}][block]"
       id="{{$subject_name}}_{{$ad_name}}_block"
       value="{{getIsArray($ad_data, "address_{$ad_name}_block")}}"
       type="hidden"
       class="not_valid"/>

<input name="contract[{{$subject_name}}][address][{{$ad_name}}][flat]"
       id="{{$subject_name}}_{{$ad_name}}_flat"
       value="{{getIsArray($ad_data, "address_{$ad_name}_flat")}}"
       type="hidden"
       class="not_valid"/>

<input name="contract[{{$subject_name}}][address][{{$ad_name}}][zip]"
       id="{{$subject_name}}_{{$ad_name}}_zip"
       value="{{getIsArray($ad_data, "address_{$ad_name}_zip")}}"
       type="hidden"
       class="not_valid"/>

<input name="contract[{{$subject_name}}][address][{{$ad_name}}][okato]"
       id="{{$subject_name}}_{{$ad_name}}_okato"
       value="{{getIsArray($ad_data, "address_{$ad_name}_okato")}}"
       type="hidden"
       class="not_valid"/>
