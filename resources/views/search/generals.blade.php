<table class="tov-table">
    <tr>
        <th>Названия/ФИО</th>
        <th>ИНН</th>
    </tr>
    <tbody>
    @foreach($generals as $general)
        <tr onclick="openPage('/general/subjects/edit/{{$general->id}}/')" style="cursor: pointer;">
            <td>{{$general->title}}</td>
            <td>{{$general->inn}}</td>
        </tr>
    @endforeach
    </tbody>
</table>