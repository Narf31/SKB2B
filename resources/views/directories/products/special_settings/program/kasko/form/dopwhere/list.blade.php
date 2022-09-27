

<span class="btn btn-success pull-left" onclick="openFancyBoxFrame('{{url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/kasko/dopwhere/{$category}/0")}}')">Добавить</span>

<br/>

<table class="tov-table">
    <tr>
        <th>Условия</th>
        <th>Тариф %</th>
    </tr>

    @foreach($dopwheres as $dopwhere)
        <tr onclick="openFancyBoxFrame('{{url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/kasko/dopwhere/{$category}/{$dopwhere->id}")}}')">
            <td>{{$dopwhere->getTitleTerms()}}</td>
            <td>{{titleFloatFormat($dopwhere->tarife)}} %</td>
        </tr>

    @endforeach
</table>



<script>





</script>