<div class="divider"></div>
@if(sizeof($bso_items))



    <div class="form-horizontal">
        <table class="tov-table">
            <tr class="sort-row">
                <th>№ п\п</th>
                <th><input type="checkbox" id='check_all' onclick='check_all_bso(this)'/></th>
                <th>Агент</th>
                <th>СК</th>
                <th>Вид/Тип БСО</th>
                <th>№ БСО</th>
                <th></th>
            </tr>
            <tbody>

            @foreach($bso_items as $key => $bso)



                <tr class="clickable-row">
                    <td>{{$key+1}}</td>
                    <td><input type='checkbox' 	value='{{$bso->id}}' class='bso_item_checkbox' onchange='show_checked_options()'></td>
                    <td>{{$bso->agent->name}}</td>
                    <td>{{$bso->supplier->title}}</td>
                    <td>{{$bso->type->title}}</td>
                    <td>
                        @if(isset($bso) && $bso->file_id > 0)
                            <a href="{{ url($bso->scan->url) }}" target="_blank">{{$bso->bso_title}}</a>
                        @else
                        {{$bso->bso_title}}
                        @endif

                    </td>
                    <td><span class="btn btn-success btn-right" onclick="add_spoiled({{$bso->id}})">Открыть</span></td>
                </tr>

            @endforeach


            </tbody>
        </table>
    </div>


@else

    <h1>Данные отсутствуют</h1>

@endif

