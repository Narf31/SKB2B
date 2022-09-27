<hr/>

<table class="table table-bordered">

    <tr>
        <td class="col-md-6 text-center">{{ trans('users/roles.edit.title') }}</td>

        <td class="col-md-6 text-center">
            {{ Form::text('title', old('title'), ['class' => 'form-control', 'required']) }}
        </td>

    </tr>

    @foreach($groups as $group)

        @if(sizeof($group->permissions))

            <tr>
                <th colspan="2" class="text-center">{{ trans('users/roles.groups_titles.' . $group->title) }}</th>
            </tr>

            @foreach($group->permissions as $permission)
                <tr>
                    <td class="col-md-6 text-center">{{ trans('users/roles.titles.' . $permission->title) }}</td>
                    <td class="col-md-6 text-center">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                               @if(isset($role) && $role->hasPermission($permission->id))
                               checked
                                @endif
                        >
                    </td>
                </tr>
            @endforeach

        @endif

    @endforeach

    <tr>
        <td></td>
        <td>
            <input type="submit" class="btn btn-default btn-blue pull-right" value="{{ trans('form.buttons.save') }}"/>
        </td>
    </tr>

</table>