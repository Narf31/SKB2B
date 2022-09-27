@if($acts)
    @foreach($acts as $key => $obj)
        <tr class="clickable-row-blank" data-href="/bso/transfer/?bso_cart_id={{$obj['id']}}" style="cursor: pointer;">
            @include('bso_acts.acts_reserve.row', ["act" => \App\Models\BSO\BsoCarts::getCarsId($obj["id"])])
        </tr>
    @endforeach
@endif