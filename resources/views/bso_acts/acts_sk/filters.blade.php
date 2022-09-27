@php($select_params = ['class'=>'form-control select2-all select2-ws','onchange'=>'loadItems()'])

<div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
    <label class="control-label" for="user_id_from">Организация</label>
    {{ Form::select('org_id', $organizations->pluck('title', 'id')->prepend('Не выбрано', -1), request()->has('org_id') ? request()->get('org_id') : -1, $select_params) }}
</div>
<div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
    <label class="control-label" for="user_id_from">СК</label>
    {{ Form::select('insurance_id', $insurances->pluck('title', 'id')->prepend('Не выбрано', -1), request()->has('insurance_id') ? request()->get('insurance_id') : -1, $select_params) }}
</div>
<div class="btn-group col-xs-12 col-sm-12 col-md-3 col-lg-3">
    <label class="control-label" for="user_id_from">Поставщик</label>
    {{ Form::select('supplier_id', $suppliers->pluck('title', 'id')->prepend('Не выбрано', -1), request()->has('supplier_id') ? request()->get('supplier_id') : -1, $select_params) }}
</div>