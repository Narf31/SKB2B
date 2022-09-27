<div class="divider"></div>
@if(sizeof($payments))



    <div class="form-horizontal">
        <table class="tov-table">
            <tr class="sort-row">
                <th>№ п\п</th>
                <th><input type="checkbox" id='check_all' onclick='check_all_bso(this)'/></th>
                <th>Агент</th>
                <th>СК</th>
                <th>Вид страхования</th>
                <th>Страхователь</th>
                <th>№ Бланка</th>
                <th>№ Квитанции</th>
                <th>Способ акцепта</th>
            </tr>
            <tbody>

            @foreach($payments as $key => $payment)



                <tr class="clickable-row">
                    <td>{{$key+1}}</td>
                    <td><input type='checkbox' 	value='{{$payment->pay_id}}' class='bso_item_checkbox' onchange='show_checked_options()'></td>
                    <td>{{$payment->bso->agent->name}}</td>
                    <td>{{$payment->bso->supplier->title}}</td>
                    <td>{{$payment->bso->type->title}}</td>
                    <td>{{$payment->contract->insurer->title}}</td>
                    <td>{{$payment->bso->bso_title}}</td>
                    <td>{{$payment->bso_receipt}}</td>
                    <td>{{\App\Models\Contracts\Contracts::KIND_ACCEPTANCE[$payment->contract->kind_acceptance]}}</td>
                </tr>

            @endforeach


            </tbody>
        </table>
    </div>


@else

    <h1>Договоры отсутствуют</h1>

@endif

