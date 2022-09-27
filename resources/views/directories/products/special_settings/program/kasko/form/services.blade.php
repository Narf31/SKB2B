
<table id="tableControl" class="tov-table">
    <tr>
        <th>Услуга</th>
        <th>Сумма</th>
    </tr>
    @foreach(\App\Models\Directories\Products\Data\Kasko\KaskoService::where('product_id', $product->id)
            ->where('program_id', $program->id)->get() as $service)
        <tr onclick="openFancyBoxFrame('{{url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/kasko/auto/services/{$service->id}")}}')">
            <td>{{\App\Models\Directories\Products\Data\Kasko\KaskoService::SERVIVES[$service->service_name]}}</td>
            <td>{{titleFloatFormat($service->payment_total)}}</td>
        </tr>

    @endforeach

</table>


<br/>
<span class="btn btn-success pull-left" onclick="openFancyBoxFrame('{{url("/directories/products/{$product->id}/edit/special-settings/program/{$program->id}/kasko/auto/services/0")}}')">Добавить</span>




<script>




    function initViewForm() {

    }



</script>
