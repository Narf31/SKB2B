
<table id="tableControl" class="tov-table">
    <tr>
        <th>Продукт</th>
        <th>Страховая сумма до</th>
        <th>Тарифы</th>
    </tr>
    @foreach(\App\Models\Directories\Products\Data\Kasko\KaskoProduct::where('product_id', $product->id)
            ->where('program_id', $program->id)->get() as $k_product)
        <tr onclick="openFancyBoxFrame('{{url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/kasko/auto/product/{$k_product->id}")}}')">
            <td>{{\App\Models\Directories\Products\Data\Kasko\KaskoProduct::PRODUCT[$k_product->kasko_product_id]}}</td>
            <td>{{titleFloatFormat($k_product->amount)}}</td>
            <td>{{titleFloatFormat($k_product->payment_tarife)}} @if($k_product->kasko_product_id != 4) % @endif</td>
        </tr>

    @endforeach

</table>


<br/>
<span class="btn btn-success pull-left" onclick="openFancyBoxFrame('{{url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/kasko/auto/product/0")}}')">Добавить</span>




<script>




    function initViewForm() {

    }



</script>
