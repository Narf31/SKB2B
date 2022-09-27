

{{--Условия договора--}}
@include('contracts.default.terms.prf.edit', [
    'contract'=>$contract,
    'terms' => ($contract->calculation && strlen($contract->calculation->risks) > 0)?json_decode($contract->calculation->risks):[],
])



{{--Участники договора--}}
{{--Страхователь--}}
@include('contracts.default.subject.edit', [
    'subject_title' => 'Страхователь',
    'subject_name' => 'insurer',
    'general_document' => '-1',
    'set_select_type' => [2=>"ФЛ", 3=>'ЮЛ'],
    'subject' => (isset($contract->insurer)?$contract->insurer:new \App\Models\Contracts\Subjects())
])

<input type="hidden" name="contract[beneficiar][is_insurer]" value="1"/>


{{--Застрахованный--}}
@include('contracts.default.insured.prf.edit', [
    'insurers' => $contract->contracts_insurers
])



@section('js')

    <script src="/js/jquery.datetimepicker.full.min.js"></script>

    <script src="/js/online.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">



    @include('contracts.product.prf.js')
@stop

