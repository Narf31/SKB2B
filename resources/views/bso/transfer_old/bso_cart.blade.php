<div id="group_by_items">

    <table class="bso_table">
        <tr>
            <th>№</th>
            <th>СК</th>
            <th>Тип</th>
            <th>Номер</th>
            <th>Удалить</th>
        </tr>
        @if(sizeof($bso_cart->bso_items))
            @foreach($bso_cart->bso_items as $key => $bso)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$bso->supplier->title}}</td>
                    <td>{{$bso->type->title}}</td>
                    <td>{{$bso->bso_title}}</td>
                    <td>
                        <span class="remove_button" bso_id="{{$bso->id}}" style="color: red;font-size: 18px;"><i class="fa fa-close"></i></span>
                    </td>
                </tr>
            @endforeach
        @endif
    </table>
</div>
<div id="group_by_types" style="display: none;">

    <table class="bso_table">
        <tr>
            <th>№</th>
            <th>СК</th>
            <th>Тип</th>
            <th>Кол-во</th>
            <th>Удалить</th>
        </tr>
        @if(sizeof($bso_items_group))
            @foreach($bso_items_group as $key => $bso_group)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$bso_group->sk_title}}</td>
                    <td>{{$bso_group->type_title}}</td>
                    <td>{{$bso_group->qty}}</td>
                    <td>
                        <span class="btn remove_type_button" type_id="{{$bso_group->type_bso_id}}" style="color: red;font-size: 18px;"><i class="fa fa-close"></i></span>
                    </td>
                </tr>
            @endforeach
        @endif
    </table>
</div>