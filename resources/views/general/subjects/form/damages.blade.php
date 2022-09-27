
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">

                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Номер договора</th>
                        <th>Продукт</th>
                        <th>Дата заявления</th>
                        <th>Статус</th>
                        <th>Дата оплаты</th>
                        <th>Агент</th>
                        <th>Агент - Организация</th>
                    </tr>
                    @foreach($general->damages as $damage)
                        <tr>
                            <td>{{$damage->bso->bso_title}}</td>
                            <td>
                                {{setDateTimeFormatRu($contract->begin_date, 1)}} - {{setDateTimeFormatRu($contract->end_date, 1)}}
                            </td>


                            <td>{{$damage->agent->name}}</td>
                            <td>{{$damage->agent->organization->title}}</td>

                        </tr>
                    @endforeach
                </table>

            </div>
        </div>
    </div>
</div>




<script>

    function startMainFunctions()
    {



    }




</script>