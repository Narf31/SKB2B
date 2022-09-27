

{{--Условия договора--}}
@include('contracts.default.terms.mortgage.edit', [
    'contract'=>$contract,
])



{{--Участники договора--}}
{{--Страхователь--}}
@include('contracts.default.subject.edit', [
    'subject_title' => 'Заемщик',
    'subject_name' => 'insurer',
    'general_document' => '-1',
    'set_select_type' => [0=>"ФЛ"],
    'subject' => (isset($contract->insurer)?$contract->insurer:new \App\Models\Contracts\Subjects())
])

<input type="hidden" name="contract[beneficiar][is_insurer]" value="1"/>


{{--Созаемщики--}}
@include('contracts.default.insured.coborrowers.edit', [
    'insurers' => $contract->contracts_insurers
])



@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>

    <script src="/js/online.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">



    @include('contracts.product.mortgage.js')
@stop

