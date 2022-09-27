<div class="row" id="actions" style="display: none">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <a id="delete_items" class="btn btn-danger btn-right">Удалить выбранные</a>
    </div>
</div>
<div class="row">
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <table class="table table-bordered bso_items_table">
            <thead>
                <tr>
                    <th class="text-center">
                        <input type="checkbox" name="all_bso_items">
                        (<span class="total_bso_count">{{$act->bso_items->count()}}</span>)
                    </th>
                    <th>Тип</th>
                    <th>Номер БСО</th>
                    <th>БСО номер бланка</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody class="bso_items_table_tbody">
                @if(sizeof($act->bso_items))
                    @foreach($act->bso_items as $bso_item)
                        <tr>
                            <td class="text-center">
                                <input class="bso_item_checkbox" type="checkbox"  name="bso_item[]" value="{{$bso_item->id}}">
                            </td>
                            <td>{{$bso_item->product ? $bso_item->product->title : "" }}</td>
                            <td>{{$bso_item->bso_title}}</td>
                            <td></td>
                            <td>{{$bso_item->state ? $bso_item->state->title : ""}}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" class="text-center">Нет БСО</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">

    </div>
</div>
