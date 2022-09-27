

<span class="btn btn-success pull-left" onclick="openFancyBoxFrame('{{url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/kasko/coefficients/{$category}/0")}}')">Добавить</span>

<br/>

<table class="tov-table">
    <tr>
        <th>Условия</th>
        <th>Коэффициент %</th>
    </tr>

    @foreach($coefficients as $coefficient)
        <tr onclick="openFancyBoxFrame('{{url("/directories/products/{$product_id}/edit/special-settings/program/{$program_id}/kasko/coefficients/{$category}/{$coefficient->id}")}}')">
            <td>{{$coefficient->getTitleTerms()}}</td>
            <td>{{titleFloatFormat($coefficient->tarife)}} %</td>
        </tr>

    @endforeach
</table>



<script>





</script>