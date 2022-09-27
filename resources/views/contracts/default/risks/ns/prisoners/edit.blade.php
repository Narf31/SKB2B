<div class="page-heading">
    <h2 class="inline-h1">Страховые риски</h2>
</div>

<div class="row form-horizontal" >
    <div class="block-main">
        <div class="block-sub">
            <div class="form-horizontal row col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <div class="row form-horizontal">

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3" >
                        <label class="control-label" style="width: 100%;max-width: none;">Страховая сумма от несчастного случая</label>
                        {{ Form::select("contract[ns_prisoners][insurance_amount_ns]", collect(\App\Models\Directories\Products\Data\NSPrisoners::INSURANCE_AMOUNT_NS) , $contract->data->insurance_amount_ns, ['class' => 'form-control select2-ws']) }}
                    </div>


                    <div class="col-md-6 col-lg-1" >
                        <div class="field form-col">
                            <div>
                                <label class="control-label">
                                    Туберкулез
                                </label>
                                <br/>
                                <input @if($contract->data->is_tuberculosis == 1) checked="checked" @endif class="easyui-switchbutton" data-options="onText:'Да',offText:'Нет'" name="contract[ns_prisoners][is_tuberculosis]" id="ns_prisoners_is_tuberculosis" type="checkbox">

                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-3 is_tuberculosis" >
                        <label class="control-label" style="width: 100%;max-width: none;">Страховая сумма от туберкулеза</label>
                        {{ Form::select("contract[ns_prisoners][insurance_amount_tuberculosis]", collect(\App\Models\Directories\Products\Data\NSPrisoners::INSURANCE_AMOUNT_TUBERCULOSIS) , $contract->data->insurance_amount_tuberculosis, ['class' => 'form-control select2-ws']) }}
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>



<script>


    function initStartRisks(){

        viewTuberculosis();
        $('#ns_prisoners_is_tuberculosis').switchbutton({
            onChange: function(checked){
                viewTuberculosis();
            }
        });

    }


    function viewTuberculosis() {

        if($('#ns_prisoners_is_tuberculosis').prop('checked')){
            $('.is_tuberculosis').show();
        }else{
            $('.is_tuberculosis').hide();

        }

    }





</script>