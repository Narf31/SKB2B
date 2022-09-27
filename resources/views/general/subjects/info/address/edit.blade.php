<div class="row col-sm-{{$size}}">
    <label class="col-sm-12 control-label">{{$title_address}}</label>
    <div class="col-sm-12">
        {{ Form::text("address[$address->type_id][address]", $address->address,
        [
            'class' => 'form-control  address',
            'id' => "{$name_address}_address",
            'data-key'=>$name_address,
            'placeholder' => '',
            'data-copy' => ((isset($copy_to) && strlen($copy_to) > 0)?$copy_to:''),
        ]) }}

        <input name="address[{{$address->type_id}}][type_id]" value="{{$address->type_id}}" type="hidden"/>
        <input name="address[{{$address->type_id}}][fias_code]" id="{{$name_address}}_fias_code" value="{{$address->fias_code}}" type="hidden"/>
        <input name="address[{{$address->type_id}}][fias_id]" id="{{$name_address}}_fias_id" value="{{$address->fias_id}}" type="hidden"/>
        <input name="address[{{$address->type_id}}][kladr]" id="{{$name_address}}_kladr" value="{{$address->kladr}}" type="hidden"/>
        <input name="address[{{$address->type_id}}][okato]" id="{{$name_address}}_okato" value="{{$address->okato}}" type="hidden"/>
        <input name="address[{{$address->type_id}}][zip]" id="{{$name_address}}_zip" value="{{$address->zip}}" type="hidden"/>
        <input name="address[{{$address->type_id}}][region]" id="{{$name_address}}_region" value="{{$address->region}}" type="hidden"/>
        <input name="address[{{$address->type_id}}][city]" id="{{$name_address}}_city" value="{{$address->city}}" type="hidden"/>
        <input name="address[{{$address->type_id}}][city_kladr_id]" id="{{$name_address}}_city_kladr_id" value="{{$address->city_kladr_id}}" type="hidden"/>
        <input name="address[{{$address->type_id}}][street]" id="{{$name_address}}_street" value="{{$address->street}}" type="hidden"/>
        <input name="address[{{$address->type_id}}][house]" id="{{$name_address}}_house" value="{{$address->house}}" type="hidden"/>
        <input name="address[{{$address->type_id}}][block]" id="{{$name_address}}_block" value="{{$address->block}}" type="hidden"/>
        <input name="address[{{$address->type_id}}][flat]" id="{{$name_address}}_flat" value="{{$address->flat}}" type="hidden"/>
    </div>
</div>



