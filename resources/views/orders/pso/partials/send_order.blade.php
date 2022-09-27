

<div class="row col-xs-12 col-sm-12 col-md-6 col-lg-6" id="data-order">
    <h2>Запись на предстраховой осмотр</h2>
    <div class="row form-horizontal">


        <div class="col-lg-12" >
            <label class="control-label">Город</label>

            <select name="order[city_id]" id="cities" onchange="moveTo()" class="form-control select2-ws cities">
                @foreach(\App\Models\Settings\City::where('is_actual', '=', '1')->get() as $city)
                    <option value="{{ $city->id }}" data-id="{{ $city->id }}" {{ isset($order->city_id) && $order->city_id == $city->id ? 'selected' : '' }} data-geo_lat="{{ $city->geo_lat }}" data-geo_lon="{{ $city->geo_lon }}">{{ $city->title }}</option>
                @endforeach
            </select>
        </div>


        <div class="col-lg-12">
            <label class="control-label">Адрес <span class="required">*</span></label>
            {{ Form::text('order[address]', $order->address, ['class' => 'form-control valid_accept', 'id' => 'address']) }}
            {{ Form::hidden('order[latitude]', $order->latitude, ['id' => 'latitude', 'class'=>'valid_accept', 'data-parent'=>'address']) }}
            {{ Form::hidden('order[longitude]', $order->longitude, ['id' => 'longitude', 'class'=>'valid_accept', 'data-parent'=>'address']) }}
        </div>

        <div>
            <div class="col-lg-6">
                <div class="field form-col">


                    <label class="control-label">
                        Дата <span class="required">*</span>
                    </label>
                    @php
                        $date_val = date('d.m.Y', strtotime("+1 day"));
                        $time = '12:00';
                    @endphp
                    {{ Form::text('order[date]', $date_val, ['class' => 'form-control format-date valid_accept']) }}
                    <span class="glyphicon glyphicon-calendar calendar-icon"></span>

                </div>
            </div>
            <div class="col-lg-6">
                <div class="field form-col">

                    <label class="control-label">
                        Время <span class="required">*</span>
                    </label>
                    {{ Form::text('order[time]', ($time) ? $time : "12:00", ['class' => 'form-control format-time valid_accept']) }}
                    <span class="glyphicon glyphicon-time calendar-icon"></span>

                </div>
            </div>
        </div>


        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <label class="control-label">ФИО <span class="required">*</span></label>
            {{ Form::text('order[fio]', $order->insurer_title, ['class' => 'form-control valid_accept']) }}
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
            <label class="control-label">Телефон <span class="required">*</span></label>
            {{ Form::text("order[phone]", $order->phone, ['class' => 'form-control phone valid_accept']) }}
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <span class="btn btn-success btn-left" onclick="sendPsoOrder();">Записаться</span>
        </div>
    </div>




</div>

<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div id="map" style="width:100%; height:300px">

    </div>
</div>

<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

<script>


    function activePSO() {
        formatDate();
        formatTime();

        initMaps();


        $('#address').suggestions({
            serviceUrl: DADATA_AUTOCOMPLETE_URL,
            token: DADATA_TOKEN,
            type: "ADDRESS",
            count: 5,
            onSelect: function (suggestion) {
                key = $(this).data('key');
                $('#address').val($(this).val());
                $('#latitude').val(suggestion.data.geo_lat);
                $('#longitude').val(suggestion.data.geo_lon);
                MAPPoint();
            }
        });

    }


    function initMaps() {

        var option = $( "#cities option:selected");

        if (option.length == 0){
            var option = $("input#cities")
        }
        latitude = option.data('geo_lat');
        longitude = option.data('geo_lon');


        ymaps.ready(function(){

            map = new ymaps.Map('map', {
                center: [parseFloat(latitude), parseFloat(longitude)],
                zoom: 11,
                controls: ["typeSelector"]
            });
        });

    }

    function moveTo() {
        var option = $("#cities option:selected");

        if (option.length == 0){
            var option = $("input#cities")
        }

        latitude = option.data('geo_lat');
        longitude = option.data('geo_lon');

        map.panTo([parseFloat(latitude), parseFloat(longitude)], {
            // Задержка между перемещениями.
            delay: 1500
        });

    }

    function MAPPoint() {
        latitude = $('#latitude').val();
        longitude = $('#longitude').val();
        address = $('#address').val();

        map.geoObjects.removeAll();

        if(latitude.length > 0 && longitude.length > 0){
            myGeoObject_m = new ymaps.GeoObject({
                geometry: {
                    type: "Point",// тип геометрии - точка
                    coordinates: [latitude, longitude] // координаты точки
                },
                properties: {
                    balloonContentHeader: address,
                    balloonContentBody: 'Точка выезда',
                }
            }, {
                preset: 'islands#redAutoIcon',
                hintHideTimeout: 0
            });

            map.geoObjects.add(myGeoObject_m);
            map.setCenter([latitude, longitude], 10);
            map.setZoom(17);
        }

    }


    function sendPsoOrder() {
        if(!validate()){
            flashHeaderMessage("Заполните все поля!", 'danger');
            return false;
        }


        loaderShow();

        $.post("/orders/actions/{{$order->id}}/set-pso", $('#data-order :input').serialize(), function (response) {
            loaderHide();

            if (Boolean(response.state) === true) {

                flashMessage('success', "Данные успешно сохранены!");
                reload();

            }else {
                flashHeaderMessage(response.msg, 'danger');

            }

        }).done(function() {
            loaderShow();
        }).fail(function() {
            loaderHide();
        }).always(function() {
            loaderHide();
        });

    }



</script>