<table class="tov-table">
    <thead>
        <tr>
            <th><a href="#">{{ trans('settings/departments.title') }}</a></th>
            <th><a href="#">Тип</a></th>
            <th><a href="#">Роль</a></th>
        </tr>
    </thead>
    @if(sizeof($departments))
        @foreach($departments as $department)
            <tr onclick="openFancyBoxFrame('{{ url("/settings/departments/$department->id/edit")  }}')">
                <td>{{ $department->title }}</td>
                <td>{{ ($department->type_org) ?$department->type_org->title:''}}</td>
                <td>{{ ($department->role)?$department->role->title:'' }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="3" class="text-center">Не создано ни одного подразделения</td>
        </tr>
    @endif
</table>

