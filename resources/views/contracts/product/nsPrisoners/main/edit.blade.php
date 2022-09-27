


{{--Участники договора--}}
{{--Страхователь--}}
@include('contracts.default.subject.edit', [
    'subject_title' => 'Страхователь',
    'subject_name' => 'insurer',
    'general_document' => '0',
    'set_type' => 0,
    'subject' => (isset($contract->insurer)?$contract->insurer:new \App\Models\Contracts\Subjects())
])

<input type="hidden" name="contract[beneficiar][is_insurer]" value="1"/>



{{--Застрахованный--}}
@include('contracts.default.insured.nsPrisoners.edit', [
    'insurer' => ((isset($contract->contracts_insurers) && isset($contract->contracts_insurers[0]))?$contract->contracts_insurers[0]:new \App\Models\Contracts\ContractsInsurer())
])

{{--Условия договора--}}
@include('contracts.default.terms.nsPrisoners.edit', [
    'contract'=>$contract,
    'terms' => ($contract->calculation && strlen($contract->calculation->risks) > 0)?json_decode($contract->calculation->risks):[],
])

{{--Страховые риски--}}
@include('contracts.default.risks.ns.prisoners.edit', [
    'contract'=>$contract,
])

@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>

    <script src="/js/online.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">



    @include('contracts.product.nsPrisoners.js')
@stop

