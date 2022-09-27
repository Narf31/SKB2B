
<table id="tableControl" class="tov-table">
    <tr>
        <th>Страховая сумма</th>
        <th>Тариф %</th>
    </tr>

    @foreach(\App\Models\Directories\Products\Data\Kasko\KaskoEquipment::where('product_id', $product->id)
            ->where('program_id', $program->id)->get() as $equipmen)
        <tr onclick="openFancyBoxFrame('{{url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/kasko/auto/equipment/{$equipmen->id}")}}')">
            <td>от {{titleFloatFormat($equipmen->amount_to)}} по {{titleFloatFormat($equipmen->amount_from)}}</td>
            <td>{{titleFloatFormat($equipmen->payment_tarife)}} %</td>
        </tr>

     @endforeach

</table>


<br/>
<span class="btn btn-success pull-left" onclick="openFancyBoxFrame('{{url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/kasko/auto/equipment/0")}}')">Добавить</span>




<script>




    function initViewForm() {

    }



</script>



