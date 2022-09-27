<div class="form-horizontal">

     <table id="tableControl" class="tov-table">
          <tr>
               <th>Объект страхования</th>
               <th>Тип недвижимости</th>
               <th>Тариф</th>
          </tr>
          @foreach(\App\Models\Directories\Products\Data\Mortgage\BaseRateTitle::where('product_id', $product->id)->get() as $baserate)

               <tr onclick="openFancyBoxFrame('{{url("/directories/products/{$product->id}/edit/special-settings/mortgage/baserate-title/{$baserate->id}")}}')">
                    <td>{{ \App\Models\Directories\Products\Data\Mortgage\Mortgage::CLASS_REALTY[$baserate->class_realty] }}</td>
                    <td>{{ \App\Models\Directories\Products\Data\Mortgage\Mortgage::TYPE_REALTY[$baserate->type_realty] }}</td>
                    <td>{{ titleFloatFormat($baserate->tarife, 0, 1, 3) }}</td>
               </tr>

          @endforeach

     </table>


     <br/>
     <span class="btn btn-success pull-left" onclick="openFancyBoxFrame('{{url("/directories/products/{$product->id}/edit/special-settings/mortgage/baserate-title/0")}}')">Добавить</span>

</div>




<script>


    function initViewForm() {

    }


</script>