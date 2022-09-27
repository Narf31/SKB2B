


{{--Участники договора--}}
{{--Страхователь--}}
@include('contracts.default.subject.edit', [
    'subject_title' => 'Арбитражный управляющий',
    'subject_name' => 'insurer',
    'general_document' => '-1',
    'is_lat' => 0,
    'set_select_type' => [0=>"ФЛ"],
    'subject' => (isset($contract->insurer)?$contract->insurer:new \App\Models\Contracts\Subjects())
])



{{--Условия договора--}}
@include('contracts.default.terms.arbitration.yearly.edit', [
    'contract'=>$contract,
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



    @include('contracts.product.arbitration.yearly.js')
@stop

