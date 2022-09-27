<div class="row col-xs-12 col-sm-12 col-md-12 col-lg-6">


   <div id="peopleDistribute" style="display: none;background-color: #ffffff;background-color: #ffffff;position: absolute;width: 97%;height: 520px;overflow: auto;z-index: 10;">
      <span class="btn btn-danger pull-left" onclick="cencelUsers()">Отмена</span>
      <span class="btn btn-primary pull-right" onclick="assignUser()">Назначить</span>

      <input type="hidden" id="order-id" value=""/>
      <input type="hidden" id="user-id" value=""/>

      <br/><br/><br/>

      <div id="executorDetail" style="display: none;">
         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-top:15px;">
            <div class="row">
               <h3 id="user-name"></h3>
               <div class="view-field">
                  <span class="view-label">Рабочий телефон</span>
                  <span class="view-value" id="user-phone"></span>
               </div>
               <div class="view-field">
                  <span class="view-label">Организация</span>
                  <span class="view-value" id="user-org"></span>
               </div>
            </div>
         </div>
      </div>

      <div id="peopleDetail"></div>


   </div>


   <span class="btn btn-success pull-left buttDistribute" onclick="openOrder()" style="display: none;">Открыть</span>
   <span class="btn btn-primary pull-right buttDistribute" onclick="setDistribute()" style="display: none;">Распределить</span>
   <br/><br/><br/>
   <div style="height: 420px;">

      <table class="table table-striped table-bordered table_for_yamap table_for_orders" >
         <thead>
         <tr>
            <th>#</th>
            <th>Дата / Время</th>
            <th>Продукт</th>
            <th>Страхователь</th>
         </tr>
         </thead>
         <tbody>
         @foreach($orders as $order)
            <tr style="cursor: pointer;" onclick="go_point({{$order->id}}, 'order')" data-id="{{$order->id}}" data-geo_lat="{{$order->latitude}}" data-geo_long="{{$order->longitude}}" data-title="Заявка #{{$order->id}} {{setDateTimeFormatRu($order->begin_date)}}" data-coment="{{$order->address}} - {{$order->comments}}">
               <td>{{$order->id}}</td>
               <td>{{setDateTimeFormatRu($order->begin_date)}}</td>
               <td>{{$order->product->title}}</td>
               <td>{{$order->insurer_title}}</td>
            </tr>
         @endforeach
         </tbody>
      </table>

   </div>


   <div class="row">
      <div id="page_list" class="easyui-pagination pull-right"></div>
   </div>


</div>


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

   @include("orders.default.maps")

</div>


<script>

    function startMainFunctions() {
        initMaps();
        setTimeout(function () {
            activePoints();
        }, 500);
    }

    function activePoints() {
        var tr = $('.table_for_orders tr');
        tr.each(function (index, value){

            setPoinsMap($(this).data('id'), $(this).data('geo_lat'), $(this).data('geo_long'), $(this).data('title'), $(this).data('coment'), 'order');

        });
    }


    function selectDistribute(order_id) {

         $(".buttDistribute").show();
         $("#order-id").val(order_id);

    }

    function setDistribute() {


        order_id = $("#order-id").val();

        loaderShow();

        $.get("/orders/pso/people-list", {order_id:order_id}, function (response) {
            loaderHide();
            $("#peopleDetail").html(response);
            $("#peopleDistribute").show();

            clearMap();
            //Запускаем отрисовку
            var tr = $('.table_for_users tr');
            tr.each(function (index, value){
                setPoinsMap($(this).data('id'), $(this).data('latitude'), $(this).data('longitude'), $(this).data('name'), $(this).data('phone'), 'user');
            });

            //РИСУЕМ ЗАЯВКУ
            setObjectMap($("#order-id").val(), $("#order-latitude").val(), $("#order-longitude").val(), $("#order-title").val(), $("#order-coment").val());


        }).done(function() {
            loaderShow();
        }).fail(function() {
             loaderHide();
        }).always(function() {
             loaderHide();
        });


    }


    function cencelUsers() {
        clearMap();
        $('#peopleDistribute').hide();
        $("#peopleDetail").html('');
        $("#executorDetail").hide();
        $("#user-id").val(0);
        $("#user-name").html('');
        $("#user-phone").html('');
        $("#user-org").html('');
        activePoints();
    }

    function selectUser(tr_id) {

        obj = "#tr_user_"+tr_id;

        id = $(obj).data("id");

        $("#executorDetail").show();
        $("#user-id").val($(obj).data("id"));
        $("#user-name").html($(obj).data("name"));
        $("#user-phone").html($(obj).data("phone"));
        $("#user-org").html($(obj).data("org"));

        var tr = $('.table_for_users tr');
        tr.each(function (index, value){
            if ($(this).data('id') == id) {
                $(this).addClass('choosen_yamap');

            }else{
                $(this).removeClass('choosen_yamap');
            }
        });

    }


    function assignUser()
    {
        order_id = $("#order-id").val();
        user_id = $("#user-id").val();

        loaderShow();

        $.get("/orders/pso/assign-user", {order_id:order_id, user_id:user_id}, function (response) {
            loaderHide();

            if (Boolean(response.state) === true) {

                flashMessage('success', "Данные успешно сохранены!");
                selectTab(TAB_INDEX);

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


   function openOrder() {
       window.location = "/orders/pso/"+$("#order-id").val();
   }


</script>
