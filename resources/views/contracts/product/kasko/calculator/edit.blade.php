{{--Условия договора--}}
@include('contracts.default.terms.kasko.calculator.edit', [
    'contract'=>$contract,
])


@include('contracts.default.insurance_object.auto.kasko.calculator.edit', [
    'object'=>(isset($contract->object_insurer_auto))?$contract->object_insurer_auto:new \App\Models\Contracts\ObjectInsurer\ObjectInsurerAuto(),
    'subject' => (isset($contract->insurer)?$contract->insurer:new \App\Models\Contracts\Subjects())
])


<input type="hidden" name="contract[beneficiar][is_insurer]" value="1"/>
<input type="hidden" name="contract[owner][is_insurer]" value="1"/>

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



    @include('contracts.product.kasko.calculator.js')
@stop

