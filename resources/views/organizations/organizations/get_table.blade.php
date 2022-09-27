@if(sizeof($organizations))
    <table class="tov-table">
        <thead>
            <tr>
                <th><a href="#">{{ trans('organizations/organizations.title') }}</a></th>
                <th><a href="#">Тип</a></th>
                <th><a href="#">Руководитель организации</a></th>
                <th><a href="#">Куратор</a></th>
                <th><a href="#">Контактное лицо</a></th>
                <th><a href="#">{{ trans('organizations/organizations.phone') }}</a></th>
            </tr>
        </thead>
        @foreach($organizations as $organization)
            <tr class="clickable-row" onclick="location.href = '{{url ("$control_url/organizations/$organization->id/edit")}}'; " data-href="{{url ("$control_url/organizations/$organization->id/edit")}}">
                <td>{{ $organization->title }}</td>
                <td>{{ $organization->org_type->title }}</td>
                <td>{{ (isset($organization->parent_user))?$organization->parent_user->name: '' }}</td>
                <td>{{ (isset($organization->curator))?$organization->curator->name: '' }}</td>
                <td>{{ $organization->user_contact_title }}</td>
                <td>{{ $organization->phone }}</td>
            </tr>
        @endforeach
    </table>
@else
    {{ trans('form.empty') }}
@endif
