<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

    @if($view == 'edit' && $damage->status_id == 2)
    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        @include("orders.damages.partials.button_work", ['damage' => $damage])
    </div>
    @endif

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        @include("orders.damages.partials.damages_{$view}", ['damage' => $damage])
    </div>

    @if($view == 'view' && $damage->status_id == 2)
        <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
            @include("orders.damages.partials.button_work", ['damage' => $damage])
        </div>
    @endif

</div>


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        {{--Информация по договору--}}
        @include('contracts.default.info.view_contract', [
            'contract' => $damage->contract,
            'is_link' => 1,
        ])

        <br/>
    </div>

    <div class="row col-xs-12 col-sm-12 col-md-12 col-lg-12">
        {{--Страхователь--}}
        @include('contracts.default.subject.view', [
            'subject_title' => 'Страхователь',
            'subject_name' => 'insurer',
            'subject' => $damage->contract->insurer
        ])
    </div>

</div>

<script>

    function startMainFunctions()
    {
        initActivForms();
    }

</script>