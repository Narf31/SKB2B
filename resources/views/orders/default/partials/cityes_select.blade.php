@php($cities = \App\Models\Settings\City::where('is_actual', '=', '1')->get())
<select name="order[city_id]" id="city_id" onchange="get_executors()" class="form-control select2-ws cities">
@foreach($cities as $city)
    <option value="{{ $city->id }}" data-id="{{ $city->id }}" {{ isset($city_id) && $city_id == $city->id ? 'selected' : '' }} data-geo_lat="{{ $city->geo_lat }}" data-geo_lon="{{ $city->geo_lon }}">{{ $city->title }}</option>
@endforeach
</select>