

{{--Условия договора--}}
@include('contracts.default.terms.dgo.edit', [
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

<input type="hidden" name="contract[beneficiar][is_insurer]" value="1"/>


{{--Водители--}}
@include('contracts.default.insured.drivers.edit', [
    'insurers' => $contract->contracts_insurers
])

{{--Транспортное средство--}}
@include('contracts.default.insurance_object.auto.dgo.edit', [
    'object'=>(isset($contract->object_insurer_auto))?$contract->object_insurer_auto:new \App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto()
])



@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>

    <script src="/js/online.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">



    @include('contracts.product.dgo.js')
@stop

