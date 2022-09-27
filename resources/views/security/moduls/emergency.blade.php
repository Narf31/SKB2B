<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="page-subheading">
        <h2>Экстренные кнопки: {{$emergency->event_title}}</h2>
    </div>

    <div class="block-inner">
        <div class="row">
            <div class="col-xs-12 col-sm-5 col-md-5 col-lg-5">
                @include('vehicles.cars.car', ['car' => $emergency->car])
            </div>

            <div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
                <div id="map" style="width:100%; height:600px"></div>
            </div>

        </div>
    </div>

</div>


@section('js')

    <script>

        var default_latitude = "{{$emergency->latitude}}";
        var default_longitude = "{{$emergency->longitude}}";


        $(function () {

            ymaps.ready(function(){
                map = new ymaps.Map('map', {
                    center: [55.755773, 37.617761],
                    zoom: 5,
                    controls: ["zoomControl", "typeSelector"]
                });
                loadItems();
            });

        });


        function loadItems(){
            openObjMap(default_latitude, default_longitude, getIconMap(), 48);
        }


        function openObjMap(latitude, longitude, icon, size){

            myPlacemarkWithContent = new ymaps.Placemark([latitude, longitude], {

            }, {
                // Опции.
                // Необходимо указать данный тип макета.
                iconLayout: 'default#imageWithContent',
                // Своё изображение иконки метки.
                iconImageHref: icon,
                // Размеры метки.
                iconImageSize: [size, size],
                // Смещение левого верхнего угла иконки относительно
                // её "ножки" (точки привязки).
                iconImageOffset: [-24, -24],
                // Смещение слоя с содержимым относительно слоя с картинкой.
                iconContentOffset: [15, 15],

            });

            map.geoObjects.add(myPlacemarkWithContent);
            map.setCenter([latitude, longitude], 16);
        }





        function getIconMap(){
            return "/images/icon/emergency.png";
        }






    </script>

@append