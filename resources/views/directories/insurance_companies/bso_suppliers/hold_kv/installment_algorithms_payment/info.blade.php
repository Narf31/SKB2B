@if(sizeof($algorithms))
    <table class="tov-table" >
        <tr>
            <th>Названия</th>
            <th>Кол-во платежей</th>
            <th>Андерайтер</th>
        </tr>
        @foreach($algorithms as $algorithm)
            <tr href="{{url("/directories/insurance_companies/{$id}/bso_suppliers/{$bso_supplier_id}/hold_kv/{$hold_kv_id}/installment_algorithms_payment/{$group_id}/{$algorithm->id}")}}" class="clickable-row fancybox fancybox.iframe">
                <td>{{ $algorithm->info->title  }}</td>
                <td>{{ $algorithm->info->quantity  }}</td>
                <td>{{ ($algorithm->is_underwriting == 1) ? "Да" : "Нет"  }}</td>
            </tr>
        @endforeach
    </table>
@else
    {{ trans('form.empty') }}
@endif