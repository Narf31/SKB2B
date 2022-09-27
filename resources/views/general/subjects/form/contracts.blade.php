
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">

                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Номер договора</th>
                        <th>Период действия</th>
                        <th>Продукт</th>
                        <th>Статус</th>
                        <th>Агент</th>
                        <th>Агент - Организация</th>
                    </tr>
                    @foreach($general->contracts()->get() as $contract)
                        <tr>
                            <td><a href="{{url("/contracts/online/{$contract->id}")}}" target="_blank">{{$contract->bso->bso_title}}</a></td>
                            <td>
                                {{setDateTimeFormatRu($contract->begin_date, 1)}} - {{setDateTimeFormatRu($contract->end_date, 1)}}
                            </td>

                            <td>{{$contract->product->title}}</td>
                            <td>{{\App\Models\Contracts\Contracts::STATYS[$contract->statys_id]}}</td>

                            <td>{{$contract->agent->name}}</td>
                            <td>{{$contract->agent->organization->title}}</td>

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