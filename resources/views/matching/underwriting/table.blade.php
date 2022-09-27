<table class="table table-bordered text-left payments_table huck">
    <thead>
    <tr>
        <th>№ заявки</th>
        <th>Категория</th>
        <th>Продукт</th>
        <th>Страхователь</th>
        <th>Агент</th>
        <th>Руководитель</th>
        <th>Куратор</th>
        <th>Статус</th>
        <th>Сотрудник</th>
    </tr>
    </thead>

    <tbody>
    @foreach($matchings as $matching)

        <tr @if($matching->is_urgently == 1) style="background-color: #ffcccc;" @endif>
            <td style="cursor: pointer;">
                {{$matching->id}}
            </td>
            <td>{{\App\Models\Contracts\Matching::CATEGORY[$matching->category_id]}} #{{$matching->contract_id}}</td>
            <td>{{$matching->category_title}}</td>
            <td>{{$matching->insurer_title}}</td>
            <td>@php
                    $agent = ($matching->contract)?$matching->contract->agent : null;
                    $agent_name = '';
                    if($agent) {
                        $agent_name = $agent->name;
                        if($agent->organization){
                            $agent_name .= ' - '.$agent->organization->title;
                        }
                    }
                @endphp
                {{$agent_name}}
            </td>
            <td>{{($matching->initiator_parent)?$matching->initiator_parent->name:''}}</td>
            <td>{{($matching->initiator_curator)?$matching->initiator_curator->name:''}}</td>
            <td>{{\App\Models\Contracts\Matching::STATYS[$matching->status_id]}}</td>
            <td>

                @if($matching->check_user)

                    {{$matching->check_user->name}}

                    <br/>
                    {{setDateTimeFormatRu($matching->check_date)}}
                    <br/>
                    <span style="font-size: 24px;@if($matching->isUnderLog() == true) color:green; @endif" onclick="openFancyBoxFrame('{{url("/matching/underwriting/{$matching->id}/log")}}')">
                        <i class="fa fa-info-circle"></i>
                    </span>

                    <span class="pull-right" style="color:red;font-size: 24px;" onclick="clearCheckUser({{$matching->id}})">
                        <i class="fa fa-times"></i>
                    </span>

                    <br/>

                    <a href="{{url("/matching/underwriting/{$matching->id}/")}}">
                        Открыть
                    </a>

                @elseif($matching->isUnderNotWork() == 0)
                    <span style="font-size: 24px;@if($matching->isUnderLog() == true) color:green; @endif" onclick="openFancyBoxFrame('{{url("/matching/underwriting/{$matching->id}/log")}}')">
                            <i class="fa fa-info-circle"></i>
                        </span>
                    <span class="btn btn-success" onclick="setCheckUser({{$matching->id}})">Взять в работу</span>
                @else

                    <span style="font-size: 24px;@if($matching->isUnderLog() == true) color:green; @endif" onclick="openFancyBoxFrame('{{url("/matching/underwriting/{$matching->id}/log")}}')">
                            <i class="fa fa-info-circle"></i>
                        </span>

                @endif
            </td>

        </tr>
    @endforeach

    </tbody>

</table>