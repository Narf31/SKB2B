

{{--Условия договора--}}
@include('contracts.default.terms.gap.edit', [
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

{{--Транспортное средство--}}
@include('contracts.default.insurance_object.auto.gap.edit', [
    'object'=>(isset($contract->object_insurer_auto))?$contract->object_insurer_auto:new \App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto()
])



@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>

    <script src="/js/online.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">



    @include('contracts.product.gap.js')
@stop

