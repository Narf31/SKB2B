<table class="bso_table">
    <thead>
    <tr>
        <th>№ п/п</th>
        <th>Филиал</th>
        <th>Вид страхования</th>
        <th>№ полиса / квит. / сер.карт с</th>
        <th>Событие</th>
        <th>Статус</th>
    </tr>
    </thead>
    <tbody>
        @if(sizeof($act->logs))
            @foreach($act->logs as $key => $bso_log)

                @php($bso = $bso_log->bso)
                @if($bso)
                <tr>
                    <td>{{$key+1}}</td>
                    <td>{{$bso->supplier->title}}</td>
                    <td>{{$bso->type->title}}</td>
                    <td>
                        @if(auth()->user()->hasPermission('bso', 'items'))
                            <a href="{{url("/bso/items/{$bso->id}/")}}">{{$bso->bso_title}}</a>
                        @else
                            {{$bso->bso_title}}
                        @endif
                    </td>
                    <td>{{$bso_log->bso_location->title}}</td>
                    <td>{{$bso_log->bso_state->title}}</td>
                </tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>




<style>

    .bso_table {
        font: 12px arial;
        border: 1px solid #777;
        border-collapse: collapse;
    }
    .bso_table td, th {
        border: 1px solid #777;
        padding: 5px;
        font: 12px arial;
    }

    .bso_table th {
        background-color: #EEE;
    }

    .bso_table td {
        background-color: #FFF;
    }

    .bso_header {
        font: 12px arial;
        border: none;
        border-collapse: collapse;
        width: 100%;
    }
    .bso_header td {
        padding: 5px;
        border: none;
        font: 12px arial;
        background-color: #F3F3F3;
    }

    .sk_header {
        font: bold 17px arial !important;
    }

    .center {
        text-align: center !important;
    }

    .gray {
        background-color: #EEE !important;
    }
    input[type=button] {
        cursor: pointer;
    }


</style>