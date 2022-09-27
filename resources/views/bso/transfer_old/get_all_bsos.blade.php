<table class="bso_table" style="float: left;">
    <tr>
        <td>№</td>
        <td>СК</td>
        <td>Тип</td>
        <td>БСО</td>
        <td>Выбор <input type="checkbox" id="check_all" /></td>
    </tr>
    @if(sizeof($bso_items))

        @foreach($bso_items as $key => $bso)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$bso->supplier->title}}</td>
                <td>{{$bso->type->title}}</td>
                <td>{{$bso->bso_title}}</td>
                <td><input type="checkbox" class="cb_bso" bso_id="{{$bso->id}}" /></td>
            </tr>
        @endforeach


    @endif

</table>
<input type="button" id="move_to_cart" value="Добавить в корзину" />