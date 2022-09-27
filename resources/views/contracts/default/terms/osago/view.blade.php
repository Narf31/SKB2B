
<div class="row form-horizontal" >
    <h2 class="inline-h1">Условия договора

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
            <span class="view-value">{{collect([0=>"Первичный", 1=>'Пролонгация'])[$contract->is_prolongation]}}
                @if($contract->is_prolongation == 1) {{$contract->prolongation_bso_title}} @endif
            </span>
        </div>


        <div class="view-field">
            <span class="view-label">Вид договора</span>
            <span class="view-value">{{\App\Models\Directories\Products\Data\Osago::CONTRACT_TYPE[(int)$contract->data->is_epolicy]}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Начало периода 1</span>
            <span class="view-value">{{getDateFormatRu($contract->data->period_beg1)}}</span>
        </div>

        <div class="view-field">
            <span class="view-label">Конец  периода 1</span>
            <span class="view-value">{{getDateFormatRu($contract->data->period_end1)}}</span>
        </div>

        @if(strlen($contract->data->period_beg2) > 0)
            <div class="view-field">
                <span class="view-label">Начало периода 2</span>
                <span class="view-value">{{getDateFormatRu($contract->data->period_beg2)}}</span>
            </div>

            <div class="view-field">
                <span class="view-label">Конец  периода 2</span>
                <span class="view-value">{{getDateFormatRu($contract->data->period_end2)}}</span>
            </div>
        @endif

        @if(strlen($contract->data->period_beg3) > 0)
            <div class="view-field">
                <span class="view-label">Начало периода 3</span>
                <span class="view-value">{{getDateFormatRu($contract->data->period_beg3)}}</span>
            </div>

            <div class="view-field">
                <span class="view-label">Конец  периода 3</span>
                <span class="view-value">{{getDateFormatRu($contract->data->period_end3)}}</span>
            </div>
        @endif

        <div class="clear"></div>



    </div>



</div>


<script>

    function initTerms() {





    }



</script>