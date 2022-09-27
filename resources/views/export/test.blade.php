<table>
    @foreach($result as $res)
        <tr>
            <td colspan="4">{{$res['title']}}</td>
        </tr>
        @foreach($res['value'] as $value)
            <tr>
                <td>{{$value['prav_title']}}</td>
                <td>{{$value['prav_url']}}</td>
                <td>{{$value['tarif_title']}}</td>
                <td>{{$value['tarif_url']}}</td>
            </tr>
        @endforeach
    @endforeach
</table>