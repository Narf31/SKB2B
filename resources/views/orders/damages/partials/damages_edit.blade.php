
   {{ Form::open(['url' => url('/orders/damages/create'), 'method' => 'post', 'class' => 'form-horizontal', 'id' => 'data-form']) }}

   <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="col-lg-4">
         <div class="field form-col">
            <label class="control-label">Город</label>
            @include('orders.default.partials.cityes_select', ['city_id' => $damage->city_id])
         </div>
      </div>

      <div class="col-lg-8">
         <div class="field form-col">
            <label class="control-label">Тип</label>
            {{ Form::select('order[position_type_id]', \App\Models\Orders\Damages::POSITION_TYPE, $damage->position_type_id, ['class' => 'form-control select2-ws', 'onchange'=>"getViewFormPositionType();", 'id'=>'position_type_id']) }}
         </div>
      </div>

   </div>

   <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="form-address">
      <div class="col-lg-12">
         <div class="field form-col">
            <label class="control-label">Адрес осмотра</label>
            {{ Form::text('order[address]', $damage->address, ['class' => 'form-control', 'id'=>'object_address']) }}
            <input name="order[latitude]" id="object_address_latitude" value="{{$damage->latitude}}" type="hidden"/>
            <input name="order[longitude]" id="object_address_longitude" value="{{$damage->longitude}}" type="hidden"/>

         </div>
      </div>
   </div>
   <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12" id="form-point-sale">
      <div class="col-lg-12">
         <div class="field form-col">
            <label class="control-label">Точка осмотра</label>
            {{ Form::select('order[point_sale_id]', collect([]), $damage->point_sale_id, ['class' => 'form-control select2-all', 'id'=>'point_sale_id']) }}
         </div>
      </div>
   </div>

   <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

      @php
         $date = $damage->begin_date ? setDateTimeFormatRu($damage->begin_date, 1) : \Carbon\Carbon::now()->format('d.m.Y');
         $time = $damage->begin_date ? getDateFormatTimeRu($damage->begin_date) : \Carbon\Carbon::now()->format('H:i');
      @endphp

      <div class="col-xs-4 col-sm-4 col-md-4 col-lg-3">
         <div class="field form-col">
            <label class="control-label">Время</label>
            {{ Form::text('order[time]', ($time) ? $time : \Carbon\Carbon::now()->format('H:i'), ['class' => 'form-control format-time']) }}
         </div>
      </div>


      <div class="col-xs-4 col-sm-4 col-md-4 col-lg-3">
         <div class="field form-col">
            <label class="control-label">Дата</label>
            {{ Form::text('order[date]', $date, ['class' => 'form-control format-date datepicker date']) }}
         </div>
      </div>

      <div class="col-xs-4 col-sm-4 col-md-4 col-lg-6">
         <div class="field form-col">
            <label class="control-label">Страхователь</label>
            {{ Form::select('order[insurer_type_id]', \App\Models\Orders\Damages::INSURER_TYPE, $damage->insurer_type_id, ['class' => 'form-control select2-ws']) }}
         </div>
      </div>


   </div>

   <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
         <div class="field form-col">
            <label class="control-label">Телефон</label>
            {{ Form::text('order[phone]', $damage->phone, ['class' => 'form-control phone']) }}
         </div>
      </div>

      <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
         <div class="field form-col">
            <label class="control-label">Email</label>
            {{ Form::text('order[email]', $damage->email, ['class' => 'form-control ']) }}
         </div>
      </div>

   </div>

   <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">

      <div class="col-lg-12">
         <div class="field form-col">
            <label class="control-label">Описание</label>
            {{ Form::textarea('order[comments]', $damage->comments, ['class' => 'form-control ']) }}
         </div>
      </div>

   </div>



   @include("orders.damages.partials.button_event", ['damage'=>$damage, 'STATYS' => \App\Models\Orders\Damages::STATYS])

   {{Form::close()}}


<script>

   function initActivForms() {
       get_executors();
       getViewFormPositionType();

       $('.phone').mask('+7 (999) 999-99-99');

       $('#object_address').suggestions({
           serviceUrl: DADATA_AUTOCOMPLETE_URL,
           token: DADATA_TOKEN,
           type: "ADDRESS",
           count: 5,
           onSelect: function (suggestion) {

               key = $(this).data('key');
               $('#object_address').val($(this).val());
               $('#object_address_latitude').val(suggestion.data.geo_lat);
               $('#object_address_longitude').val(suggestion.data.geo_lon);

           }
       });

   }

   function get_executors() {
       city_id = $("#city_id").val();

       $.post(
           '{{url("/orders/actions/get_point_sale")}}',
           {city_id:city_id, type:1},
           function (response) {
               select_val = 0;
               var options = '';
               response.map(function (item) {
                   if(select_val == 0){
                       select_val = item.id;
                   }
                   options += "<option value='" + item.id + "'>" + item.title + "</option>";
               });

               $('#point_sale_id').html(options).select2('val', select_val);

           });
   }

   function getViewFormPositionType() {
       position_type_id = $("#position_type_id").val();
       if(parseInt(position_type_id) == 1){
           $("#form-address").hide();
           $("#form-point-sale").show();
       }else{
           $("#form-address").show();
           $("#form-point-sale").hide();
       }
   }


</script>