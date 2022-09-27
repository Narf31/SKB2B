
<table class="table table-bordered text-left payments_table huck">
   <thead>
      <tr>
         <th>#</th>
         <th>Дата / Время</th>
         <th>Город</th>
         <th>Филиал</th>
         <th>Тип</th>
         <th>Сотрудник</th>
         <th>Продукт</th>
         <th>Страхователь</th>
         <th>Номер договора</th>
         <th>Сумма убытка</th>
      </tr>
   </thead>
   <tbody>
      @foreach($damages as $damage)
         <tr onclick="openPage('{{url("/orders/damages/{$damage->id}")}}')" style="cursor: pointer;" @if($damage->info) @if($damage->info->status_payments_id == 2) class="bg-green" @elseif($damage->info->status_payments_id == 3) class="bg-red"@endif @endif>
            <td>{{$damage->id}}</td>
            <td>{{setDateTimeFormatRu($damage->begin_date)}}</td>
            <td>{{$damage->city->title}}</td>
            <td>{{$damage->bso->supplier->title}}</td>
            <td>{{\App\Models\Orders\Damages::POSITION_TYPE[$damage->position_type_id]}} @if($damage->point_sale) - {{$damage->point_sale->title}} @endif</td>
            <td>{{$damage->work_user?$damage->work_user->name:''}}</td>
            <td>{{$damage->bso->product->title}}</td>
            <td>{{$damage->contract->insurer->title}}</td>
            <td>{{$damage->bso->bso_title}}</td>
            <td>
               @if($damage->info)
                  {{titleFloatFormat($damage->info->payments_total)}} - {{\App\Models\Orders\DamageOrder::STATUS_PAYMENT[$damage->info->status_payments_id]}}
               @else

               @endif

            </td>
         </tr>
      @endforeach
   </tbody>
</table>


<div class="row">
   <div id="page_list" class="easyui-pagination pull-right"></div>
</div>

<script>

   function startMainFunctions() {

   }

</script>