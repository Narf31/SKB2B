
<div class="row form-horizontal" >

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">


        <table class="table table-striped table-bordered">
            <tr>
                <th>Дата</th>
                <th>Назначил</th>
                <th>Статус</th>
                <th>Примечание</th>
            </tr>
            @if($contract->history_logs)

                @foreach($contract->history_logs as $log)

                    <tr>
                        <td>{{ setDateTimeFormatRu($log->created_at) }}</td>
                        <td>{{ $log->user ? $log->user->name : '' }}</td>
                        <td>{{ $log->status_title }}</td>
                        <td>{!! $log->text !!}</td>

                    </tr>
                @endforeach
            @endif
        </table>



    </div>



</div>


<script>

    function initTab() {





    }

    function saveTab() {

    }


</script>