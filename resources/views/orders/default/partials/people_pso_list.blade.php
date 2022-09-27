<input type="hidden" id="order-latitude" value="{{$order->latitude}}"/>
<input type="hidden" id="order-longitude" value="{{$order->longitude}}"/>
<input type="hidden" id="order-title" value="Заявка #{{$order->id}} {{setDateTimeFormatRu($order->begin_date)}}"/>
<input type="hidden" id="order-coment" value="{{$order->address}} - {{$order->comments}}"/>

<table class="table table-striped table-bordered table_for_yamap table_for_users">
    <tbody>
    @foreach($users as $user)

        <tr onclick="go_point({{$user->id}}, 'user')"
            id="tr_user_{{$user->id}}"
            data-id="{{$user->id}}"
            data-name="{{$user->name}}"
            data-phone="{{$user->phone}}"
            data-org="{{$user->organization->title}}"
            data-latitude="{{$user->point_sale->latitude}}"
            data-longitude="{{$user->point_sale->longitude}}" >
            <td><b>{{$user->name}}</b></td>
            <td>{{$user->organization->title}}</td>
        </tr>

    @endforeach

    </tbody>
</table>