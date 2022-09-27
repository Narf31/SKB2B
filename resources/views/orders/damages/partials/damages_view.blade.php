      <div class="row form-horizontal">
         <h2 class="inline-h1">
            @if(isset($is_link))
               <a href="{{url("/orders/damages/{$damage->id}")}}" target="_blank">#{{$damage->id}} - {{\App\Models\Orders\Damages::STATYS[$damage->status_id]}}</a>
            @else
               <span>Убыток #{{$damage->id}} - {{\App\Models\Orders\Damages::STATYS[$damage->status_id]}}</span>
            @endif

         </h2>
         <br/>
         <br/>

         <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="view-field">
               <span class="view-label">Город</span>
               <span class="view-value">{{$damage->city->title}}</span>
            </div>


            <div class="view-field">
               <span class="view-label">Тип</span>
               <span class="view-value">{{ \App\Models\Orders\Damages::POSITION_TYPE[$damage->position_type_id]}}</span>
            </div>

            @if($damage->position_type_id == 1)

               <div class="view-field">
                  <span class="view-label">Точка осмотра</span>
                  <span class="view-value">{{($damage->point_sale)?$damage->point_sale->title:''}}</span>
               </div>

            @endif

            <div class="view-field">
               <span class="view-label">Дата / Время</span>
               <span class="view-value">{{setDateTimeFormatRu($damage->begin_date)}}</span>
            </div>

            <div class="view-field">
               <span class="view-label">Адрес осмотра</span>
               <span class="view-value">{{$damage->address}}</span>
            </div>

            <div class="view-field">
               <span class="view-label">Страхователь</span>
               <span class="view-value">{{\App\Models\Orders\Damages::INSURER_TYPE[$damage->insurer_type_id]}}</span>
            </div>
            <div class="view-field">
               <span class="view-label">Телефон</span>
               <span class="view-value">{{$damage->phone}}</span>
            </div>
            <div class="view-field">
               <span class="view-label">Email</span>
               <span class="view-value">{{$damage->email}}</span>
            </div>

            <div class="view-field">
               <span class="view-label">Сумма убытка ({{\App\Models\Orders\DamageOrder::STATUS_PAYMENT[($damage->info)?$damage->info->status_payments_id:0]}})</span>
               <span class="view-value">{{titleFloatFormat(($damage->info)?$damage->info->payments_total:0)}}</span>
            </div>

            <div class="view-field">
               {{$damage->comments}}
            </div>




            <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>

         </div>
      </div>







<script>

    function initActivForms() {


    }

</script>