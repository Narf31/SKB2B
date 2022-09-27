<div class="form-group">
    <h2 class="col-sm-12">{{$name}}
        <span class="btn btn-success pull-right" style="width: 30px;height: 25px;font-size: 10px;" onclick="openFancyBoxFrame('{{ url("/directories/products/{$product->id}/edit/info/{$type}/0/edit") }}')">
            <i class="fa fa-plus"></i>
        </span>
    </h2>



    <div class="col-sm-12">

        <table class="tov-table-no-sort">
            <thead>
            <tr>
                <th>Название</th>
                <th></th>
            </tr>
            </thead>
            <tbody class="sortable_table_columns" data-type="{{$type}}">
            @if($lists)
                @foreach($lists as $list)
                    <tr id="infodata-{{$list->id}}">
                        <td>{{$list->title}}</td>
                        <td>
                            <span class="btn btn-primary btn-right" onclick="openFancyBoxFrame('{{ url("/directories/products/{$product->id}/edit/info/{$type}/{$list->id}/edit") }}')">
                                Открыть
                            </span>
                        </td>
                    </tr>
                @endforeach
            @endif

            </tbody>
        </table>


    </div>
</div>