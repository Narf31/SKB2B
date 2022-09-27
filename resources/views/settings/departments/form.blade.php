
<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('settings/departments.title') }}</label>
    <div class="col-sm-8">
        {{ Form::text('title', old('title'), ['class' => 'form-control', 'required']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Роль</label>
    <div class="col-sm-8">
        {{ Form::select('role_id', \App\Models\Users\Role::all()->pluck('title', 'id')->prepend(trans('form.select.not_selected'), ''), old('role_id'), ['class' => 'form-control select2-ws', 'required']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">{{ trans('organizations/organizations.type') }}</label>
    <div class="col-sm-8">
        {{ Form::select('org_type_id', \App\Models\Settings\TypeOrg::where('is_actual', 1)->orderBy('title')->get()->pluck('title', 'id'), old('org_type_id'),  ['class' => 'form-control select2-ws org_type_id']) }}
    </div>
</div>
<div class="form-group">
    <label class="col-sm-4 control-label">Страница входа</label>
    <div class="col-sm-8">
        {{ Form::select('page_enthy', collect(\App\Models\Settings\Department::PAGE_ENTRY), old('page_enthy'),  ['class' => 'form-control select2-ws']) }}
    </div>
</div>