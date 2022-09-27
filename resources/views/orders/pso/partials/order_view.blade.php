      <div class="row form-horizontal">
         <h2 class="inline-h1">
            @if(isset($is_link))
               <a href="{{url("/orders/pso/{$order->id}")}}" target="_blank">#{{$order->id}} - {{\App\Models\Orders\Pso::STATYS[$order->status_id]}}</a>
            @else
               <span>Осмотр #{{$order->id}} - {{\App\Models\Orders\Pso::STATYS[$order->status_id]}}</span>
            @endif

         </h2>
         <br/>
         <br/>

         <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

             <div class="view-field">
                 <span class="view-label">Статус</span>
                 <span class="view-value">{{ \App\Models\Orders\Pso::STATYS[$order->status_id]}}</span>
             </div>

             @if($order->work_user)
             <div class="view-field">
                 <span class="view-label">Сотрудник</span>
                 <span class="view-value">{{ $order->work_user->name}}</span>
             </div>

             @endif
            @if($order->city)
            <div class="view-field">
               <span class="view-label">Город</span>
               <span class="view-value">{{$order->city->title}}</span>
            </div>
             @endif

            <div class="view-field">
               <span class="view-label">Тип</span>
               <span class="view-value">{{ \App\Models\Orders\Pso::POSITION_TYPE[$order->position_type_id]}}</span>
            </div>

            @if($order->position_type_id == 1)

               <div class="view-field">
                  <span class="view-label">Точка осмотра</span>
                  <span class="view-value">{{($order->point_sale)?$order->point_sale->title:''}}</span>
               </div>

            @else

                 <div class="view-field">
                     <span class="view-label">Адрес осмотра</span>
                     <span class="view-value">{{$order->address}}</span>
                 </div>

            @endif



            <div class="view-field">
               <span class="view-label">Дата / Время</span>
               <span class="view-value">{{setDateTimeFormatRu($order->begin_date)}}</span>
            </div>



            <div class="view-field">
               <span class="view-label">Клиент</span>
               <span class="view-value">{{$order->insurer_title}}</span>
            </div>
            <div class="view-field">
               <span class="view-label">Телефон</span>
               <span class="view-value">{{$order->phone}}</span>
            </div>


            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

         </div>
      </div>







<script>

    function initActivForms() {


    }

</script>