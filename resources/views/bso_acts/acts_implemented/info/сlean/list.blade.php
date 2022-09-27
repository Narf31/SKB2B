<div class="divider"></div>
@if(sizeof($bso_items))



    <div class="form-horizontal">
        <table class="tov-table">
            <tr class="sort-row">
                <th>№ п\п</th>
                <th><input type="checkbox" id='check_all' onclick='check_all_bso(this)'/></th>
                <th>Агент</th>
                <th>Событие / Статус</th>
                <th>Вид/Тип БСО</th>
                <th>№ БСО</th>
            </tr>
            <tbody>

            @foreach($bso_items as $key => $bso)



                <tr class="clickable-row">
                    <td>{{$key+1}}</td>
                    <td><input type='checkbox' 	value='{{$bso->id}}' class='bso_item_checkbox' onchange='show_checked_options()'></td>
                    <td>{{$bso->user->name}}</td>
                    <td>{{$bso->location ? $bso->location->title : ""}} / {{$bso->state ? $bso->state->title : ""}}</td>
                    <td>{{$bso->type->title}}</td>
                    <td>{{$bso->bso_title}}</td>
                </tr>

            @endforeach


            </tbody>
        </table>
    </div>


@else

    <h1>Данные отсутствуют</h1>

@endif

