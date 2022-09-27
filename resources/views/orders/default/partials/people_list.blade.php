<input type="hidden" id="order-latitude" value="{{$damage->latitude}}"/>
<input type="hidden" id="order-longitude" value="{{$damage->longitude}}"/>
<input type="hidden" id="order-title" value="Заявка #{{$damage->id}} {{setDateTimeFormatRu($damage->begin_date)}}"/>
<input type="hidden" id="order-coment" value="{{$damage->address}} - {{$damage->comments}}"/>

<table class="table table-striped table-bordered table_for_yamap table_for_users">
    <tbody>
    @foreach($users as $user)

        <tr onclick="go_point({{$user->id}}, 'user')"
            id="tr_user_{{$user->id}}"
            data-id="{{$user->id}}"
            data-name="{{$user->name}}"
            data-phone="{{$user->phone}}"
            data-org="{{$user->organizations_title}}"
            data-latitude="{{$user->latitude}}"
            data-longitude="{{$user->longitude}}" >
            <td><b>{{$user->name}}</b></td>
            <td>{{$user->organizations_title}}</td>
        </tr>

    @endforeach

    </tbody>
</table>