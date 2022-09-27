@if(sizeof($matchings))
    <table class="tov-table" >
        <tr>
            <th>Условия</th>
        </tr>
        @foreach($matchings as $matching)
            <tr href="{{url("/directories/insurance_companies/{$id}/bso_suppliers/{$bso_supplier_id}/hold_kv/{$hold_kv_id}/matching-terms/{$group_id}/{$matching->type}/{$matching->id}")}}" class="clickable-row fancybox fancybox.iframe">
                <td>{{ $matching->title  }}</td>
            </tr>
        @endforeach
    </table>
@else
    {{ trans('form.empty') }}
@endif