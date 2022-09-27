<table class="table table-bordered text-left payments_table huck">
    <thead>
    <tr>
        <th>№ заявки</th>
        <th>Категория</th>
        <th>Продукт</th>
        <th>Страхователь</th>
        <th>Инициатор</th>
        <th>Руководитель</th>
        <th>Куратор</th>
        <th>Статус</th>
        <th>Сотрудник</th>
    </tr>
    </thead>

    <tbody>
    @foreach($matchings as $matching)


        <tr>
            <td style="cursor: pointer;">
                {{$matching->id}}
            </td>
            <td>{{\App\Models\Contracts\Matching::CATEGORY[$matching->category_id]}}</td>
            <td>{{$matching->category_title}}</td>
            <td>{{$matching->insurer_title}}</td>
            <td>{{($matching->initiator_user)?$matching->initiator_user->name:''}}</td>
            <td>{{($matching->initiator_parent)?$matching->initiator_parent->name:''}}</td>
            <td>{{($matching->initiator_curator)?$matching->initiator_curator->name:''}}</td>
            <td>{{\App\Models\Contracts\Matching::STATYS[$matching->status_id]}}</td>
            <td>

                @if($matching->check_user)

                    {{$matching->check_user->name}}

                    <br/>
                    {{setDateTimeFormatRu($matching->check_date)}}
                    <br/>
                    <span style="font-size: 24px;@if($matching->isUnderLog() == true) color:green; @endif" onclick="openFancyBoxFrame('{{url("/matching/security-service/{$matching->id}/log")}}')">
                        <i class="fa fa-info-circle"></i>
                    </span>

                    <span class="pull-right" style="color:red;font-size: 24px;" onclick="clearCheckUser({{$matching->id}})">
                        <i class="fa fa-times"></i>
                    </span>

                    <br/>

                    <a href="{{url("/matching/security-service/{$matching->id}/")}}">
                        Открыть
                    </a>

                @elseif($matching->isUnderNotWork() == 0)
                    <span style="font-size: 24px;@if($matching->isUnderLog() == true) color:green; @endif" onclick="openFancyBoxFrame('{{url("/matching/security-service/{$matching->id}/log")}}')">
                            <i class="fa fa-info-circle"></i>
                        </span>
                    <span class="btn btn-success" onclick="setCheckUser({{$matching->id}})">Взять в работу</span>
                @else

                    <span style="font-size: 24px;@if($matching->isUnderLog() == true) color:green; @endif" onclick="openFancyBoxFrame('{{url("/matching/security-service/{$matching->id}/log")}}')">
                            <i class="fa fa-info-circle"></i>
                        </span>

                @endif
            </td>

        </tr>
    @endforeach

    </tbody>

</table>