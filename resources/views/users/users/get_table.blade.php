@if(sizeof($users))

    <table class="tov-table">
        <thead>
        <tr>
            <th><a href="javascript:void(0);">ID</a></th>
            <th><a href="javascript:void(0);">{{ trans('users/users.index.name') }}</a></th>
            <th><a href="javascript:void(0);">Руководитель</a></th>
            <th><a href="javascript:void(0);">Куратор</a></th>
            <th><a href="javascript:void(0);">{{ trans('users/users.index.role') }}</a></th>
            <th><a href="javascript:void(0);">Организация</a></th>
            <th><a href="javascript:void(0);">Статус</a></th>
        </tr>
        </thead>
        @foreach($users as $user)
            <tr class="clickable-row" onclick="location.href = '{{url ("/users/users/$user->id/edit")}}'; " data-href="{{url ("/users/users/$user->id/edit")}}">
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->perent ? $user->perent->name : '' }}</td>
                <td>{{ $user->curator ? $user->curator->name : '' }}</td>
                <td>{{ $user->role ? $user->role->title : '' }}</td>
                <td>{{ $user->organization ? $user->organization->title : '' }}</td>
                <td>{{ $user->status_user_id == 0 ? 'Работает' : 'Уволен' }}</td>
            </tr>
        @endforeach
    </table>

@else
    {{ trans('form.empty') }}
@endif