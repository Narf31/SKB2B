<div class="col-sm-12">
    <h2>Входит группу
        @if($state == 'edit')
            <span class="btn btn-primary pull-right" onclick="openFancyBoxFrame('{{url("/general/subjects/edit/{$general->id}/action/interactions-connections/0?type=2")}}')">Добавить</span>
        @endif
    </h2>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <table class="table table-striped table-bordered">
        <tr>
            <th width="40%">Организация</th>
            <th>Должность</th>
            <th>Дата начало отношений</th>
            <th>Дата завершения отношений</th>
        </tr>
        @foreach($general->interactions_connections_type(2) as $ic)
            <tr @if($state == 'edit') style="cursor: pointer;" onclick="openFancyBoxFrame('{{url("/general/subjects/edit/{$general->id}/action/interactions-connections/{$ic->id}?type=2")}}')" @endif>
                <td>{{$ic->general_organization->title}}</td>
                <td>{{$ic->job_position}}</td>
                <td>{{setDateTimeFormatRu($ic->date_from, 1)}}</td>
                <td>{{setDateTimeFormatRu($ic->date_to, 1)}}</td>
            </tr>
        @endforeach
    </table>
</div>




<div class="col-sm-12">
    <h2>Учередители
        @if($state == 'edit')
            <span class="btn btn-primary pull-right" onclick="openFancyBoxFrame('{{url("/general/subjects/edit/{$general->id}/action/founders/0?type=1")}}')">Добавить</span>
        @endif
    </h2>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <table class="table table-striped table-bordered">
        <tr>
            <th width="40%">Название/ФИО</th>
            <th>Доля %</th>
            <th>Доля сумма</th>
            <th></th>
        </tr>

        @foreach($general->founders_type(1) as $founder)
            <tr>
                <td><a href="{{url("/general/subjects/edit/{$founder->general_founders_id}")}}" target="_blank">{{$founder->general_founders->title}}</a></td>
                <td>{{titleFloatFormat($founder->share)}}</td>
                <td>{{titleFloatFormat($founder->share_sum)}}</td>
                <td><span class="btn btn-info pull-right" onclick="openFancyBoxFrame('{{url("/general/subjects/edit/{$general->id}/action/founders/{$founder->id}?type=1")}}')">Открыть</span> </td>
            </tr>
        @endforeach

    </table>
</div>



<div class="col-sm-12">
    <h2>Бенефициары
        @if($state == 'edit')
            <span class="btn btn-primary pull-right" onclick="openFancyBoxFrame('{{url("/general/subjects/edit/{$general->id}/action/founders/0?type=2")}}')">Добавить</span>
        @endif
    </h2>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <table class="table table-striped table-bordered">
        <tr>
            <th width="40%">Название/ФИО</th>
            <th>Доля %</th>
            <th>Доля сумма</th>
            <th></th>
        </tr>

        @foreach($general->founders_type(2) as $founder)
            <tr>
                <td><a href="{{url("/general/subjects/edit/{$founder->general_founders_id}")}}" target="_blank">{{$founder->general_founders->title}}</a></td>
                <td>{{titleFloatFormat($founder->share)}}</td>
                <td>{{titleFloatFormat($founder->share_sum)}}</td>
                <td><span class="btn btn-info pull-right" onclick="openFancyBoxFrame('{{url("/general/subjects/edit/{$general->id}/action/founders/{$founder->id}?type=2")}}')">Открыть</span> </td>
            </tr>
        @endforeach

    </table>
</div>



<div class="col-sm-12">
    <h2>Сотрудники</h2>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <table class="table table-striped table-bordered">
        <tr>
            <th width="40%">ФИО</th>
            <th>Должность</th>
        </tr>

        @foreach($general->employees_podft as $employees_podft)
            <tr>
                <td><a href="{{url("/general/subjects/edit/{$employees_podft->general->id}")}}" target="_blank">{{$employees_podft->general->title}}</a></td>
                <td>{{$employees_podft->job_position}}</td>
            </tr>
        @endforeach

    </table>
</div>

<script>
    

</script>