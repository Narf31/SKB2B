@if($acts)
@foreach($acts as $key => $obj)
<tr class="clickable-row-blank" data-href="/bso_acts/show_bso_act/{{$obj['id']}}/" style="cursor: pointer;">
    @php
    $act = \App\Models\BSO\BsoActs::getActId($obj["id"]);
    @endphp
    <td>{{$act->act_number}}</td>
    <td>{{$act->act_name}}</td>
    <td>{{setDateTimeFormatRu($act->time_create)}}</td>
    <td>{{($act->bso_manager)?$act->bso_manager->name.' - '.$act->bso_manager->organization->title:''}} </td>
    <td>{{($act->user_to)?$act->user_to->name.' - '.$act->user_to->organization->title:''}} </td>

    <td>{{($act->point_sale)?$act->point_sale->title:''}}</td>
</tr>
@endforeach
@endif