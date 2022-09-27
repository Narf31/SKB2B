@php
    $type = in_array($contract->status_id, [3,4]) ? 'view' : 'edit';
@endphp

<div class="form-horizontal" id="main_container">

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
    @include("contracts.online.views.{$contract->product->slug}", ['contract' => $contract])
    </div>

    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        @include('contracts.contract.edit.documents', ['contract' => $contract])
    </div>

</div>
