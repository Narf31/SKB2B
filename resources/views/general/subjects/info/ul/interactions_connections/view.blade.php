<div class="col-sm-12">
    <h2>Входит группу
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
            <tr>
                <td><a href="{{url("/general/subjects/edit/{$founder->general_founders_id}")}}" target="_blank">{{$founder->general_founders->title}}</a></td>
                <td>{{$ic->job_position}}</td>
                <td>{{setDateTimeFormatRu($ic->date_from, 1)}}</td>
                <td>{{setDateTimeFormatRu($ic->date_to, 1)}}</td>
            </tr>
        @endforeach
    </table>
</div>




<div class="col-sm-12">
    <h2>Учередители
    </h2>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <table class="table table-striped table-bordered">
        <tr>
            <th width="40%">Название/ФИО</th>
            <th>Доля %</th>
            <th>Доля сумма</th>
        </tr>

        @foreach($general->founders_type(1) as $founder)
            <tr>
                <td><a href="{{url("/general/subjects/edit/{$founder->general_founders_id}")}}" target="_blank">{{$founder->general_founders->title}}</a></td>

                <td>{{titleFloatFormat($founder->share)}}</td>
                <td>{{titleFloatFormat($founder->share_sum)}}</td>
            </tr>
        @endforeach

    </table>
</div>



<div class="col-sm-12">
    <h2>Бенефициары
    </h2>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <table class="table table-striped table-bordered">
        <tr>
            <th width="40%">Название/ФИО</th>
            <th>Доля %</th>
            <th>Доля сумма</th>
        </tr>

        @foreach($general->founders_type(2) as $founder)
            <tr>
                <td>{{$founder->general_founders->title}}</td>
                <td>{{titleFloatFormat($founder->share)}}</td>
                <td>{{titleFloatFormat($founder->share_sum)}}</td>
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