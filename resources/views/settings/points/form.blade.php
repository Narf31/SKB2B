<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/banks.is_actual') }}</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_actual', 1, old('is_actual')) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/banks.title') }}</label>
    <div class="col-sm-8">
        {{ Form::text('title', old('title'), ['class' => 'form-control', 'required']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Город</label>
    <div class="col-sm-8">
        {{ Form::select('city_id', \App\Models\Settings\City::where('is_actual', '=', '1')->get()->pluck('title', 'id'), old('city_id'), ['class' => 'form-control select2-ws', 'required']) }}

    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Адрес</label>
    <div class="col-sm-8">
        {{ Form::text('address', old('address'), ['class' => 'form-control address-autocomplete', 'data-address-type' => 'address']) }}
        <input type="hidden" name="latitude" data-name="address_latitude" value="{{old('latitude')}}"/>
        <input type="hidden" name="longitude" data-name="address_longitude" value="{{old('longitude')}}"/>
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Продажи</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_sale', 1, old('is_sale')) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">ПСО</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_pso', 1, old('is_pso')) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Убытки</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_damages', 1, old('is_damages')) }}
    </div>
</div>