
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/banks.title') }}</label>
    <div class="col-sm-8">
        {{ Form::text('title', old('title'), ['class' => 'form-control address-autocomplete', 'data-address-type' => 'city', 'required']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/banks.is_actual') }}</label>
    <div class="col-sm-8">
        {{ Form::checkbox('is_actual', 1, old('is_actual')) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">КЛАДР</label>
    <div class="col-sm-8">
        {{ Form::text('kladr', old('kladr'), ['class' => 'form-control', 'id' => 'address_kladr', 'data-name' => 'city_kladr']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Широта</label>
    <div class="col-sm-8">
        {{ Form::text('geo_lat', old('geo_lat'), ['class' => 'form-control', 'id' => 'geo_lat', 'data-name' => 'city_latitude']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Долгота</label>
    <div class="col-sm-8">
        {{ Form::text('geo_lon', old('geo_lon'), ['class' => 'form-control', 'id' => 'geo_lon', 'data-name' => 'city_longitude']) }}
    </div>
</div>
