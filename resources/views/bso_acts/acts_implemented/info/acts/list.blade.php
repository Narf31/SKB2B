<div class="divider"></div>
@if(sizeof($bso_act))



    <div class="form-horizontal">
        <table class="tov-table">
            <tr class="sort-row">
                <th>№ п\п</th>
                <th>№ акта</th>
                <th>Время создания</th>
                <th>Тип</th>
                <th>Создал</th>
                <th>Примечание</th>
            </tr>
            <tbody>
            @foreach($bso_act as $key => $act)
                <tr class="clickable-row" data-href="/bso_acts/acts_implemented/details/{{$act->id}}/" @if($act->target_date < $act->time_create) style="background-color: #ffd6cc;" @endif>
                    <td>{{$key+1}}</td>
                    <td>{{$act->act_number}}</td>
                    <td>{{setDateTimeFormatRu($act->time_create)}}</td>
                    <td>{{$act->act_name}}</td>
                    <td>{{($act->bso_manager)?$act->bso_manager->name:''}}</td>
                    <td>Срок передачи до: {{setDateTimeFormatRu($act->target_date, 1)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


@else

    <h1>Акты отсутствуют</h1>

@endif

