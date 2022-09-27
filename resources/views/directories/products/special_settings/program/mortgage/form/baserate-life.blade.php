<div class="form-horizontal">

     <table id="tableControl" class="tov-table">
          <tr>
               <th>Возраст</th>
               <th>Муж.</th>
               <th>Жен.</th>
          </tr>
          @foreach(\App\Models\Directories\Products\Data\Mortgage\BaseRateLife::where('product_id', $product->id)->get() as $baserate)

               <tr onclick="openFancyBoxFrame('{{url("/directories/products/{$product->id}/edit/special-settings/mortgage/baserate-life/{$baserate->id}")}}')">
                    <td>{{$baserate->age_from}} - {{$baserate->age_to}}</td>
                    <td>{{ titleFloatFormat($baserate->tarife_man, 0, 1, 3) }}</td>
                    <td>{{ titleFloatFormat($baserate->tarife_woman, 0, 1, 3) }}</td>
               </tr>

          @endforeach

     </table>


     <br/>
     <span class="btn btn-success pull-left" onclick="openFancyBoxFrame('{{url("/directories/products/{$product->id}/edit/special-settings/mortgage/baserate-life/0")}}')">Добавить</span>

</div>




<script>


    function initViewForm() {

    }


</script>