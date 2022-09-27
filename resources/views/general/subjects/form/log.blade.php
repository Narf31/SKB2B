
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal">

                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Дата</th>
                        <th>Пользователь</th>
                        <th>Статус</th>
                    </tr>
                    @if($general->logs)
                        @foreach($general->logs as $log)
                            <tr>
                                <td>{{ setDateTimeFormatRu($log->date_sent) }}</td>
                                <td>{{ $log->user ? $log->user->name : '' }}</td>
                                <td>{!! $log->text !!}</td>
                            </tr>
                        @endforeach
                    @endif
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