<div id="map" style="width:100%; height:500px;">

</div>


<script>


    function initMaps() {

        var option = $( "#city_id option:selected");
        if (option.length == 0){
            var option = $("input#city_id")
        }

        latitude = option.data('geo_lat');
        longitude = option.data('geo_lon');

        ymaps.ready(function(){

            map = new ymaps.Map('map', {
                center: [parseFloat(latitude), parseFloat(longitude)],
                zoom: 10,
                controls: ["typeSelector"]
            });
            openFlagToMap()
        });
    }

    function openFlagToMap() {
        var option = $( "#city_id option:selected");
        if (option.length == 0){
            var option = $("input#city_id")
        }

        latitude = option.data('geo_lat');
        longitude = option.data('geo_lon');

        if(latitude.length > 0 && longitude.length > 0){

            map.setCenter([latitude, longitude], 10);
            map.setZoom(10);
        }
    }

    function clearMap()
    {
        map.geoObjects.removeAll();
    }

    function setObjectMap(id, geo_lat, geo_long, title, coment)
    {
        myGeoObject_e = new ymaps.GeoObject({
            geometry: {
                type: "Point",// тип геометрии - точка
                coordinates: [geo_lat, geo_long] // координаты точки
            },
            properties: {
                balloonContentHeader: title,
                balloonContentBody: coment,
                id: id
            }},
            {
                preset: 'islands#redIcon',
                hintHideTimeout: 0,
            });

        map.geoObjects.add(myGeoObject_e);
    }

    function setPoinsMap(id, geo_lat, geo_long, title, coment, type)
    {
        myGeoObject_e = new ymaps.GeoObject({
            geometry: {
                type: "Point",// тип геометрии - точка
                coordinates: [geo_lat, geo_long] // координаты точки
            },
            properties: {
                balloonContentHeader: title,
                balloonContentBody: coment,
                typeContent: type,
                id: id
            }
        });

        myGeoObject_e.events.add('click', function (e) {
            // Метка, на которой сработало событие
            var params = e.get('target').properties._data;
            set_tr(params.id, params.typeContent);
        });

        map.geoObjects.add(myGeoObject_e);
    }

    function set_tr(id, typeContent){
        var tr = $('.table_for_yamap tr');
        tr.each(function (index, value){
            if ($(this).data('id') == id) {
                $(this).addClass('choosen_yamap');

                if(typeContent == 'order'){
                    selectDistribute(id);
                }

                if(typeContent == 'user'){
                    selectUser(id);
                }

            }else{
                $(this).removeClass('choosen_yamap');
            }
        });
    }

    function go_point(id, typeContent){
        map.geoObjects.each(function (item) {

            if (item.properties.get('id') == id) {
                var coord = item.geometry.getCoordinates();

                map.panTo([parseFloat(coord[0]), parseFloat(coord[1])], {
                    // Задержка между перемещениями.
                    delay: 1500
                });
                item.balloon.open();
            }
        });

        set_tr(id, typeContent);

    }

</script>


<style>
    .block-view{
        padding: 20px 35px 35px 35px;
    }
    .contract-block{
        display: block;
        padding: 15px 15px 15px 15px;
        border: 1px solid #e4e4e4;
        margin-bottom: 11px;
        margin-top: 10px;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 5px 20px rgba(0, 0, 0, .15);
    }
    .contract-block div{
        color: #7d7f81;
        font-family: "AgoraSansProRegular", "serif";
        font-size: 14px;
        font-weight: normal;
    }
    .contract-block:hover,
    .contract-block:visited,
    .contract-block:link,
    .contract-block:active
    {
        text-decoration: none;
    }
    .choosen_yamap {
        background: #d9ebc6 !important;
    }
</style>