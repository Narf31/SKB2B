<div class="block-sub">
    <div class="block-main-heading">Пользователь</div>
    <div class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label">{{ trans('users/roles.edit.title') }}</label>
            <div class="col-sm-9">
                {{ Form::text('title', old('title'), ['class' => 'form-control', 'required']) }}
            </div>
        </div>
    </div>
</div>
@foreach($groups as $group)
    @if(sizeof($group->permissions))
        <div class="divider"></div>
        <div class="block-sub">
            <div class="block-main-heading">{{ trans('users/roles.groups_titles.' . $group->title) }}</div>
            <div class="form-horizontal">
                <div class="row">
                    @foreach($group->permissions->chunk(4) as $permissionsChunk)
                        <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                            @foreach($permissionsChunk as $permission)
                                <div class="form-group">
                                    <label class="col-sm-8 control-label">
                                        @if($permission->subpermissions()->count())
                                            @if(isset($role) && $role->id > 0)
                                            <a href="javascript:void(0);" onclick="openFancyBoxFrame('{{ "/users/roles/{$role->id}/permission/{$permission->id}/subpermissions" }}')">
                                                {{ trans('users/roles.titles.' . $permission->title) }}
                                            </a>
                                            @else
                                            <a href="javascript:void(0);" onclick="flashMessage('danger', 'Настройка дополнительных прав не доступна без предварительного сохранения роли.');">
                                                {{ trans('users/roles.titles.' . $permission->title) }}
                                            </a>
                                            @endif
                                        @else
                                            {{ trans('users/roles.titles.' . $permission->title) }}
                                        @endif
                                    </label>
                                    <div class="col-sm-4">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"  name="permissions[]" value="{{ $permission->id }}"
                                                       @if(isset($role) && $role->hasPermission($permission->id))
                                                       checked
                                                        @endif
                                                >
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    @endforeach

                </div>
                @if($group->is_visibility == 1)
                    <div class="form-group">
                        <span class="col-sm-4 control-label">Видимость по базе</span>
                        <div class="col-sm-6">
                            <input type="hidden" name="groups[]" value="{{$group->id}}"/>
                            {{ Form::select("visibility[$group->id]", collect(\App\Models\Users\RolesVisibility::VISIBILITY), ((isset($role) && $role->rolesVisibility($group->id))?$role->rolesVisibility($group->id)->visibility:0), ['class' => 'form-control select2-ws', '']) }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
@endforeach


<button class="btn btn-primary btn-lg mb-30 pull-right" style="margin-top: 20px;">Сохранить</button>
