<table class="table table-info">
    <thead>
    <tr>
        <th>
            <input type="checkbox" id='check_all' onclick='checkAll(this)'/>
        </th>
        <th>Вид страхования</th>
        <th>Дата оформления</th>
        <th>Период действия</th>
        <th>Страхователь</th>
        <th>Объект страхования</th>
        <th>Страховая сумма</th>
        <th>Страховая премия</th>
    </tr>
    </thead>
    <tbody>
    @if(sizeof($drafts))
        @foreach($drafts as $draft)
            <tr style="cursor: pointer;">
                <td>
                    <input type='checkbox' value='{{$draft->id}}' class='item_checkbox' onchange='showCheckedOptions()'>
                </td>
                <td onclick="openPage('{{url("/contracts/online/$draft->id")}}')">{{$draft->product->title}} @if($draft->program) - {{$draft->program->title}} @endif</td>
                <td onclick="openPage('{{url("/contracts/online/$draft->id")}}')">{{setDateTimeFormatRu($draft->updated_at, 1)}}</td>
                <td onclick="openPage('{{url("/contracts/online/$draft->id")}}')">{{setDateTimeFormatRu($draft->begin_date, 1)}} - {{setDateTimeFormatRu($draft->end_date, 1)}}</td>
                <td onclick="openPage('{{url("/contracts/online/$draft->id")}}')">{{($draft->insurer)?$draft->insurer->title:''}}</td>
                <td onclick="openPage('{{url("/contracts/online/$draft->id")}}')">{{($draft->object_insurer)?$draft->object_insurer->title:''}}</td>

                <td onclick="openPage('{{url("/contracts/online/$draft->id")}}')">{{($draft->insurance_amount && $draft->insurance_amount > 0)?titleFloatFormat($draft->insurance_amount, 0, 1):''}}</td>
                <td onclick="openPage('{{url("/contracts/online/$draft->id")}}')">{{($draft->payment_total)?titleFloatFormat($draft->payment_total, 0, 1):''}}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>