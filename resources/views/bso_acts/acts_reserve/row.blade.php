<td>{{$act->id}}</td>
<td>{{$act->type ? $act->type->title : ""}}</td>
<td>{{setDateTimeFormatRu($act->time_create)}}</td>
<td>{{($act->bso_manager)?$act->bso_manager->name:''}} </td>
<td>{{($act->user_to)?$act->user_to->name.' - '.$act->user_to->organization->title:''}} </td>
<td>{{$act->point_sale ? $act->point_sale->title : ""}}</td>
