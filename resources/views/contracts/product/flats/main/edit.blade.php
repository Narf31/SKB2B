{{--Условия договора--}}
@include('contracts.default.terms.flats.edit', [
    'contract'=>$contract,
    'object'=>(isset($contract->object_insurer_flats))?$contract->object_insurer_flats:new \App\Models\Contracts\ObjectInsurer\ObjectInsurerFlats(),
    'terms' => ($contract->calculation && strlen($contract->calculation->risks) > 0)?json_decode($contract->calculation->risks):[],
])




{{--Участники договора--}}
{{--Страхователь--}}
@include('contracts.default.subject.edit', [
    'subject_title' => 'Страхователь',
    'subject_name' => 'insurer',
    'general_document' => '0',
    'subject' => (isset($contract->insurer)?$contract->insurer:new \App\Models\Contracts\Subjects())
])

<input type="hidden" name="contract[beneficiar][is_insurer]" value="1"/>





@section('js')
    <script src="/js/jquery.datetimepicker.full.min.js"></script>

    <script src="/js/online.js"></script>
    <link rel="stylesheet" href="/css/jquery.datetimepicker.min.css">


    @include('contracts.product.flats.js')
@stop

