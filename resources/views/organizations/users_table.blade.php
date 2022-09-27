<table class="tov-table">
    <tbody>
        <tr class="sort-row">
            <th>{{ trans('users/users.index.name') }}</th>
            <th>Роль</th>
            <th>Статус</th>

        </tr>
        @if(sizeof($users))
            @foreach($users as $user)

                <tr class="clickable-row" @if(auth()->user()->hasPermission('directories', 'organizations_user')) onclick="openFancyBoxFrame('/users/frame/?user_id={{$user->id}}&org_id={{$organization->id}}')" @endif>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->role ? $user->role->title : '' }}</td>
                    <td>{{ \App\Models\User::STATUS_USER[$user->status_user_id] }}</td>

                </tr>
            @endforeach

        @else
            <tr>
                <td colspan="4" class="text-center">Не создано ни одного пользователя</td>
            </tr>
        @endif
    </tbody>
</table>