
<div class="row form-horizontal" >
    <h2 class="inline-h1">Условия договора
        <span class=" pull-right" data-intro='Копировать договор!' onclick="copyContract('{{$contract->id}}')"><i class="fa fa-clone" style="cursor: pointer;color: green;"></i></span>
    </h2>
    <br/><br/>

    <div class="row form-info col-xs-12 col-sm-12 col-md-12 col-lg-12">

        <div class="view-field">
            <span class="view-label">Дата заключения</span>
            <span class="view-value">{{setDateTimeFormatRu($contract->sign_date)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Дата начала</span>
            <span class="view-value">{{setDateTimeFormatRu($contract->begin_date)}}</span>
        </div>
        <div class="view-field">
            <span class="view-label">Дата окончания</span>
            <span class="view-value">{{setDateTimeFormatRu($contract->end_date)}}</span>
        </div>
        <div class="view-field">
            <span class="view-label">Тип договора</span>
            <span class="view-value">
                {{\App\Models\Directories\Products\Data\VZR::TYPE_AGR[$contract->data->type_agr_id]}}
            </span>
        </div>


        <div class="clear"></div>



    </div>



</div>


<script>

    function initTerms() {





    }



</script>