<div class="divider"></div>
@if(sizeof($bsos))



    <div class="form-horizontal">
        <table class="tov-table">
            <tr class="sort-row">
                <th>№ п\п</th>
                <th><input type="checkbox" id='check_all' onclick='check_all_bso(this)'/></th>
                <th>№ Бланка</th>
                <th>Вид страхования</th>
                <th>Страхователь</th>
                <th>Агент</th>
            </tr>
            <tbody>

            @foreach($bsos as $key => $bso)



                <tr class="clickable-row">
                    <td>{{$key+1}}</td>
                    <td><input type='checkbox' 	value='{{$bso->id}}' class='bso_item_checkbox' onchange='show_checked_options()'></td>
                    <td>{{$bso->bso_title}}</td>
                    <td>{{$bso->type->title}}</td>
                    <td>{{($bso->contract && $bso->contract->insurer)?$bso->contract->insurer->title:''}}</td>
                    <td>{{$bso->agent->name}}</td>
                </tr>

            @endforeach


            </tbody>
        </table>
    </div>


@else

    <h1>Договоры отсутствуют</h1>

@endif

