{{--Условия договора--}}
@include('contracts.default.terms.kasko.standard.edit', [
    'contract'=>$contract,
])



{{--Участники договора--}}
{{--Страхователь--}}
@include('contracts.default.subject.edit', [
    'subject_title' => 'Страхователь',
    'subject_name' => 'insurer',
    'general_document' => '-1',
    'is_lat' => 0,
    'set_select_type' => [0=>"ФЛ", 3=>'ЮЛ'],
    'subject' => (isset($contract->insurer)?$contract->insurer:new \App\Models\Contracts\Subjects())
])


{{--Собственник--}}
@include('contracts.default.subject.edit', [
    'subject_title' => 'Собственник',
    'subject_name' => 'owner',
    'general_document' => '-1',
    'is_insurer' => (!$contract->owner_id || $contract->owner_id == $contract->insurer_id ? 1 : 0 ),
    'is_lat' => 0,
    'set_select_type' => [0=>"ФЛ", 3=>'ЮЛ'],
    'subject' => (isset($contract->owner)?$contract->owner:new \App\Models\Contracts\Subjects())
])


{{--Выгодоприобретатель--}}
@include('contracts.default.subject.edit', [
    'subject_title' => 'Выгодоприобретатель',
    'subject_name' => 'beneficiar',
    'general_document' => '-1',
    'is_insurer' => (!$contract->beneficiar_id || $contract->beneficiar_id == $contract->insurer_id ? 1 : 0 ),
    'is_owner' => (!$contract->beneficiar_id || ($contract->owner_id > 0 && $contract->beneficiar_id != $contract->insurer_id && $contract->beneficiar_id == $contract->owner_id) ? 1 : 0 ),
    'is_beneficiar' => (!$contract->beneficiar_id || ($contract->beneficiar_id != $contract->insurer_id && $contract->beneficiar_id != $contract->owner_id && $contract->beneficiar_id == $contract->beneficiar_id) ? 1 : 0 ),
    'is_lat' => 0,
    'set_select_type' => [0=>"ФЛ", 3=>'ЮЛ'],
    'subject' => (isset($contract->beneficiar)?$contract->beneficiar:new \App\Models\Contracts\Subjects())
])

{{--Водители--}}
@include('contracts.default.insured.drivers.edit', [
    'insurers' => $contract->contracts_insurers
])

{{--Транспортное средство--}}
@include('contracts.default.insurance_object.auto.kasko.edit', [
    'object'=>(isset($contract->object_insurer_auto))?$contract->object_insurer_auto:new \App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto()
])

{{-- Дополнительное оборудование --}}
@include('contracts.default.insurance_object.auto.equipment.edit', [
    'equipments'=>(isset($contract->object_equipment))?$contract->object_equipment:new \App\Models\Contracts\ObjectInsurer\ObjectEquipmentAuto()
])



<div id="loadPage" style="position: fixed;
left: 0;
top: 0;
width: 100%;
height: 100%;
z-index: 99;
background: url('/assets/img/spinner.svg') 50% 50% no-repeat rgb(249,249,249);
opacity: 1;"></div>


@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>

    <script src="/js/online.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">



    @include('contracts.product.kasko.standard.js')
@stop

