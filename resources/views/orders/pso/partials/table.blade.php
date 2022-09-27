
<table class="table table-bordered text-left payments_table huck">
   <thead>
      <tr>
         <th>#</th>
         <th>Дата / Время</th>
         <th>Город</th>
         <th>Тип</th>
         <th>Сотрудник</th>
         <th>Продукт</th>
         <th>Страхователь</th>
      </tr>
   </thead>
   <tbody>
      @foreach($orders as $order)
         <tr onclick="openPage('{{url("/orders/pso/{$order->id}")}}')" style="cursor: pointer;" @if($order->info) @if($order->info->status_payments_id == 2) class="bg-green" @elseif($order->info->status_payments_id == 3) class="bg-red"@endif @endif>
            <td>{{$order->id}}</td>
            <td>{{setDateTimeFormatRu($order->begin_date)}}</td>
            <td>{{$order->city->title}}</td>
            <td>{{\App\Models\Orders\Pso::POSITION_TYPE[$order->position_type_id]}} @if($order->point_sale) - {{$order->point_sale->title}} @endif</td>
            <td>{{$order->work_user?$order->work_user->name:''}}</td>
            <td>{{$order->product->title}}</td>
            <td>{{$order->insurer_title}}</td>
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