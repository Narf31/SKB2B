<div class="form-group">
    <label class="col-sm-4 control-label">Описание</label>
    <div class="col-sm-8">
        {{ Form::text('description', old('description'), ['class' => 'form-control', 'required']) }}
    </div>
</div>

<div class="form-group">
    <label class="col-sm-4 control-label">Тип</label>
    <div class="col-sm-8">
        @php($type_select = collect(['cash'=>'Наличные', 'cashless'=>'Безналичные', 'sk' =>'СК']))
        @php($type_selected = request('parent_agent_id') ? request()->query('parent_agent_id') : 0)
        {{ Form::select('type', $type_select, $type_selected, ['class' => 'form-control select2 select2-all', 'id'=>'type', 'required']) }}
    </div>
</div>