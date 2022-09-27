<table class="table table-bordered bso_items_table">
    <thead>
        <tr>
            <th class="text-center">
                <input type="checkbox" name="all_bso_items">
                (<span class="total_bso_count">{{$bso_items->count()}}</span>)
            </th>
            <td>Номер БСО</td>
            <td>Статус</td>
        </tr>
    </thead>
    <tbody class="bso_items_table_tbody">
        @if(sizeof($bso_items))
            @foreach($bso_items as $bso_item)
                <tr>
                    <td class="text-center">
                        <input class="bso_item_checkbox" type="checkbox"  name="bso_item[]" value="{{$bso_item->id}}">
                    </td>
                    <td>{{$bso_item->bso_title}}</td>
                    <td>{{$bso_item->state ? $bso_item->state->title : ""}}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" class="text-center">Нет БСО</td>
            </tr>
        @endif
    </tbody>
</table>